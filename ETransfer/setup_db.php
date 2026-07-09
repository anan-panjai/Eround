<?php
$host = '127.0.0.1';
$port = '3307';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbname = '`E-transfer port 3307`';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created successfully.\n";
    
    $pdo->exec("USE $dbname");
    
    $sql = "
    CREATE TABLE IF NOT EXISTS transfers (
        id                      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

        -- ─── Patient Info ───────────────────────────────────────────
        hn                      VARCHAR(20)   NOT NULL,
        patient_name            VARCHAR(100)  DEFAULT NULL,
        ward                    VARCHAR(50)   DEFAULT NULL,
        destination             VARCHAR(100)  DEFAULT NULL,
        age_group               ENUM('adult','child') DEFAULT 'adult',

        -- ─── Section 1: Vital Signs & Scores ────────────────────────
        rr                      TINYINT UNSIGNED DEFAULT NULL,
        spo2                    TINYINT UNSIGNED DEFAULT NULL,
        airo2                   ENUM('room','oxygen') DEFAULT 'room',
        consciousness           ENUM('alert','voice','pain','unresponsive') DEFAULT 'alert',
        sbp                     SMALLINT UNSIGNED DEFAULT NULL,
        pulse                   SMALLINT UNSIGNED DEFAULT NULL,
        temp                    DECIMAL(4,1)  DEFAULT NULL,
        copd                    TINYINT(1)    DEFAULT 0,
        hr_peds                 SMALLINT UNSIGNED DEFAULT NULL,
        resp_effort             ENUM('normal','mild','moderate','severe') DEFAULT 'normal',
        news_score              TINYINT UNSIGNED DEFAULT 0,
        pews_score              TINYINT UNSIGNED DEFAULT 0,

        -- ─── Section 2: Transport Severity ──────────────────────────
        comm                    ENUM('yes','no') DEFAULT 'yes',
        breath                  ENUM('no_o2','o2') DEFAULT 'no_o2',
        o2_type                 ENUM('cannula','mask_c_bag','hfnc','niv','ventilator') DEFAULT NULL,
        vitals_stable           TINYINT(1)    DEFAULT 1,
        vital_monitor           ENUM('15min','1hr','2-4hr') DEFAULT NULL,
        sedation                ENUM('no','yes') DEFAULT 'no',
        ctas_level              TINYINT UNSIGNED DEFAULT NULL,
        risk_suicide            TINYINT(1)    DEFAULT 0,
        risk_mdrs               TINYINT(1)    DEFAULT 0,
        risk_child              TINYINT(1)    DEFAULT 0,
        risk_other              VARCHAR(200)  DEFAULT NULL,
        severity_level          TINYINT UNSIGNED DEFAULT 1 COMMENT '1=ไม่รุนแรง 2=เล็กน้อย 3=ปานกลาง 4=รุนแรงมาก',

        -- ─── Section 3: Pre-transport Checklist ─────────────────────
        tube_ng                 TINYINT(1)    DEFAULT 0,
        tube_peg                TINYINT(1)    DEFAULT 0,
        tube_icd                TINYINT(1)    DEFAULT 0,
        tube_icd_side           VARCHAR(20)   DEFAULT NULL,
        tube_redivac            TINYINT(1)    DEFAULT 0,
        tube_redivac_location   VARCHAR(100)  DEFAULT NULL,
        tube_pcn                TINYINT(1)    DEFAULT 0,
        tube_pcn_location       VARCHAR(100)  DEFAULT NULL,
        tube_foley              TINYINT(1)    DEFAULT 0,
        tube_other              TINYINT(1)    DEFAULT 0,
        tube_other_detail       VARCHAR(200)  DEFAULT NULL,
        chk_identify            TINYINT(1)    DEFAULT 0,
        chk_abc                 TINYINT(1)    DEFAULT 0,
        eq_emergency_bag        TINYINT(1)    DEFAULT 0,
        eq_infusion             TINYINT(1)    DEFAULT 0,
        eq_ventilator           TINYINT(1)    DEFAULT 0,
        eq_ekg                  TINYINT(1)    DEFAULT 0,
        eq_records              TINYINT(1)    DEFAULT 0,
        pers_doctor             TINYINT(1)    DEFAULT 0,
        pers_doctor_name        VARCHAR(100)  DEFAULT NULL,
        pers_rn                 TINYINT(1)    DEFAULT 0,
        pers_rn_name            VARCHAR(100)  DEFAULT NULL,
        pers_pn                 TINYINT(1)    DEFAULT 0,
        pers_pn_name            VARCHAR(100)  DEFAULT NULL,
        pers_aide               TINYINT(1)    DEFAULT 0,
        pers_aide_name          VARCHAR(100)  DEFAULT NULL,

        -- ─── Section 4: Oxygen Calculator ───────────────────────────
        o2_cylinder_size        VARCHAR(10)   DEFAULT NULL COMMENT 'factor value: 0.28/0.16/2.41/3.14',
        o2_pressure             SMALLINT UNSIGNED DEFAULT NULL,
        o2_safe_residual        SMALLINT UNSIGNED DEFAULT 200,
        o2_flow_rate            DECIMAL(5,2)  DEFAULT NULL,
        o2_time_result          VARCHAR(50)   DEFAULT NULL COMMENT 'calculated duration text',
        hfnc_flow               DECIMAL(5,1)  DEFAULT NULL,
        hfnc_fio2               TINYINT UNSIGNED DEFAULT NULL,
        niv_total_flow          DECIMAL(5,1)  DEFAULT NULL,
        niv_safety_margin       TINYINT(1)    DEFAULT 0,
        vent_mv                 DECIMAL(5,2)  DEFAULT NULL,
        vent_fio2               TINYINT UNSIGNED DEFAULT NULL,
        vent_type               ENUM('turbine','pneumatic') DEFAULT 'turbine',

        -- ─── Section 6: Handoff Information (formerly 5) ────────────
        sec6_enabled            TINYINT(1)    DEFAULT 0,
        sec6_consciousness      VARCHAR(100)  DEFAULT NULL,
        sec6_breathing          VARCHAR(200)  DEFAULT NULL,
        iv_pls                  TINYINT(1)    DEFAULT 0,
        iv_aline                TINYINT(1)    DEFAULT 0,
        iv_cline                TINYINT(1)    DEFAULT 0,
        iv_dlc                  TINYINT(1)    DEFAULT 0,
        iv_perm                 TINYINT(1)    DEFAULT 0,
        iv_other                VARCHAR(100)  DEFAULT NULL,
        sec6_tube               VARCHAR(300)  DEFAULT NULL,
        sec6_ostomy             TINYINT(1)    DEFAULT 0,
        sec6_traction           TINYINT(1)    DEFAULT 0,
        sec6_others             VARCHAR(300)  DEFAULT NULL,
        rights_status           ENUM('has_rights','no_rights') DEFAULT NULL,
        rights_detail           VARCHAR(100)  DEFAULT NULL,
        billing_status          VARCHAR(50)   DEFAULT NULL,
        billing_other           VARCHAR(200)  DEFAULT NULL,
        sec6_leftover_meds      TEXT          DEFAULT NULL,
        sec6_problems           TEXT          DEFAULT NULL,

        -- ─── Section 7: Transfer Log (formerly 6) ───────────────────
        log_time_out            TIME          DEFAULT NULL,
        log_sender              VARCHAR(100)  DEFAULT NULL,
        log_time_in             TIME          DEFAULT NULL,
        log_receiver            VARCHAR(100)  DEFAULT NULL,

        -- ─── Section 8: Destination Reassessment (formerly 7) ───────
        dest_time_in            TIME          DEFAULT NULL,
        dest_receiver           VARCHAR(100)  DEFAULT NULL,
        dest_rr                 TINYINT UNSIGNED DEFAULT NULL,
        dest_spo2               TINYINT UNSIGNED DEFAULT NULL,
        dest_airo2              ENUM('room','oxygen') DEFAULT 'room',
        dest_consciousness      ENUM('alert','voice','pain','unresponsive') DEFAULT 'alert',
        dest_sbp                SMALLINT UNSIGNED DEFAULT NULL,
        dest_pulse              SMALLINT UNSIGNED DEFAULT NULL,
        dest_temp               DECIMAL(4,1)  DEFAULT NULL,
        dest_copd               TINYINT(1)    DEFAULT 0,
        dest_hr_peds            SMALLINT UNSIGNED DEFAULT NULL,
        dest_resp_effort        ENUM('normal','mild','moderate','severe') DEFAULT 'normal',
        dest_news_score         TINYINT UNSIGNED DEFAULT 0,
        dest_pews_score         TINYINT UNSIGNED DEFAULT 0,

        -- ─── Status & Timestamps ──────────────────────────────────────
        is_locked               TINYINT(1)    DEFAULT 0,
        locked_at               DATETIME      DEFAULT NULL,
        is_completed            TINYINT(1)    DEFAULT 0,
        status                  VARCHAR(50)   DEFAULT 'in_progress',
        completed_at            DATETIME      DEFAULT NULL,
        created_at              DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at              DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        -- ─── Indexes ─────────────────────────────────────────────────
        INDEX idx_hn            (hn),
        INDEX idx_ward          (ward),
        INDEX idx_created       (created_at),
        INDEX idx_severity      (severity_level),
        INDEX idx_completed     (is_completed)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql);
    echo "Table 'transfers' created successfully.\n";
    
    $sql_wp = "
    CREATE TABLE IF NOT EXISTS transfer_waypoints (
        id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        transfer_id     INT UNSIGNED NOT NULL,
        seq             INT UNSIGNED NOT NULL DEFAULT 1,

        location_name   VARCHAR(100) NOT NULL COMMENT 'ชื่อหน่วยงาน/สถานที่แวะ',
        time_arrived    TIME         DEFAULT NULL COMMENT 'เวลาถึงจุดแวะ',
        time_departed   TIME         DEFAULT NULL COMMENT 'เวลาออกจากจุดแวะ',

        -- Vital Signs ณ จุดแวะ
        rr              TINYINT UNSIGNED DEFAULT NULL,
        spo2            TINYINT UNSIGNED DEFAULT NULL,
        airo2           ENUM('room','oxygen') DEFAULT 'room',
        consciousness   ENUM('alert','voice','pain','unresponsive') DEFAULT 'alert',
        sbp             SMALLINT UNSIGNED DEFAULT NULL,
        pulse           SMALLINT UNSIGNED DEFAULT NULL,
        temp            DECIMAL(4,1) DEFAULT NULL,
        hr_peds         SMALLINT UNSIGNED DEFAULT NULL,
        resp_effort     ENUM('normal','mild','moderate','severe') DEFAULT 'normal',
        news_score      TINYINT UNSIGNED DEFAULT 0,
        pews_score      TINYINT UNSIGNED DEFAULT 0,
        note            TEXT         DEFAULT NULL,

        created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

        CONSTRAINT fk_wp_transfer FOREIGN KEY (transfer_id)
            REFERENCES transfers(id) ON DELETE CASCADE,
        INDEX idx_wp_transfer (transfer_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_wp);
    echo "Table 'transfer_waypoints' created successfully.\n";

    // Create View
    $sql_view = "
    CREATE OR REPLACE VIEW v_transfer_summary AS
    SELECT 
        id, hn, patient_name, ward, destination, age_group,
        news_score, pews_score, severity_level,
        is_locked, is_completed, status, created_at, updated_at
    FROM transfers;
    ";
    $pdo->exec($sql_view);
    echo "View 'v_transfer_summary' created successfully.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
