<?php
$host = 'localhost';
$port = '3307';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbname = '`E-transfer port 3307`';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    $pdo->exec("USE $dbname");
    
    // 1. Wards Table (optional, but good for standardization)
    $sql_wards = "
    CREATE TABLE IF NOT EXISTS borrow_wards (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_wards);
    echo "Table 'borrow_wards' created successfully.<br>\n";

    // Insert some default wards if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM borrow_wards");
    if ($stmt->fetchColumn() == 0) {
        $default_wards = ['ICU', 'ER', 'OR', 'Ward 1', 'Ward 2', 'NICU', 'PICU'];
        $insert = $pdo->prepare("INSERT INTO borrow_wards (name) VALUES (?)");
        foreach ($default_wards as $w) {
            $insert->execute([$w]);
        }
        echo "Default wards inserted.<br>\n";
    }

    // 2. Equipment Catalog
    $sql_eq = "
    CREATE TABLE IF NOT EXISTS borrow_equipment (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        category VARCHAR(50) DEFAULT 'ทั่วไป',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_eq);
    echo "Table 'borrow_equipment' created successfully.<br>\n";

    // Insert some default equipment
    $stmt = $pdo->query("SELECT COUNT(*) FROM borrow_equipment");
    if ($stmt->fetchColumn() == 0) {
        $default_eq = [
            ['Infusion Pump', 'เครื่องมือแพทย์'],
            ['Syringe Pump', 'เครื่องมือแพทย์'],
            ['Ventilator', 'เครื่องมือแพทย์'],
            ['Defibrillator', 'เครื่องมือแพทย์'],
            ['EKG Monitor', 'เครื่องมือแพทย์'],
            ['รถเข็นนั่ง (Wheelchair)', 'พาหนะ'],
            ['เปลนอน (Stretcher)', 'พาหนะ']
        ];
        $insert = $pdo->prepare("INSERT INTO borrow_equipment (name, category) VALUES (?, ?)");
        foreach ($default_eq as $eq) {
            $insert->execute([$eq[0], $eq[1]]);
        }
        echo "Default equipment inserted.<br>\n";
    }

    // 3. Transactions Table
    $sql_trans = "
    CREATE TABLE IF NOT EXISTS borrow_transactions (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        equipment_name VARCHAR(150) NOT NULL,
        quantity INT UNSIGNED NOT NULL DEFAULT 1,
        borrower_ward VARCHAR(100) NOT NULL,
        lender_ward VARCHAR(100) NOT NULL,
        borrower_name VARCHAR(100) NOT NULL,
        expected_return_date DATE NOT NULL,
        borrow_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
        
        return_date DATETIME DEFAULT NULL,
        receiver_name VARCHAR(100) DEFAULT NULL,
        return_note TEXT DEFAULT NULL,

        INDEX idx_status (status),
        INDEX idx_borrower (borrower_ward),
        INDEX idx_lender (lender_ward)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_trans);
    echo "Table 'borrow_transactions' created successfully.<br>\n";

    echo "<h3>Database setup complete!</h3>";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
