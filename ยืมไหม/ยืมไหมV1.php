<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบยืม-คืนอุปกรณ์ระหว่างหอผู้ป่วย</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #047857;
            --bg-color: #f3f4f6;
            --surface: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --danger: #ef4444;
            --warning: #d97706;
            --radius: 8px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); line-height: 1.6; }

        .navbar {
            background-color: var(--primary); color: white; padding: 1rem 2rem;
            box-shadow: var(--shadow); display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 100;
        }
        .navbar h1 { font-size: 1.25rem; font-weight: 600; }

        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }

        .tabs {
            display: flex; gap: 1rem; margin-bottom: 2rem;
            border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        .tab-btn {
            background: none; border: none; padding: 0.5rem 1rem; font-size: 1rem;
            font-weight: 500; color: var(--text-muted); cursor: pointer;
            border-bottom: 3px solid transparent; transition: all 0.2s;
        }
        .tab-btn:hover { color: var(--primary-light); }
        .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }

        .tab-content { display: none; animation: fadeIn 0.3s ease-in-out; }
        .tab-content.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: var(--surface); border-radius: var(--radius);
            padding: 1.5rem; box-shadow: var(--shadow); margin-bottom: 1.5rem;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }

        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-weight: 500; font-size: 0.9rem; color: var(--text-main); }

        .form-control {
            padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px;
            font-size: 1rem; transition: border-color 0.2s, box-shadow 0.2s; background-color: #f9fafb;
        }
        .form-control:focus {
            outline: none; border-color: var(--primary-light); background-color: white;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-size: 1rem;
            font-weight: 500; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-light); }
        .btn-success { background-color: var(--secondary); color: white; }
        .btn-success:hover { background-color: #059669; }
        .btn-warning { background-color: #f59e0b; color: white; }
        .btn-warning:hover { background-color: #d97706; }

        /* Ward Selector Banner */
        .ward-selector-bar {
            background: white; border-radius: var(--radius); padding: 1rem 1.5rem;
            box-shadow: var(--shadow); margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .ward-selector-bar label { font-weight: 600; color: var(--primary); white-space: nowrap; }
        .ward-selector-bar .search-select { flex: 1; min-width: 220px; max-width: 400px; }
        .selected-ward-badge {
            background: #dbeafe; color: var(--primary); padding: 0.35rem 0.9rem;
            border-radius: 9999px; font-size: 0.9rem; font-weight: 600;
            display: none; align-items: center; gap: 0.4rem;
        }
        .selected-ward-badge.visible { display: inline-flex; }

        /* Tables */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.85rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th {
            background-color: #f9fafb; font-weight: 600; color: var(--text-muted);
            font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;
        }
        tr:hover { background-color: #f9fafb; }

        /* Badge */
        .badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 500; }
        .badge-borrowed  { background: #fef3c7; color: #d97706; }   /* กำลังยืม */
        .badge-returning { background: #fee2e2; color: #dc2626; }   /* รอรับคืน */
        .badge-returned  { background: #d1fae5; color: #047857; }   /* คืนแล้ว */

        /* Modals */
        .modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000;
        }
        .modal.active { display: flex; animation: fadeIn 0.2s ease-in-out; }
        .modal-content {
            background: white; padding: 2rem; border-radius: var(--radius);
            width: 100%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        .modal-header { font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; }
        .modal-header.blue  { color: var(--primary); }
        .modal-header.green { color: var(--secondary); }

        /* Items section in borrow form */
        .items-section { margin-bottom: 1.5rem; }
        .items-section-header {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;
        }
        .items-section-header label { font-weight: 600; font-size: 0.95rem; }

        .btn-add-item {
            padding: 0.4rem 0.9rem; font-size: 0.85rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white; border: none; border-radius: 6px; cursor: pointer;
            font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.3rem;
        }
        .btn-add-item:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(30,64,175,0.3); }

        .item-row {
            display: flex; gap: 0.75rem; align-items: flex-end; padding: 0.75rem;
            background: #f9fafb; border: 1px solid var(--border); border-radius: 8px;
            margin-bottom: 0.5rem; animation: fadeIn 0.2s ease-in-out;
        }
        .item-row .form-group { flex: 1; min-width: 0; }
        .item-row .form-group.qty-group { flex: 0 0 80px; }
        .item-row .form-group.cat-group { flex: 1.3; }
        .item-row .form-group.sub-group { flex: 0.9; }
        .item-row .form-group.sub-group.hidden { display: none; }
        .item-row .form-group label { font-size: 0.8rem; color: var(--text-muted); }

        .btn-remove-item {
            padding: 0.5rem 0.6rem; background: #fee2e2; color: var(--danger);
            border: 1px solid #fecaca; border-radius: 6px; cursor: pointer;
            font-size: 1rem; line-height: 1; transition: all 0.2s; flex-shrink: 0; margin-bottom: 0.5rem;
        }
        .btn-remove-item:hover { background: #fecaca; border-color: var(--danger); }

        .item-number {
            display: flex; align-items: center; justify-content: center;
            width: 24px; height: 24px; background: var(--primary); color: white;
            border-radius: 50%; font-size: 0.75rem; font-weight: 600; flex-shrink: 0; margin-bottom: 0.5rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 3rem 1rem; color: var(--text-muted);
        }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .empty-state p { font-size: 0.95rem; }

        /* Searchable Dropdown */
        .search-select { position: relative; }
        .search-select-trigger {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px;
            background: #f9fafb; cursor: pointer; font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s; min-height: 46px;
        }
        .search-select-trigger:hover { border-color: var(--primary-light); }
        .search-select-trigger.active {
            border-color: var(--primary-light); background: white;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        .search-select-trigger .selected-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .search-select-trigger .selected-text.placeholder { color: var(--text-muted); }
        .search-select-trigger .arrow { font-size: 0.6rem; color: var(--text-muted); margin-left: 0.5rem; transition: transform 0.2s; }
        .search-select-trigger.active .arrow { transform: rotate(180deg); }

        .search-select-dropdown {
            display: none;
            position: absolute; top: 100%; left: 0; right: 0; z-index: 200;
            background: white; border: 1px solid var(--border); border-top: none;
            border-radius: 0 0 6px 6px; box-shadow: 0 8px 16px rgba(0,0,0,0.12);
            max-height: 280px; overflow: hidden;
        }
        .search-select-dropdown.open { display: block !important; animation: fadeIn 0.15s ease-out; }

        .search-select-search {
            padding: 0.5rem 0.75rem; border-bottom: 1px solid var(--border);
            position: sticky; top: 0; background: white;
        }
        .search-select-search input {
            width: 100%; padding: 0.5rem 0.6rem; border: 1px solid var(--border);
            border-radius: 4px; font-size: 0.9rem; outline: none;
        }
        .search-select-search input:focus { border-color: var(--primary-light); }
        .search-select-list { max-height: 220px; overflow-y: auto; }
        .search-select-group-label {
            padding: 0.4rem 0.75rem; font-size: 0.75rem; font-weight: 600;
            color: var(--primary); background: #eff6ff; text-transform: uppercase;
            letter-spacing: 0.03em; position: sticky; top: 0;
        }
        .search-select-option {
            padding: 0.5rem 0.75rem 0.5rem 1.2rem; cursor: pointer; font-size: 0.9rem; transition: background 0.1s;
        }
        .search-select-option:hover { background: #eff6ff; }
        .search-select-option.selected { background: #dbeafe; font-weight: 500; color: var(--primary); }
        .search-select-empty { padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.9rem; }

        /* Info box */
        .info-box {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;
            padding: 0.75rem 1rem; margin-bottom: 1.5rem; font-size: 0.9rem; color: #1e40af;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }

        /* Return item info */
        .return-item-info {
            background: #f9fafb; border: 1px solid var(--border); border-radius: 8px;
            padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.9rem;
        }
        .return-item-info .label { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.2rem; }
        .return-item-info .value { font-weight: 600; color: var(--text-main); }
    </style>
</head>
<body>

<div class="navbar">
    <h1>🏥 ระบบยืม-คืนอุปกรณ์ระหว่างหอผู้ป่วย</h1>
</div>

<div class="container">

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active"  onclick="switchTab('borrow', this)">📋 ยืมอุปกรณ์</button>
        <button class="tab-btn"         onclick="switchTab('my-borrowed', this)">📤 ของที่เราให้ยืมออกไป</button>
        <button class="tab-btn"         onclick="switchTab('we-borrowing', this)">📥 ของที่เรายืมมา</button>
        <button class="tab-btn"         onclick="switchTab('history', this)">🗂 ประวัติการคืน</button>
    </div>

    <!-- ===== TAB 1: แบบฟอร์มยืม ===== -->
    <div id="borrow" class="tab-content active card">
        <h2 style="margin-bottom:1.5rem; color:var(--primary);">แบบฟอร์มยืมอุปกรณ์</h2>
        <form id="borrowForm" onsubmit="submitBorrow(event)">
            <div class="items-section">
                <div class="items-section-header">
                    <label>📦 รายการอุปกรณ์ที่ต้องการยืม</label>
                    <button type="button" class="btn-add-item" onclick="addItem()">＋ เพิ่มรายการ</button>
                </div>
                <div id="items-container"></div>
            </div>

            <hr style="border:none; border-top:1px solid var(--border); margin-bottom:1.5rem;">

            <div class="form-grid">
                <div class="form-group">
                    <label>หอผู้ป่วยที่ต้องการยืม (เรา)</label>
                    <div class="search-select" id="ss-borrower-ward" data-target="b_borrower_ward">
                        <input type="hidden" id="b_borrower_ward" required>
                        <div class="search-select-trigger" onclick="toggleSearchSelect('ss-borrower-ward')">
                            <span class="selected-text placeholder">-- เลือกหอผู้ป่วย --</span>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="search-select-dropdown">
                            <div class="search-select-search">
                                <input type="text" placeholder="🔍 พิมพ์ชื่อหอผู้ป่วย..." oninput="filterWardOptions('ss-borrower-ward', this.value)">
                            </div>
                            <div class="search-select-list"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>หอผู้ป่วยที่ให้ยืม (ต้นทาง)</label>
                    <div class="search-select" id="ss-lender-ward" data-target="b_lender_ward">
                        <input type="hidden" id="b_lender_ward" required>
                        <div class="search-select-trigger" onclick="toggleSearchSelect('ss-lender-ward')">
                            <span class="selected-text placeholder">-- เลือกหอผู้ป่วย --</span>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="search-select-dropdown">
                            <div class="search-select-search">
                                <input type="text" placeholder="🔍 พิมพ์ชื่อหอผู้ป่วย..." oninput="filterWardOptions('ss-lender-ward', this.value)">
                            </div>
                            <div class="search-select-list"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>ชื่อผู้ยืม (เจ้าหน้าที่)</label>
                    <input type="text" id="b_borrower_name" class="form-control" placeholder="ชื่อ-สกุล" required>
                </div>
                <div class="form-group">
                    <label>กำหนดวันคืน</label>
                    <input type="date" id="b_expected_return" class="form-control" required>
                </div>
            </div>
            <div style="margin-top:1.5rem; display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary">💾 บันทึกการยืม</button>
            </div>
        </form>
    </div>

    <!-- ===== TAB 2: ของที่เราให้ยืมออกไป (หอเจ้าของ กด "รับของคืน") ===== -->
    <div id="my-borrowed" class="tab-content">
        <div class="ward-selector-bar">
            <label>🏥 เลือกหอผู้ป่วยของเรา :</label>
            <div class="search-select" id="ss-lender-view" data-target="lender_view_ward">
                <input type="hidden" id="lender_view_ward">
                <div class="search-select-trigger" onclick="toggleSearchSelect('ss-lender-view')">
                    <span class="selected-text placeholder">-- เลือกหอผู้ป่วย --</span>
                    <span class="arrow">▼</span>
                </div>
                <div class="search-select-dropdown">
                    <div class="search-select-search">
                        <input type="text" placeholder="🔍 พิมพ์ชื่อหอผู้ป่วย..." oninput="filterWardOptions('ss-lender-view', this.value)">
                    </div>
                    <div class="search-select-list"></div>
                </div>
            </div>
        </div>

        <div id="my-borrowed-content">
            <div class="empty-state">
                <div class="icon">🏥</div>
                <p>เลือกหอผู้ป่วยของเราก่อน เพื่อดูรายการที่ให้ยืมออกไป</p>
            </div>
        </div>
    </div>

    <!-- ===== TAB 3: ของที่เรายืมมา (หอผู้ยืม กด "ส่งคืน") ===== -->
    <div id="we-borrowing" class="tab-content">
        <div class="ward-selector-bar">
            <label>🏥 เลือกหอผู้ป่วยของเรา :</label>
            <div class="search-select" id="ss-borrower-view" data-target="borrower_view_ward">
                <input type="hidden" id="borrower_view_ward">
                <div class="search-select-trigger" onclick="toggleSearchSelect('ss-borrower-view')">
                    <span class="selected-text placeholder">-- เลือกหอผู้ป่วย --</span>
                    <span class="arrow">▼</span>
                </div>
                <div class="search-select-dropdown">
                    <div class="search-select-search">
                        <input type="text" placeholder="🔍 พิมพ์ชื่อหอผู้ป่วย..." oninput="filterWardOptions('ss-borrower-view', this.value)">
                    </div>
                    <div class="search-select-list"></div>
                </div>
            </div>
        </div>

        <div id="we-borrowing-content">
            <div class="empty-state">
                <div class="icon">🏥</div>
                <p>เลือกหอผู้ป่วยของเราก่อน เพื่อดูรายการที่ยืมมา</p>
            </div>
        </div>
    </div>

    <!-- ===== TAB 4: ประวัติการคืน ===== -->
    <div id="history" class="tab-content card">
        <h2 style="margin-bottom:1rem; color:var(--primary);">🗂 ประวัติการคืนอุปกรณ์</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>อุปกรณ์</th>
                        <th>หอผู้ยืม</th>
                        <th>หอผู้ให้ยืม</th>
                        <th>ผู้รับคืน</th>
                        <th>วันที่คืน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody id="history-list">
                    <tr><td colspan="6" style="text-align:center; color:gray;">กำลังโหลดข้อมูล...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ===== Modal: ส่งคืน (หอผู้ยืม) ===== -->
<div id="sendReturnModal" class="modal">
    <div class="modal-content">
        <div class="modal-header blue">📤 แจ้งส่งคืนอุปกรณ์</div>
        <div class="info-box">ℹ️ การแจ้งส่งคืนจะเปลี่ยนสถานะเป็น "รอรับคืน" หอที่ให้ยืมจะต้องกด "รับของคืน" เพื่อยืนยัน</div>
        <div id="sendReturnItemInfo" class="return-item-info"></div>
        <form id="sendReturnForm" onsubmit="submitSendReturn(event)">
            <input type="hidden" id="sr_transaction_id">
            <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:0.5rem;">
                <button type="button" class="btn" onclick="closeModal('sendReturnModal')" style="background:#e5e7eb;">ยกเลิก</button>
                <button type="submit" class="btn btn-warning">📤 แจ้งส่งคืน</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== Modal: รับของคืน (หอที่ให้ยืม) ===== -->
<div id="receiveReturnModal" class="modal">
    <div class="modal-content">
        <div class="modal-header green">✅ รับของคืน</div>
        <div id="receiveReturnItemInfo" class="return-item-info"></div>
        <form id="receiveReturnForm" onsubmit="submitReceiveReturn(event)">
            <input type="hidden" id="rr_transaction_id">
            <div class="form-group" style="margin-bottom:1rem;">
                <label>ชื่อผู้รับคืน (เจ้าหน้าที่)</label>
                <input type="text" id="rr_receiver_name" class="form-control" placeholder="ชื่อ-สกุล" required>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>หมายเหตุ (ถ้ามี)</label>
                <input type="text" id="rr_note" class="form-control" placeholder="เช่น ครบถ้วน / อุปกรณ์ชำรุด">
            </div>
            <div style="display:flex; gap:1rem; justify-content:flex-end;">
                <button type="button" class="btn" onclick="closeModal('receiveReturnModal')" style="background:#e5e7eb;">ยกเลิก</button>
                <button type="submit" class="btn btn-success">✅ ยืนยันรับของคืน</button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// ข้อมูลอุปกรณ์
// ============================================================
const equipmentData = {
    'น้ำเกลือ (IV Fluid)': {
        sub1: {
            label: 'ชนิด',
            options: ['NSS (0.9% NaCl)', '5% Dextrose in Water (5%DW)', '5% Dextrose in NSS (5%DNS)',
                      '5% Dextrose in 1/2 NSS', '5% Dextrose in 1/3 NSS', '10% Dextrose in Water (10%DW)',
                      "Lactated Ringer's (LRS)", 'Acetar', 'Sterile Water']
        },
        sub2: {
            label: 'ขนาด',
            dependsOnSub1: {
                'NSS (0.9% NaCl)': ['50 mL','100 mL','250 mL','500 mL','1000 mL'],
                '5% Dextrose in Water (5%DW)': ['100 mL','250 mL','500 mL','1000 mL'],
                '5% Dextrose in NSS (5%DNS)': ['500 mL','1000 mL'],
                '5% Dextrose in 1/2 NSS': ['500 mL','1000 mL'],
                '5% Dextrose in 1/3 NSS': ['500 mL','1000 mL'],
                '10% Dextrose in Water (10%DW)': ['500 mL'],
                "Lactated Ringer's (LRS)": ['500 mL','1000 mL'],
                'Acetar': ['500 mL','1000 mL'],
                'Sterile Water': ['100 mL','500 mL','1000 mL']
            }
        }
    },
    'Infusion Pump': { sub1: { label: 'ยี่ห้อ', options: ['FN','Terumo'] }, sub2: null },
    'Mobile Ventilator Carina': { sub1: null, sub2: null },
    'จุก ICD': { sub1: { label: 'ชนิด', options: ['ปากแคบ','ปากกว้าง'] }, sub2: null },
    'ขวด ICD': { sub1: { label: 'ชนิด', options: ['ปากแคบ','ปากกว้าง'] }, sub2: null },
    'สาย NG': { sub1: { label: 'ขนาด', options: ['8 Fr','10 Fr','12 Fr','14 Fr','16 Fr','18 Fr'] }, sub2: null },
    'สาย Foley': {
        sub1: { label: 'ขนาด', options: ['12 Fr','14 Fr','16 Fr','18 Fr','20 Fr','22 Fr','24 Fr'] },
        sub2: { label: 'จำนวนหาง', options: ['2 หาง','3 หาง'] }
    }
};

let categoryOptionsHTML = '<option value="">-- เลือกอุปกรณ์ --</option>';
Object.keys(equipmentData).forEach(cat => {
    categoryOptionsHTML += `<option value="${cat}">${cat}</option>`;
});

// ============================================================
// ข้อมูลหอผู้ป่วย
// ============================================================
const wardData = {
    'กุมารเวชกรรม': [
        'หอผู้ป่วยกุมารเวชกรรม 1','หอผู้ป่วยกุมารเวชกรรม 2','หอผู้ป่วยกุมารเวชกรรม 3',
        'หอผู้ป่วยกุมารเวชกรรม 4','หอผู้ป่วยกุมารเวชกรรม 5',
        'หอผู้ป่วยหนักกุมารเวชกรรม (PICU)','หอผู้ป่วยหนักกุมารเวชกรรมโรคหัวใจ',
        'หอผู้ป่วยทารกแรกเกิด 2','หอผู้ป่วยวิกฤตทารกแรกเกิด (NICU)',
        'หอผู้ป่วยหนักทารกแรกเกิด 1','หอผู้ป่วยหนักทารกแรกเกิด 2'
    ],
    'อายุรศาสตร์': [
        'หอผู้ป่วยอายุรกรรมชาย 1','หอผู้ป่วยอายุรกรรมชาย 2','หอผู้ป่วยอายุรกรรมชาย 3',
        'หอผู้ป่วยอายุรกรรมหญิง 1','หอผู้ป่วยอายุรกรรมหญิง 2','หอผู้ป่วยอายุรกรรมหญิง 3',
        'หอผู้ป่วยวิกฤตอายุรกรรม 1 (MICU1)','หอผู้ป่วยวิกฤตอายุรกรรม 2 (MICU2)',
        'หอผู้ป่วยหนักอายุรกรรม','หอผู้ป่วยวิกฤติโรคหลอดเลือดสมอง (Stroke Unit)',
        'หอผู้ป่วยหนักโรคหัวใจและหลอดเลือด 1 (CCU1)','หอผู้ป่วยหนักโรคหัวใจและหลอดเลือด 2 (CCU2)',
        'หอผู้ป่วยโรคปอด','หอผู้ป่วยเคมีบำบัด',
        'หอผู้ป่วยปลูกถ่ายไขกระดูกและเคมีบำบัดขนาดสูง','หน่วยผู้ป่วยพึ่งพาเครื่องช่วยหายใจ'
    ],
    'ศัลยศาสตร์': [
        'หอผู้ป่วยศัลยกรรมชาย 1','หอผู้ป่วยศัลยกรรมชาย 2','หอผู้ป่วยศัลยกรรมชาย 3',
        'หอผู้ป่วยศัลยกรรมหญิง 1','หอผู้ป่วยศัลยกรรมหญิง 2','หอผู้ป่วยศัลยกรรมหญิง 3',
        'หอผู้ป่วยวิกฤตศัลยกรรมอุบัติเหตุ (Trauma ICU)','หอผู้ป่วยวิกฤตศัลยกรรมประสาท (Neuro ICU)',
        'หอผู้ป่วยศัลยกรรมประสาท','หอผู้ป่วยศัลยกรรมทรวงอก หัวใจ และหลอดเลือด (CVT Ward)',
        'หอผู้ป่วยวิกฤตศัลยกรรมทรวงอก หัวใจ (CVT ICU)','หอผู้ป่วยไฟไหม้ น้ำร้อนลวก (Burn Unit)',
        'หอผู้ป่วยวิกฤตศัลยกรรมฉุกเฉิน','หอผู้ป่วยหนักศัลยกรรมทั่วไป (SICU)'
    ],
    'สูติ-นรีเวช': ['หอผู้ป่วยสูติกรรม-นรีเวชกรรม','ห้องคลอด','หอผู้ป่วยหลังคลอด'],
    'ออร์โธปิดิกส์': ['หอผู้ป่วยออร์โธปิดิกส์'],
    'จักษุ / โสต ศอ นาสิก / จิตเวช': [
        'หอผู้ป่วยจักษุ 1','หอผู้ป่วยจักษุ 2',
        'หอผู้ป่วยโสต 1','หอผู้ป่วยโสต 2','หอผู้ป่วยจิตเวชผู้ใหญ่'
    ],
    'อื่นๆ': [
        'หอผู้ป่วยสงฆ์อาพาธ 1','หอผู้ป่วยสงฆ์อาพาธ 2','หอผู้ป่วยฟื้นฟูสภาพ',
        'งานอุบัติเหตุและฉุกเฉิน (ER)','ห้องผ่าตัด'
    ],
    'หอผู้ป่วยพิเศษ': [
        'หอผู้ป่วยพิเศษสุจิณโณ ชั้น 3','หอผู้ป่วยพิเศษสุจิณโณ ชั้น 12',
        'หอผู้ป่วยพิเศษสุจิณโณ ชั้น 13','หอผู้ป่วยพิเศษสุจิณโณ ชั้น 14',
        'หอผู้ป่วยพิเศษบุญสม ชั้น 7','หอผู้ป่วยพิเศษบุญสม ชั้น 8'
    ]
};

// ============================================================
// Tab switching
// ============================================================
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    btn.classList.add('active');
    if (tabId === 'history') loadHistory();
}

// ============================================================
// Borrow form — item rows
// ============================================================
let itemCounter = 0;
document.getElementById('b_expected_return').valueAsDate = new Date();

function addItem() {
    itemCounter++;
    const container = document.getElementById('items-container');
    const row = document.createElement('div');
    row.className = 'item-row';
    row.id = `item-row-${itemCounter}`;
    row.innerHTML = `
        <span class="item-number">${container.children.length + 1}</span>
        <div class="form-group cat-group">
            <label>อุปกรณ์</label>
            <select class="form-control item-category" onchange="onCategoryChange(this)" required>${categoryOptionsHTML}</select>
        </div>
        <div class="form-group sub-group hidden" data-sub="1">
            <label class="sub1-label">ตัวเลือก 1</label>
            <select class="form-control item-sub1" onchange="onSub1Change(this)" disabled>
                <option value="">--</option>
            </select>
        </div>
        <div class="form-group sub-group hidden" data-sub="2">
            <label class="sub2-label">ตัวเลือก 2</label>
            <select class="form-control item-sub2" disabled>
                <option value="">--</option>
            </select>
        </div>
        <div class="form-group qty-group">
            <label>จำนวน</label>
            <input type="number" class="form-control item-quantity" value="1" min="1" required>
        </div>
        <button type="button" class="btn-remove-item" onclick="removeItem('item-row-${itemCounter}')" title="ลบรายการ">✕</button>
    `;
    container.appendChild(row);
    updateItemNumbers();
}

function onCategoryChange(selectEl) {
    const row = selectEl.closest('.item-row');
    const sub1Wrap = row.querySelector('[data-sub="1"]');
    const sub2Wrap = row.querySelector('[data-sub="2"]');
    const sub1Select = row.querySelector('.item-sub1');
    const sub2Select = row.querySelector('.item-sub2');
    const config = equipmentData[selectEl.value];

    sub1Wrap.classList.add('hidden'); sub2Wrap.classList.add('hidden');
    sub1Select.disabled = true; sub2Select.disabled = true;
    sub1Select.innerHTML = '<option value="">--</option>';
    sub2Select.innerHTML = '<option value="">--</option>';
    sub1Select.removeAttribute('required'); sub2Select.removeAttribute('required');

    if (!config) return;

    if (config.sub1) {
        row.querySelector('.sub1-label').textContent = config.sub1.label;
        sub1Select.innerHTML = `<option value="">-- เลือก${config.sub1.label} --</option>` +
            config.sub1.options.map(o => `<option value="${o}">${o}</option>`).join('');
        sub1Select.disabled = false; sub1Select.setAttribute('required', '');
        sub1Wrap.classList.remove('hidden');
    }

    if (config.sub2 && config.sub2.options) {
        row.querySelector('.sub2-label').textContent = config.sub2.label;
        sub2Select.innerHTML = `<option value="">-- เลือก${config.sub2.label} --</option>` +
            config.sub2.options.map(o => `<option value="${o}">${o}</option>`).join('');
        sub2Select.disabled = false; sub2Select.setAttribute('required', '');
        sub2Wrap.classList.remove('hidden');
    }

    if (config.sub2 && config.sub2.dependsOnSub1) {
        row.querySelector('.sub2-label').textContent = config.sub2.label;
        sub2Select.innerHTML = `<option value="">-- เลือก${config.sub2.label} --</option>`;
        sub2Select.setAttribute('required', '');
        sub2Wrap.classList.remove('hidden');
    }
}

function onSub1Change(selectEl) {
    const row = selectEl.closest('.item-row');
    const cat = row.querySelector('.item-category').value;
    const config = equipmentData[cat];
    const sub2Select = row.querySelector('.item-sub2');
    if (!config || !config.sub2 || !config.sub2.dependsOnSub1) return;
    const sizes = config.sub2.dependsOnSub1[selectEl.value];
    if (sizes && sizes.length > 0) {
        sub2Select.innerHTML = `<option value="">-- เลือก${config.sub2.label} --</option>` +
            sizes.map(s => `<option value="${s}">${s}</option>`).join('');
        sub2Select.disabled = false;
    } else {
        sub2Select.innerHTML = `<option value="">-- เลือก${config.sub2.label} --</option>`;
        sub2Select.disabled = true;
    }
}

function removeItem(rowId) {
    if (document.getElementById('items-container').children.length <= 1) {
        alert('ต้องมีอย่างน้อย 1 รายการ'); return;
    }
    document.getElementById(rowId).remove();
    updateItemNumbers();
}

function updateItemNumbers() {
    document.querySelectorAll('#items-container .item-row').forEach((row, idx) => {
        const num = row.querySelector('.item-number');
        if (num) num.textContent = idx + 1;
    });
}

// ============================================================
// Ward searchable dropdown
// ============================================================
function buildWardList(containerId, filter = '') {
    const container = document.querySelector(`#${containerId} .search-select-list`);
    let html = '';
    const lf = filter.toLowerCase();
    let hasResults = false;
    for (const [group, wards] of Object.entries(wardData)) {
        const filtered = wards.filter(w => w.toLowerCase().includes(lf) || group.toLowerCase().includes(lf));
        if (filtered.length > 0) {
            hasResults = true;
            html += `<div class="search-select-group-label">${group}</div>`;
            filtered.forEach(w => {
                html += `<div class="search-select-option" data-value="${w}" data-container="${containerId}">${w}</div>`;
            });
        }
    }
    container.innerHTML = hasResults ? html : '<div class="search-select-empty">ไม่พบหอผู้ป่วยที่ค้นหา</div>';
}

function toggleSearchSelect(id) {
    const el = document.getElementById(id);
    const dropdown = el.querySelector('.search-select-dropdown');
    const trigger = el.querySelector('.search-select-trigger');
    const isOpen = dropdown.classList.contains('open');

    document.querySelectorAll('.search-select-dropdown.open').forEach(d => {
        d.classList.remove('open');
        d.closest('.search-select').querySelector('.search-select-trigger').classList.remove('active');
    });

    if (!isOpen) {
        dropdown.classList.add('open'); trigger.classList.add('active');
        buildWardList(id);
        const inp = el.querySelector('.search-select-search input');
        inp.value = '';
        setTimeout(() => inp.focus(), 50);
    }
}

function filterWardOptions(id, value) { buildWardList(id, value); }

function selectWard(containerId, value, callback) {
    const el = document.getElementById(containerId);
    const target = el.dataset.target;
    if (target) document.getElementById(target).value = value;
    el.querySelector('.selected-text').textContent = value;
    el.querySelector('.selected-text').classList.remove('placeholder');
    el.querySelector('.search-select-dropdown').classList.remove('open');
    el.querySelector('.search-select-trigger').classList.remove('active');
    el.querySelectorAll('.search-select-option').forEach(opt => {
        opt.classList.toggle('selected', opt.dataset.value === value);
    });
    if (callback) callback(value);
}

document.addEventListener('click', function(e) {
    const optionEl = e.target.closest('.search-select-option');
    if (optionEl) {
        const cid = optionEl.dataset.container;
        const val = optionEl.dataset.value;
        if (!cid || !val) return;

        // ตรวจว่าเป็น dropdown ของ tab ไหน
        if (cid === 'ss-lender-view') {
            selectWard(cid, val, (v) => loadMyBorrowed(v));
        } else if (cid === 'ss-borrower-view') {
            selectWard(cid, val, (v) => loadWeBorrowing(v));
        } else {
            selectWard(cid, val);
        }
        return;
    }
    if (e.target.closest('.search-select-dropdown') || e.target.closest('.search-select-trigger')) return;
    document.querySelectorAll('.search-select-dropdown.open').forEach(d => {
        d.classList.remove('open');
        d.closest('.search-select').querySelector('.search-select-trigger').classList.remove('active');
    });
});

// ============================================================
// โหลดข้อมูล: ของที่เราให้ยืมออกไป (Tab 2)
// ============================================================
async function loadMyBorrowed(ward) {
    const content = document.getElementById('my-borrowed-content');
    content.innerHTML = '<div class="card" style="text-align:center; color:gray; padding:2rem;">กำลังโหลด...</div>';
    try {
        const res = await fetch(`api.php?action=get_lender_transactions&ward=${encodeURIComponent(ward)}`);
        const data = await res.json();
        if (!data.success) { content.innerHTML = `<div class="card">${data.message}</div>`; return; }

        const rows = data.data;
        if (rows.length === 0) {
            content.innerHTML = `<div class="card"><div class="empty-state"><div class="icon">✅</div><p>ไม่มีรายการที่ค้างอยู่ในขณะนี้</p></div></div>`;
            return;
        }

        // แบ่งกลุ่มตามสถานะ
        const returning = rows.filter(r => r.status === 'returning');
        const borrowed  = rows.filter(r => r.status === 'borrowed');

        let html = '';

        if (returning.length > 0) {
            html += `<div class="card">
                <h3 style="color:#dc2626; margin-bottom:1rem;">🔴 รอรับของคืน (${returning.length} รายการ)</h3>
                <div class="info-box">ℹ️ รายการเหล่านี้หอที่ยืมแจ้งส่งคืนแล้ว กรุณากด "รับของคืน" เมื่อได้รับของ</div>
                <div class="table-responsive"><table>
                    <thead><tr><th>อุปกรณ์</th><th>จำนวน</th><th>หอที่ยืม</th><th>ผู้ยืม</th><th>กำหนดคืน</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                    <tbody>`;
            returning.forEach(row => {
                html += `<tr>
                    <td><strong>${row.equipment_name}</strong></td>
                    <td>${row.quantity}</td>
                    <td>${row.borrower_ward}</td>
                    <td><small style="color:gray;">${row.borrower_name}</small></td>
                    <td><span style="color:#b45309; font-weight:500;">${row.expected_return_date}</span></td>
                    <td><span class="badge badge-returning">🔴 รอรับคืน</span></td>
                    <td><button onclick="openReceiveReturnModal(${row.id}, '${escHtml(row.equipment_name)}', ${row.quantity}, '${escHtml(row.borrower_ward)}')"
                        class="btn btn-success" style="padding:0.4rem 0.8rem; font-size:0.85rem;">✅ รับของคืน</button></td>
                </tr>`;
            });
            html += `</tbody></table></div></div>`;
        }

        if (borrowed.length > 0) {
            html += `<div class="card">
                <h3 style="color:#d97706; margin-bottom:1rem;">🟡 ยังไม่ได้คืน (${borrowed.length} รายการ)</h3>
                <div class="table-responsive"><table>
                    <thead><tr><th>อุปกรณ์</th><th>จำนวน</th><th>หอที่ยืม</th><th>ผู้ยืม</th><th>กำหนดคืน</th><th>สถานะ</th></tr></thead>
                    <tbody>`;
            borrowed.forEach(row => {
                const overdue = new Date(row.expected_return_date) < new Date();
                html += `<tr>
                    <td><strong>${row.equipment_name}</strong></td>
                    <td>${row.quantity}</td>
                    <td>${row.borrower_ward}</td>
                    <td><small style="color:gray;">${row.borrower_name}</small></td>
                    <td><span style="color:${overdue ? '#dc2626' : '#b45309'}; font-weight:500;">${row.expected_return_date}${overdue ? ' ⚠️' : ''}</span></td>
                    <td><span class="badge badge-borrowed">🟡 กำลังยืม</span></td>
                </tr>`;
            });
            html += `</tbody></table></div></div>`;
        }

        content.innerHTML = html;
    } catch (err) {
        console.error(err);
        content.innerHTML = '<div class="card" style="color:red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
    }
}

// ============================================================
// โหลดข้อมูล: ของที่เรายืมมา (Tab 3)
// ============================================================
async function loadWeBorrowing(ward) {
    const content = document.getElementById('we-borrowing-content');
    content.innerHTML = '<div class="card" style="text-align:center; color:gray; padding:2rem;">กำลังโหลด...</div>';
    try {
        const res = await fetch(`api.php?action=get_borrower_transactions&ward=${encodeURIComponent(ward)}`);
        const data = await res.json();
        if (!data.success) { content.innerHTML = `<div class="card">${data.message}</div>`; return; }

        const rows = data.data;
        if (rows.length === 0) {
            content.innerHTML = `<div class="card"><div class="empty-state"><div class="icon">✅</div><p>ไม่มีรายการที่กำลังยืมอยู่</p></div></div>`;
            return;
        }

        const borrowed  = rows.filter(r => r.status === 'borrowed');
        const returning = rows.filter(r => r.status === 'returning');

        let html = '';

        if (borrowed.length > 0) {
            html += `<div class="card">
                <h3 style="color:#d97706; margin-bottom:1rem;">🟡 กำลังยืมอยู่ (${borrowed.length} รายการ) — กด "ส่งคืน" เมื่อนำของไปคืนแล้ว</h3>
                <div class="table-responsive"><table>
                    <thead><tr><th>อุปกรณ์</th><th>จำนวน</th><th>ยืมจากหอ</th><th>กำหนดคืน</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                    <tbody>`;
            borrowed.forEach(row => {
                const overdue = new Date(row.expected_return_date) < new Date();
                html += `<tr>
                    <td><strong>${row.equipment_name}</strong></td>
                    <td>${row.quantity}</td>
                    <td>${row.lender_ward}</td>
                    <td><span style="color:${overdue ? '#dc2626' : '#b45309'}; font-weight:500;">${row.expected_return_date}${overdue ? ' ⚠️' : ''}</span></td>
                    <td><span class="badge badge-borrowed">🟡 กำลังยืม</span></td>
                    <td><button onclick="openSendReturnModal(${row.id}, '${escHtml(row.equipment_name)}', ${row.quantity}, '${escHtml(row.lender_ward)}')"
                        class="btn btn-warning" style="padding:0.4rem 0.8rem; font-size:0.85rem;">📤 แจ้งส่งคืน</button></td>
                </tr>`;
            });
            html += `</tbody></table></div></div>`;
        }

        if (returning.length > 0) {
            html += `<div class="card">
                <h3 style="color:#2563eb; margin-bottom:1rem;">🔵 รอการยืนยันรับคืน (${returning.length} รายการ)</h3>
                <div class="info-box">ℹ️ รายการเหล่านี้ส่งคืนแล้ว รอให้หอที่ให้ยืมกด "รับของคืน" ยืนยัน</div>
                <div class="table-responsive"><table>
                    <thead><tr><th>อุปกรณ์</th><th>จำนวน</th><th>ยืมจากหอ</th><th>กำหนดคืน</th><th>สถานะ</th></tr></thead>
                    <tbody>`;
            returning.forEach(row => {
                html += `<tr>
                    <td><strong>${row.equipment_name}</strong></td>
                    <td>${row.quantity}</td>
                    <td>${row.lender_ward}</td>
                    <td><span style="color:#b45309; font-weight:500;">${row.expected_return_date}</span></td>
                    <td><span class="badge badge-returning">🔵 รอการยืนยัน</span></td>
                </tr>`;
            });
            html += `</tbody></table></div></div>`;
        }

        content.innerHTML = html;
    } catch (err) {
        console.error(err);
        content.innerHTML = '<div class="card" style="color:red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
    }
}

// ============================================================
// โหลดประวัติการคืน (Tab 4)
// ============================================================
async function loadHistory() {
    const tbody = document.getElementById('history-list');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:gray;">กำลังโหลด...</td></tr>';
    try {
        const res = await fetch('api.php?action=get_transactions&status=returned');
        const data = await res.json();
        if (!data.success || data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:gray;">ไม่มีประวัติการคืน</td></tr>';
            return;
        }
        tbody.innerHTML = data.data.map(row => `
            <tr>
                <td><strong>${row.equipment_name}</strong> <small style="color:gray;">x${row.quantity}</small></td>
                <td>${row.borrower_ward}</td>
                <td>${row.lender_ward}</td>
                <td>${row.receiver_name || '-'}</td>
                <td>${row.return_date}</td>
                <td><span class="badge badge-returned">✅ คืนแล้ว</span></td>
            </tr>`).join('');
    } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">เกิดข้อผิดพลาด</td></tr>';
    }
}

// ============================================================
// Modal: แจ้งส่งคืน (หอที่ยืม)
// ============================================================
function openSendReturnModal(id, name, qty, lenderWard) {
    document.getElementById('sr_transaction_id').value = id;
    document.getElementById('sendReturnItemInfo').innerHTML = `
        <div class="label">อุปกรณ์ที่ส่งคืน</div>
        <div class="value">${name} (จำนวน ${qty})</div>
        <div class="label" style="margin-top:0.5rem;">คืนไปที่หอ</div>
        <div class="value">${lenderWard}</div>`;
    document.getElementById('sendReturnModal').classList.add('active');
}

async function submitSendReturn(e) {
    e.preventDefault();
    const payload = { transaction_id: document.getElementById('sr_transaction_id').value };
    try {
        const res = await fetch('api.php?action=send_return', {
            method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.success) {
            closeModal('sendReturnModal');
            alert('✅ แจ้งส่งคืนสำเร็จ รอหอที่ให้ยืมกด "รับของคืน" ยืนยัน');
            // reload tab ปัจจุบัน
            const ward = document.getElementById('borrower_view_ward').value;
            if (ward) loadWeBorrowing(ward);
        } else { alert(result.message); }
    } catch (err) { console.error(err); alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'); }
}

// ============================================================
// Modal: รับของคืน (หอที่ให้ยืม)
// ============================================================
function openReceiveReturnModal(id, name, qty, borrowerWard) {
    document.getElementById('rr_transaction_id').value = id;
    document.getElementById('rr_receiver_name').value = '';
    document.getElementById('rr_note').value = '';
    document.getElementById('receiveReturnItemInfo').innerHTML = `
        <div class="label">อุปกรณ์ที่รับคืน</div>
        <div class="value">${name} (จำนวน ${qty})</div>
        <div class="label" style="margin-top:0.5rem;">รับคืนจากหอ</div>
        <div class="value">${borrowerWard}</div>`;
    document.getElementById('receiveReturnModal').classList.add('active');
}

async function submitReceiveReturn(e) {
    e.preventDefault();
    const payload = {
        transaction_id: document.getElementById('rr_transaction_id').value,
        receiver_name: document.getElementById('rr_receiver_name').value,
        return_note: document.getElementById('rr_note').value
    };
    try {
        const res = await fetch('api.php?action=receive_return', {
            method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.success) {
            closeModal('receiveReturnModal');
            alert('✅ บันทึกการรับคืนสำเร็จ');
            const ward = document.getElementById('lender_view_ward').value;
            if (ward) loadMyBorrowed(ward);
        } else { alert(result.message); }
    } catch (err) { console.error(err); alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'); }
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// ============================================================
// Submit borrow form
// ============================================================
async function submitBorrow(e) {
    e.preventDefault();
    const rows = document.querySelectorAll('#items-container .item-row');
    const items = [];
    let valid = true;
    rows.forEach(row => {
        const cat  = row.querySelector('.item-category').value;
        const sub1 = row.querySelector('.item-sub1').value;
        const sub2 = row.querySelector('.item-sub2').value;
        const qty  = row.querySelector('.item-quantity').value;
        const config = equipmentData[cat];
        if (!cat) { valid = false; return; }
        if (config && config.sub1 && !sub1) { valid = false; return; }
        if (config && config.sub2 && !sub2) { valid = false; return; }
        let name = cat;
        if (sub1) name += ` ${sub1}`;
        if (sub2) name += ` ${sub2}`;
        items.push({ equipment_name: name, quantity: parseInt(qty) || 1 });
    });

    if (!valid || items.length === 0) { alert('กรุณาเลือกอุปกรณ์และตัวเลือกให้ครบทุกรายการ'); return; }

    const borrower_ward = document.getElementById('b_borrower_ward').value;
    const lender_ward   = document.getElementById('b_lender_ward').value;
    if (borrower_ward === lender_ward) { alert('หอผู้ป่วยผู้ยืมและผู้ให้ยืมต้องไม่เป็นหอเดียวกัน'); return; }

    const payload = {
        items, borrower_ward, lender_ward,
        borrower_name: document.getElementById('b_borrower_name').value,
        expected_return_date: document.getElementById('b_expected_return').value
    };

    try {
        const res = await fetch('api.php?action=borrow', {
            method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.success) {
            alert(`✅ บันทึกการยืมสำเร็จ (${items.length} รายการ)`);
            document.getElementById('borrowForm').reset();
            document.getElementById('b_expected_return').valueAsDate = new Date();
            document.getElementById('b_borrower_ward').value = '';
            document.getElementById('b_lender_ward').value = '';
            ['ss-borrower-ward','ss-lender-ward'].forEach(id => {
                const el = document.getElementById(id);
                el.querySelector('.selected-text').textContent = '-- เลือกหอผู้ป่วย --';
                el.querySelector('.selected-text').classList.add('placeholder');
            });
            document.getElementById('items-container').innerHTML = '';
            addItem();
        } else { alert(result.message); }
    } catch (err) { console.error(err); alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'); }
}

// ============================================================
// Utilities
// ============================================================
function escHtml(str) {
    return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

// Init
addItem();
</script>
</body>
</html>