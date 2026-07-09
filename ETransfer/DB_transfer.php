<?php
/**
 * DB_transfer.php — REST API สำหรับ Safety Transfer System
 * ─────────────────────────────────────────────────────────
 *
 * Endpoints:
 *   POST   /DB_transfer.php/transfers             → สร้าง transfer ใหม่
 *   PUT    /DB_transfer.php/transfers/{id}        → อัปเดต transfer
 *   GET    /DB_transfer.php/transfers/{id}        → ดึงข้อมูล transfer
 *   GET    /DB_transfer.php/transfers             → ดึงรายการทั้งหมด
 *   POST   /DB_transfer.php/transfers/{id}/lock   → ล็อกข้อมูล
 *   POST   /DB_transfer.php/transfers/{id}/complete → บันทึกปลายทาง
 *   DELETE /DB_transfer.php/transfers/{id}        → ลบ transfer
 */

// ─── Config ───────────────────────────────────────────────
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');
define('DB_NAME', 'E-transfer port 3307');
define('DB_USER', 'root');          
define('DB_PASS', '');              
define('DB_CHARSET', 'utf8mb4');

// ─── CORS & Headers ───────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── DB Connection ────────────────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

// ─── Response Helpers ─────────────────────────────────────
function respond(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function respondSuccess($data = null, string $msg = 'success', int $code = 200): void {
    respond(['success' => true, 'message' => $msg, 'data' => $data], $code);
}

function respondError(string $msg, int $code = 400): void {
    respond(['success' => false, 'message' => $msg, 'data' => null], $code);
}

// ─── Route Parser ─────────────────────────────────────────
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$pathInfo = $_SERVER['PATH_INFO'] ?? '';

if (!$pathInfo && strpos($requestUri, $scriptName) === 0) {
    $pathInfo = substr($requestUri, strlen($scriptName));
} elseif (!$pathInfo) {
    // fallback if SCRIPT_NAME doesn't match
    $parts = explode('DB_transfer.php', $requestUri);
    $pathInfo = $parts[1] ?? '';
}

$segments = array_values(array_filter(explode('/', $pathInfo)));
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];

$resource = $segments[0] ?? '';
$resourceId = isset($segments[1]) && is_numeric($segments[1]) ? (int)$segments[1] : null;
$action = $segments[2] ?? '';

// ─── Router ───────────────────────────────────────────────
try {
    if ($resource !== 'transfers') {
        respondError('Resource not found', 404);
    }

    switch ($method) {
        case 'POST':
            if ($resourceId && $action === 'lock') {
                lockTransfer($resourceId);
            } elseif ($resourceId && $action === 'complete') {
                completeTransfer($resourceId, $body);
            } else {
                createTransfer($body);
            }
            break;

        case 'PUT':
            if (!$resourceId) respondError('ID required for update', 400);
            updateTransfer($resourceId, $body);
            break;

        case 'GET':
            if ($resourceId) {
                getTransfer($resourceId);
            } else {
                listTransfers();
            }
            break;

        case 'DELETE':
            if (!$resourceId) respondError('ID required for delete', 400);
            deleteTransfer($resourceId);
            break;

        default:
            respondError('Method not allowed', 405);
    }
} catch (PDOException $e) {
    error_log('DB Error: ' . $e->getMessage());
    respondError('Database error: ' . $e->getMessage(), 500);
} catch (Throwable $e) {
    error_log('App Error: ' . $e->getMessage());
    respondError('Server error: ' . $e->getMessage(), 500);
}

// ═══════════════════════════════════════════════════════════
// HANDLERS
// ═══════════════════════════════════════════════════════════

/**
 * สร้าง Transfer ใหม่ (POST /transfers)
 */
function createTransfer(array $data): void {
    if (empty($data['hn'])) {
        respondError('HN is required', 422);
    }

    $db = getDB();

    // ── Insert transfer ──
    $cols = getTransferColumns();
    $values = buildValues($data, $cols);

    $placeholders = implode(', ', array_map(fn($c) => ":$c", array_keys($values)));
    $colNames     = implode(', ', array_keys($values));

    $stmt = $db->prepare("INSERT INTO transfers ($colNames) VALUES ($placeholders)");
    $stmt->execute($values);
    $transferId = (int)$db->lastInsertId();

    // ── Insert waypoints ──
    if (!empty($data['waypoints']) && is_array($data['waypoints'])) {
        insertWaypoints($db, $transferId, $data['waypoints']);
    }

    $transfer = fetchTransfer($db, $transferId);
    respondSuccess($transfer, 'Transfer created', 201);
}

/**
 * อัปเดต Transfer (PUT /transfers/{id})
 */
function updateTransfer(int $id, array $data): void {
    $db = getDB();

    // ตรวจสอบว่ามี transfer นี้
    $existing = fetchTransfer($db, $id);
    if (!$existing) respondError('Transfer not found', 404);
    // if ($existing['is_locked']) respondError('Transfer is locked and cannot be edited', 403);

    $cols   = getTransferColumns();
    $values = buildValues($data, $cols);

    if (empty($values)) respondError('No valid fields to update', 422);

    $sets = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($values)));
    $values['_id'] = $id;

    $db->prepare("UPDATE transfers SET $sets WHERE id = :_id")->execute($values);

    // อัปเดต waypoints — ลบเก่าแล้วใส่ใหม่
    if (isset($data['waypoints']) && is_array($data['waypoints'])) {
        $db->prepare('DELETE FROM transfer_waypoints WHERE transfer_id = ?')->execute([$id]);
        insertWaypoints($db, $id, $data['waypoints']);
    }

    $transfer = fetchTransfer($db, $id);
    respondSuccess($transfer, 'Transfer updated');
}

/**
 * ดึงข้อมูล Transfer (GET /transfers/{id})
 */
function getTransfer(int $id): void {
    $db = getDB();
    $transfer = fetchTransfer($db, $id);
    if (!$transfer) respondError('Transfer not found', 404);

    // ดึง waypoints
    $wp = $db->prepare('SELECT * FROM transfer_waypoints WHERE transfer_id = ? ORDER BY seq');
    $wp->execute([$id]);
    $transfer['waypoints'] = $wp->fetchAll();

    respondSuccess($transfer);
}

/**
 * รายการ Transfers ทั้งหมด (GET /transfers?limit=20&offset=0)
 */
function listTransfers(): void {
    $limit  = min((int)($_GET['limit']  ?? 50), 200);
    $offset = (int)($_GET['offset'] ?? 0);
    $hn     = $_GET['hn'] ?? null;
    $status = $_GET['status'] ?? null;

    $db = getDB();
    $where = [];
    $params = [];

    if ($hn) {
        $where[] = 'hn LIKE :hn';
        $params['hn'] = "%$hn%";
    }
    if ($status) {
        $where[] = 'status = :status';
        $params['status'] = $status;
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $countStmt = $db->prepare("SELECT COUNT(*) FROM v_transfer_summary $whereClause");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    $params['limit']  = $limit;
    $params['offset'] = $offset;

    $stmt = $db->prepare(
        "SELECT * FROM v_transfer_summary $whereClause
         ORDER BY created_at DESC
         LIMIT :limit OFFSET :offset"
    );
    // PDO requires bindValue for LIMIT/OFFSET with integer type
    foreach ($params as $k => $v) {
        if ($k === 'limit' || $k === 'offset') {
            $stmt->bindValue(":$k", $v, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(":$k", $v);
        }
    }
    $stmt->execute();
    $rows = $stmt->fetchAll();

    respondSuccess([
        'total'   => $total,
        'limit'   => $limit,
        'offset'  => $offset,
        'records' => $rows,
    ]);
}

/**
 * ล็อกข้อมูลส่วนที่ 1-5 (POST /transfers/{id}/lock)
 */
function lockTransfer(int $id): void {
    $db = getDB();
    $existing = fetchTransfer($db, $id);
    if (!$existing) respondError('Transfer not found', 404);

    $db->prepare(
        "UPDATE transfers SET is_locked = 1, status = 'sent', locked_at = NOW()
         WHERE id = ?"
    )->execute([$id]);

    respondSuccess(['id' => $id, 'is_locked' => true], 'Transfer locked');
}

/**
 * บันทึกข้อมูลปลายทาง + complete (POST /transfers/{id}/complete)
 */
function completeTransfer(int $id, array $data): void {
    $db = getDB();
    $existing = fetchTransfer($db, $id);
    if (!$existing) respondError('Transfer not found', 404);

    $allowed = [
        'dest_time_in', 'dest_receiver',
        'dest_rr', 'dest_spo2', 'dest_airo2', 'dest_consciousness',
        'dest_sbp', 'dest_pulse', 'dest_temp', 'dest_copd',
        'dest_hr_peds', 'dest_resp_effort',
        'dest_news_score', 'dest_pews_score',
        'log_time_in', 'log_receiver',
    ];

    $updates = [];
    foreach ($allowed as $col) {
        if (array_key_exists($col, $data)) {
            $updates[$col] = $data[$col] === '' ? null : $data[$col];
        }
    }
    $updates['status']       = 'completed';
    $updates['is_completed'] = 1;
    $updates['completed_at'] = date('Y-m-d H:i:s');

    $sets = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($updates)));
    $updates['_id'] = $id;
    $db->prepare("UPDATE transfers SET $sets WHERE id = :_id")->execute($updates);

    respondSuccess(['id' => $id, 'status' => 'completed'], 'Transfer completed');
}

/**
 * ลบ Transfer (DELETE /transfers/{id})
 */
function deleteTransfer(int $id): void {
    $db = getDB();
    $existing = fetchTransfer($db, $id);
    if (!$existing) respondError('Transfer not found', 404);

    $db->prepare('DELETE FROM transfers WHERE id = ?')->execute([$id]);
    respondSuccess(['id' => $id], 'Transfer deleted');
}

// ─── Helper: Insert Waypoints ─────────────────────────────
function insertWaypoints(PDO $db, int $transferId, array $waypoints): void {
    $stmt = $db->prepare(
        'INSERT INTO transfer_waypoints
         (transfer_id, seq, location_name, time_arrived, time_departed,
          rr, spo2, airo2, consciousness, sbp, pulse, temp,
          hr_peds, resp_effort, news_score, pews_score, note)
         VALUES
         (:transfer_id, :seq, :location_name, :time_arrived, :time_departed,
          :rr, :spo2, :airo2, :consciousness, :sbp, :pulse, :temp,
          :hr_peds, :resp_effort, :news_score, :pews_score, :note)'
    );

    foreach ($waypoints as $i => $wp) {
        $stmt->execute([
            'transfer_id'   => $transferId,
            'seq'           => $i + 1,
            'location_name' => $wp['location_name'] ?? null,
            'time_arrived'  => normalizeTime($wp['time_arrived'] ?? null),
            'time_departed' => normalizeTime($wp['time_departed'] ?? null),
            'rr'            => $wp['rr'] ?? null,
            'spo2'          => $wp['spo2'] ?? null,
            'airo2'         => $wp['airo2'] ?? 'room',
            'consciousness' => $wp['consciousness'] ?? 'alert',
            'sbp'           => $wp['sbp'] ?? null,
            'pulse'         => $wp['pulse'] ?? null,
            'temp'          => $wp['temp'] ?? null,
            'hr_peds'       => $wp['hr_peds'] ?? null,
            'resp_effort'   => $wp['resp_effort'] ?? 'normal',
            'news_score'    => (int)($wp['news_score'] ?? 0),
            'pews_score'    => (int)($wp['pews_score'] ?? 0),
            'note'          => $wp['note'] ?? null,
        ]);
    }
}

// ─── Helper: Fetch single transfer ────────────────────────
function fetchTransfer(PDO $db, int $id): ?array {
    $stmt = $db->prepare('SELECT * FROM transfers WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

// ─── Helper: Column whitelist ─────────────────────────────
function getTransferColumns(): array {
    return [
        'hn','patient_name','ward','destination','age_group',
        'rr','spo2','airo2','consciousness','sbp','pulse','temp',
        'copd','hr_peds','resp_effort','news_score','pews_score',
        'comm','breath','o2_type','vitals_stable','vital_monitor',
        'sedation','ctas_level','risk_suicide','risk_mdrs','risk_child',
        'risk_other','severity_level',
        'tube_ng','tube_peg','tube_icd','tube_icd_side',
        'tube_redivac','tube_redivac_location','tube_pcn','tube_pcn_location',
        'tube_foley','tube_other','tube_other_detail',
        'chk_identify','chk_abc','eq_emergency_bag','eq_infusion',
        'eq_ventilator','eq_ekg','eq_records',
        'pers_doctor','pers_doctor_name','pers_rn','pers_rn_name',
        'pers_pn','pers_pn_name','pers_aide','pers_aide_name',
        'o2_cylinder_size','o2_pressure','o2_safe_residual','o2_flow_rate',
        'o2_time_result','hfnc_flow','hfnc_fio2','niv_total_flow',
        'niv_safety_margin','vent_mv','vent_fio2','vent_type',
        'sec6_enabled','sec6_consciousness','sec6_breathing',
        'iv_pls','iv_aline','iv_cline','iv_dlc','iv_perm','iv_other',
        'sec6_tube','sec6_ostomy','sec6_traction','sec6_others',
        'rights_status','rights_detail','billing_status','billing_other',
        'sec6_leftover_meds','sec6_problems',
        'log_time_out','log_sender','log_time_in','log_receiver',
    ];
}

// ─── Helper: Build sanitized values ───────────────────────
function buildValues(array $data, array $allowed): array {
    $out = [];
    foreach ($allowed as $col) {
        if (!array_key_exists($col, $data)) continue;
        $val = $data[$col];
        // แปลงค่าว่างเป็น null
        $out[$col] = ($val === '' || $val === false) ? null : $val;
    }
    return $out;
}

// ─── Helper: Normalize TIME value ─────────────────────────
function normalizeTime(?string $t): ?string {
    if (!$t) return null;
    // รองรับทั้ง "HH:MM" และ "HH:MM:SS"
    if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $t)) return $t;
    return null;
}