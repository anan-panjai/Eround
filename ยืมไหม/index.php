<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบยืม-คืนอุปกรณ์ระหว่างหอผู้ป่วย</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af; /* Blue-800 */
            --primary-light: #3b82f6; /* Blue-500 */
            --secondary: #047857; /* Emerald-700 */
            --bg-color: #f3f4f6; /* Gray-100 */
            --surface: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --danger: #ef4444;
            --radius: 8px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Tabs Navigation */
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--border);
            padding-bottom: 0.5rem;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            color: var(--primary-light);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards & Forms */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-light);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
        }
        
        .btn-success {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #047857;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s ease-in-out;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>🏥 ระบบยืม-คืนอุปกรณ์ระหว่างหอผู้ป่วย</h1>
    </div>

    <div class="container">
        
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('dashboard')">รายการที่กำลังยืม</button>
            <button class="tab-btn" onclick="switchTab('borrow')">ทำรายการยืมอุปกรณ์</button>
            <button class="tab-btn" onclick="switchTab('history')">ประวัติการคืน</button>
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content active card">
            <h2 style="margin-bottom: 1rem; color: var(--primary);">รายการอุปกรณ์ที่ยังไม่ได้คืน</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>อุปกรณ์</th>
                            <th>จำนวน</th>
                            <th>ผู้ยืม (หอผู้ป่วย)</th>
                            <th>ผู้ให้ยืม (หอผู้ป่วย)</th>
                            <th>กำหนดคืน</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="active-list">
                        <tr><td colspan="7" style="text-align:center;">กำลังโหลดข้อมูล...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Borrow Form Tab -->
        <div id="borrow" class="tab-content card">
            <h2 style="margin-bottom: 1.5rem; color: var(--primary);">แบบฟอร์มยืมอุปกรณ์</h2>
            <form id="borrowForm" onsubmit="submitBorrow(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label>ชื่ออุปกรณ์ที่ต้องการยืม</label>
                        <select id="b_equipment" class="form-control" required>
                            <option value="">-- เลือกอุปกรณ์ --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>จำนวน</label>
                        <input type="number" id="b_quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>หอผู้ป่วยที่ต้องการยืม (เรา)</label>
                        <select id="b_borrower_ward" class="form-control" required>
                            <option value="">-- เลือกหอผู้ป่วย --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>หอผู้ป่วยที่ให้ยืม (ต้นทาง)</label>
                        <select id="b_lender_ward" class="form-control" required>
                            <option value="">-- เลือกหอผู้ป่วย --</option>
                        </select>
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
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">บันทึกการยืม</button>
                </div>
            </form>
        </div>

        <!-- History Tab -->
        <div id="history" class="tab-content card">
            <h2 style="margin-bottom: 1rem; color: var(--primary);">ประวัติการคืนอุปกรณ์</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>อุปกรณ์</th>
                            <th>ผู้ยืม (หอผู้ป่วย)</th>
                            <th>ผู้รับคืน</th>
                            <th>วันที่คืน</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody id="history-list">
                        <tr><td colspan="5" style="text-align:center;">กำลังโหลดข้อมูล...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">ทำรายการคืนอุปกรณ์</div>
            <form id="returnForm" onsubmit="submitReturn(event)">
                <input type="hidden" id="r_transaction_id">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>ชื่อผู้รับคืน (เจ้าหน้าที่)</label>
                    <input type="text" id="r_receiver_name" class="form-control" placeholder="ชื่อ-สกุล" required>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>หมายเหตุ (ถ้ามี)</label>
                    <input type="text" id="r_note" class="form-control" placeholder="เช่น อุปกรณ์ชำรุด หรือ คืนครบถ้วน">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="btn" onclick="closeReturnModal()" style="background:#e5e7eb;">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">ยืนยันการคืน</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Set today's date as default for borrow expected return
        document.getElementById('b_expected_return').valueAsDate = new Date();

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');

            if(tabId === 'dashboard') loadTransactions('borrowed');
            if(tabId === 'history') loadTransactions('returned');
        }

        async function loadOptions() {
            try {
                // Load Wards
                const resWards = await fetch('api.php?action=get_wards');
                const wards = await resWards.json();
                if(wards.success) {
                    let opts = '<option value="">-- เลือกหอผู้ป่วย --</option>';
                    wards.data.forEach(w => opts += `<option value="${w}">${w}</option>`);
                    document.getElementById('b_borrower_ward').innerHTML = opts;
                    document.getElementById('b_lender_ward').innerHTML = opts;
                }

                // Load Equipment
                const resEq = await fetch('api.php?action=get_equipment');
                const eq = await resEq.json();
                if(eq.success) {
                    let opts = '<option value="">-- เลือกอุปกรณ์ --</option>';
                    eq.data.forEach(e => opts += `<option value="${e}">${e}</option>`);
                    document.getElementById('b_equipment').innerHTML = opts;
                }
            } catch (err) {
                console.error("Error loading options", err);
            }
        }

        async function loadTransactions(status) {
            try {
                const res = await fetch(`api.php?action=get_transactions&status=${status}`);
                const data = await res.json();
                
                const tbody = status === 'borrowed' ? document.getElementById('active-list') : document.getElementById('history-list');
                
                if(data.success) {
                    if(data.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:gray;">ไม่มีรายการข้อมูล</td></tr>`;
                        return;
                    }
                    
                    let html = '';
                    data.data.forEach(row => {
                        if(status === 'borrowed') {
                            html += `
                            <tr>
                                <td><strong>${row.equipment_name}</strong></td>
                                <td>${row.quantity}</td>
                                <td>${row.borrower_ward}<br><small style="color:gray;">${row.borrower_name}</small></td>
                                <td>${row.lender_ward}</td>
                                <td><span style="color:#b45309; font-weight:500;">${row.expected_return_date}</span></td>
                                <td><span class="badge badge-warning">กำลังยืม</span></td>
                                <td>
                                    <button onclick="openReturnModal(${row.id})" class="btn btn-success" style="padding:0.4rem 0.8rem; font-size:0.85rem;">คืนอุปกรณ์</button>
                                </td>
                            </tr>`;
                        } else {
                            html += `
                            <tr>
                                <td><strong>${row.equipment_name}</strong> <small>x${row.quantity}</small></td>
                                <td>${row.borrower_ward}</td>
                                <td>${row.receiver_name}</td>
                                <td>${row.return_date}</td>
                                <td><span class="badge badge-success">คืนแล้ว</span></td>
                            </tr>`;
                        }
                    });
                    tbody.innerHTML = html;
                }
            } catch (err) {
                console.error("Error loading transactions", err);
            }
        }

        async function submitBorrow(e) {
            e.preventDefault();
            const payload = {
                equipment_name: document.getElementById('b_equipment').value,
                quantity: document.getElementById('b_quantity').value,
                borrower_ward: document.getElementById('b_borrower_ward').value,
                lender_ward: document.getElementById('b_lender_ward').value,
                borrower_name: document.getElementById('b_borrower_name').value,
                expected_return_date: document.getElementById('b_expected_return').value
            };

            if(payload.borrower_ward === payload.lender_ward) {
                alert("หอผู้ป่วยผู้ยืมและผู้ให้ยืมต้องไม่เป็นหอเดียวกัน");
                return;
            }

            try {
                const res = await fetch('api.php?action=borrow', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if(result.success) {
                    alert('บันทึกการยืมสำเร็จ');
                    document.getElementById('borrowForm').reset();
                    document.getElementById('b_expected_return').valueAsDate = new Date();
                    switchTab('dashboard');
                } else {
                    alert(result.message);
                }
            } catch (err) {
                console.error(err);
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อ");
            }
        }

        function openReturnModal(id) {
            document.getElementById('r_transaction_id').value = id;
            document.getElementById('r_receiver_name').value = '';
            document.getElementById('r_note').value = '';
            document.getElementById('returnModal').classList.add('active');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.remove('active');
        }

        async function submitReturn(e) {
            e.preventDefault();
            const payload = {
                transaction_id: document.getElementById('r_transaction_id').value,
                receiver_name: document.getElementById('r_receiver_name').value,
                return_note: document.getElementById('r_note').value
            };

            try {
                const res = await fetch('api.php?action=return', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if(result.success) {
                    alert('บันทึกการคืนสำเร็จ');
                    closeReturnModal();
                    loadTransactions('borrowed');
                } else {
                    alert(result.message);
                }
            } catch (err) {
                console.error(err);
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อ");
            }
        }

        // Initialize
        loadOptions();
        loadTransactions('borrowed');
    </script>
</body>
</html>
