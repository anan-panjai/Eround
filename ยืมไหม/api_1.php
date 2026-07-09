<?php
/**
 * api.php — ระบบยืม-คืนอุปกรณ์ระหว่างหอผู้ป่วย
 *
 * สถานะ (status) ของแต่ละรายการ:
 *   borrowed   → กำลังยืมอยู่ (เพิ่งบันทึก)
 *   returning  → หอที่ยืมแจ้งส่งคืนแล้ว รอหอเจ้าของกด "รับของคืน"
 *   returned   → คืนเรียบร้อยแล้ว (หอเจ้าของกด "รับของคืน" แล้ว)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// ── การเชื่อมต่อ Database ───────────────────────────────────────────────────
$host   = 'localhost';
$dbname = 'borrow_db';       // ← เปลี่ยนตามชื่อ database ของคุณ
$user   = 'root';            // ← เปลี่ยนตาม username
$pass   = '';                // ← เปลี่ยนตาม password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Connection Error: ' . $e->getMessage()]);
    exit;
}

// สร้างตารางอัตโนมัติถ้ายังไม่มี
$pdo->exec("
    CREATE TABLE IF NOT EXISTS borrow_transactions (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        equipment_name       VARCHAR(255) NOT NULL,
        quantity             INT NOT NULL DEFAULT 1,
        borrower_ward        VARCHAR(255) NOT NULL,
        lender_ward          VARCHAR(255) NOT NULL,
        borrower_name        VARCHAR(255) NOT NULL,
        expected_return_date DATE NOT NULL,
        status               ENUM('borrowed','returning','returned') DEFAULT 'borrowed',
        receiver_name        VARCHAR(255) DEFAULT NULL,
        return_note          TEXT DEFAULT NULL,
        return_date          DATETIME DEFAULT NULL,
        created_at           DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// ── Router ─────────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? '';
$body   = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($action) {

    // ─────────────────────────────────────────────────────────────────────
    // บันทึกการยืม (รองรับหลายรายการ)
    // ─────────────────────────────────────────────────────────────────────
    case 'borrow':
        $items               = $body['items']                ?? [];
        $borrower_ward       = trim($body['borrower_ward']   ?? '');
        $lender_ward         = trim($body['lender_ward']     ?? '');
        $borrower_name       = trim($body['borrower_name']   ?? '');
        $expected_return_date = trim($body['expected_return_date'] ?? '');

        if (empty($items) || !$borrower_ward || !$lender_ward || !$borrower_name || !$expected_return_date) {
            echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
            exit;
        }
        if ($borrower_ward === $lender_ward) {
            echo json_encode(['success' => false, 'message' => 'หอผู้ยืมและหอผู้ให้ยืมต้องไม่เป็นหอเดียวกัน']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO borrow_transactions
                (equipment_name, quantity, borrower_ward, lender_ward, borrower_name, expected_return_date, status)
            VALUES
                (:equipment_name, :quantity, :borrower_ward, :lender_ward, :borrower_name, :expected_return_date, 'borrowed')
        ");

        $pdo->beginTransaction();
        try {
            foreach ($items as $item) {
                $stmt->execute([
                    ':equipment_name'       => trim($item['equipment_name'] ?? ''),
                    ':quantity'             => max(1, intval($item['quantity'] ?? 1)),
                    ':borrower_ward'        => $borrower_ward,
                    ':lender_ward'          => $lender_ward,
                    ':borrower_name'        => $borrower_name,
                    ':expected_return_date' => $expected_return_date,
                ]);
            }
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'บันทึกสำเร็จ']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ─────────────────────────────────────────────────────────────────────
    // ดึงรายการตามสถานะ (สำหรับ Tab ประวัติ)
    // ─────────────────────────────────────────────────────────────────────
    case 'get_transactions':
        $status = $_GET['status'] ?? 'borrowed';
        $stmt = $pdo->prepare("
            SELECT * FROM borrow_transactions
            WHERE status = :status
            ORDER BY created_at DESC
        ");
        $stmt->execute([':status' => $status]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    // ─────────────────────────────────────────────────────────────────────
    // ดึงรายการ "ของที่หอนี้ให้ยืมออกไป" (Tab: ของที่เราให้ยืมออกไป)
    // แสดงเฉพาะ borrowed + returning
    // ─────────────────────────────────────────────────────────────────────
    case 'get_lender_transactions':
        $ward = $_GET['ward'] ?? '';
        if (!$ward) { echo json_encode(['success' => false, 'message' => 'ไม่ระบุหอผู้ป่วย']); exit; }

        $stmt = $pdo->prepare("
            SELECT * FROM borrow_transactions
            WHERE lender_ward = :ward
              AND status IN ('borrowed', 'returning')
            ORDER BY
                CASE status WHEN 'returning' THEN 0 ELSE 1 END,
                expected_return_date ASC
        ");
        $stmt->execute([':ward' => $ward]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    // ─────────────────────────────────────────────────────────────────────
    // ดึงรายการ "ของที่หอนี้ยืมมา" (Tab: ของที่เรายืมมา)
    // แสดงเฉพาะ borrowed + returning
    // ─────────────────────────────────────────────────────────────────────
    case 'get_borrower_transactions':
        $ward = $_GET['ward'] ?? '';
        if (!$ward) { echo json_encode(['success' => false, 'message' => 'ไม่ระบุหอผู้ป่วย']); exit; }

        $stmt = $pdo->prepare("
            SELECT * FROM borrow_transactions
            WHERE borrower_ward = :ward
              AND status IN ('borrowed', 'returning')
            ORDER BY
                CASE status WHEN 'borrowed' THEN 0 ELSE 1 END,
                expected_return_date ASC
        ");
        $stmt->execute([':ward' => $ward]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    // ─────────────────────────────────────────────────────────────────────
    // หอที่ยืมแจ้งส่งคืน → เปลี่ยน status เป็น 'returning'
    // ─────────────────────────────────────────────────────────────────────
    case 'send_return':
        $transaction_id = intval($body['transaction_id'] ?? 0);
        if (!$transaction_id) { echo json_encode(['success' => false, 'message' => 'ไม่พบ transaction_id']); exit; }

        $stmt = $pdo->prepare("
            UPDATE borrow_transactions
            SET status = 'returning'
            WHERE id = :id AND status = 'borrowed'
        ");
        $stmt->execute([':id' => $transaction_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่พบรายการ หรือสถานะไม่ถูกต้อง']);
        }
        break;

    // ─────────────────────────────────────────────────────────────────────
    // หอที่ให้ยืมกด "รับของคืน" → เปลี่ยน status เป็น 'returned'
    // ─────────────────────────────────────────────────────────────────────
    case 'receive_return':
        $transaction_id = intval($body['transaction_id'] ?? 0);
        $receiver_name  = trim($body['receiver_name']   ?? '');
        $return_note    = trim($body['return_note']     ?? '');

        if (!$transaction_id || !$receiver_name) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกชื่อผู้รับคืน']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE borrow_transactions
            SET status        = 'returned',
                receiver_name = :receiver_name,
                return_note   = :return_note,
                return_date   = NOW()
            WHERE id = :id AND status = 'returning'
        ");
        $stmt->execute([
            ':id'            => $transaction_id,
            ':receiver_name' => $receiver_name,
            ':return_note'   => $return_note,
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่พบรายการ หรือยังไม่ได้แจ้งส่งคืน']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => "Unknown action: $action"]);
}
