import re

with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'r') as f:
    content = f.read()

# Add GOOGLE_SCRIPT_URL placeholder at the top of the script
init_state_target = """// ===== STATE =====
const TOTAL_ROUNDS = 8;"""

init_state_repl = """// ===== STATE =====
const GOOGLE_SCRIPT_URL = "YOUR_GOOGLE_WEB_APP_URL_HERE"; // นำ Web App URL ของ Google Script มาใส่ตรงนี้
const TOTAL_ROUNDS = 8;"""

content = content.replace(init_state_target, init_state_repl)

# Update submitForm function
submit_target = """    // Save to localStorage
    const records = JSON.parse(localStorage.getItem('isidown_records') || '[]');
    records.push(record);
    localStorage.setItem('isidown_records', JSON.stringify(records));

    console.log('Saved:', record);

    // Count issues for modal
    const issueCount = Object.values(roundStatuses).filter(v => v === 'issue').length;
    const desc = issueCount > 0
        ? `บันทึกแล้ว — พบปัญหา ${issueCount} รายการ`
        : 'บันทึกแล้ว — ผ่านทุกรายการ ✓';
    document.getElementById('modalDesc').textContent = desc;
    document.getElementById('modalSuccess').classList.add('show');
}"""

submit_repl = """    // เช็คว่ามีการตั้งค่า Google Script URL หรือไม่
    if (GOOGLE_SCRIPT_URL && GOOGLE_SCRIPT_URL !== "YOUR_GOOGLE_WEB_APP_URL_HERE") {
        toast('⏳ กำลังบันทึกข้อมูล...', 'success');
        const submitBtn = document.querySelector('.btn-primary');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '⏳ กำลังบันทึก...';
        submitBtn.disabled = true;

        fetch(GOOGLE_SCRIPT_URL, {
            method: 'POST',
            body: JSON.stringify(record),
            // redirect: "follow" สำคัญมากสำหรับ Google Apps Script
            redirect: "follow",
            headers: {
                "Content-Type": "text/plain;charset=utf-8",
            }
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.result === 'success') {
                showSuccessModal();
            } else {
                toast('❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'warn');
                console.error(data.error);
            }
        })
        .catch(err => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            toast('❌ เกิดข้อผิดพลาดในการเชื่อมต่อ', 'warn');
            console.error(err);
        });
    } else {
        // Save to localStorage as fallback
        const records = JSON.parse(localStorage.getItem('isidown_records') || '[]');
        records.push(record);
        localStorage.setItem('isidown_records', JSON.stringify(records));
        console.log('Saved to LocalStorage:', record);
        showSuccessModal();
    }
}

function showSuccessModal() {
    const issueCount = Object.values(roundStatuses).filter(v => v === 'issue').length;
    const desc = issueCount > 0
        ? `บันทึกแล้ว — พบปัญหา ${issueCount} รายการ`
        : 'บันทึกแล้ว — ผ่านทุกรายการ ✓';
    document.getElementById('modalDesc').textContent = desc;
    document.getElementById('modalSuccess').classList.add('show');
}"""

content = content.replace(submit_target, submit_repl)

with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'w') as f:
    f.write(content)

print("Updated submit logic.")
