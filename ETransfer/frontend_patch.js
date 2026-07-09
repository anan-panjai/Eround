/**
 * =====================================================================
 *  PATCH สำหรับ suandok_transport_ORV3.php
 * =====================================================================
 *  วางก่อน </script> ใน block แรก (บรรทัดประมาณ 1755)
 *  หน้าที่: เก็บ transfer_id ที่ได้จาก gemini_api.php
 *           และส่งข้อมูลเพิ่มเติม (hn, lock, complete, waypoints)
 *           ไปยัง suandok_transport_api.php
 * =====================================================================
 */

// ─── 1. เก็บ transfer_id ไว้ใช้ตลอด session ──────────────────────────
let currentTransferId = null;   // ได้รับจาก gemini_api.php ครั้งแรก

// ─── 2. แก้ runAISafetyCheck — เพิ่ม hn + transfer_id เข้า payload ──
//
//  ค้นหาบรรทัดนี้ในไฟล์หลัก:
//
//      const formData = {
//          form_mode: 'safety_check',
//
//  แก้เป็น:
//
//      const formData = {
//          form_mode: 'safety_check',
//          hn: document.querySelector('input[placeholder="ระบุ HN"]')?.value || 'UNKNOWN',
//          transfer_id: currentTransferId,   // null = INSERT ใหม่, มีค่า = UPDATE
//
//  ──────────────────────────────────────────────────────────────────
//  จากนั้นค้นหาบรรทัดนี้:
//
//      if (data.error) {
//          aiResult.innerHTML = `<strong>Error:</strong> ${data.error}`;
//      } else {
//          const formattedText = data.result.replace(/\n/g, '<br>');
//          aiResult.innerHTML = `<strong>AI Analysis:</strong><br><br>${formattedText}`;
//      }
//
//  แก้เป็น (เพิ่ม 2 บรรทัด หลัง formattedText):
//
//      if (data.error) {
//          aiResult.innerHTML = `<strong>Error:</strong> ${data.error}`;
//      } else {
//          const formattedText = data.result.replace(/\n/g, '<br>');
//          aiResult.innerHTML = `<strong>AI Analysis:</strong><br><br>${formattedText}`;
//          // *** เพิ่มใหม่ ***
//          if (data.transfer_id) currentTransferId = data.transfer_id;
//          showSavedBadge('ai-result', currentTransferId);
//      }

// ─── 3. แก้ runSection6AI — เพิ่ม transfer_id เข้า payload ──────────
//
//  ค้นหาบรรทัดนี้ใน runSection6AI:
//
//      let formData = {
//          form_mode: 'handoff',
//
//  แก้เป็น:
//
//      let formData = {
//          form_mode: 'handoff',
//          transfer_id: currentTransferId,   // *** เพิ่มใหม่ ***

// ─── 4. แก้ lockSections1to6 — เรียก API แทน alert เดิม ─────────────
//
//  ค้นหา function lockSections1to6() แล้วแก้ส่วน alert:
//
//  เดิม:
//      alert('ข้อมูลส่วนที่ 1-6 ถูกล็อคเรียบร้อยแล้ว');
//
//  ใหม่:
//      await apiLock();

// ─── 5. แก้ completeTransfer — เรียก API แทน alert เดิม ─────────────
//
//  เดิม:
//      function completeTransfer() {
//          alert('transfer patient complete');
//      }
//
//  ใหม่:
//      async function completeTransfer() {
//          await apiComplete();
//      }

// ─── 6. แก้ removeWaypoint — เรียก API ลบ Waypoint ──────────────────
//
//  เดิม:
//      function removeWaypoint(wpNum) { ... entry.remove() ... }
//
//  ใหม่: เพิ่ม apiDeleteWaypoint(wpNum) ก่อน entry.remove()

// =====================================================================
//  NEW HELPER FUNCTIONS  (วางต่อท้าย script block แรก)
// =====================================================================

const API_BASE = 'suandok_transport_api.php';  // ปรับ path ตามที่ deploy

/** แสดง badge "บันทึกแล้ว #ID" ใต้ผลลัพธ์ AI */
function showSavedBadge(containerId, id) {
    if (!id) return;
    const el = document.getElementById(containerId);
    if (!el) return;
    let badge = el.querySelector('.db-saved-badge');
    if (!badge) {
        badge = document.createElement('div');
        badge.className = 'db-saved-badge';
        badge.style.cssText = 'margin-top:.5rem;font-size:.75rem;color:#047857;'
            + 'background:#d1fae5;border-radius:6px;padding:.25rem .75rem;display:inline-block;';
        el.appendChild(badge);
    }
    badge.textContent = `✅ บันทึกลง Database แล้ว (Transfer #${id})`;
}

/** POST /transfers/{id}/lock */
async function apiLock() {
    if (!currentTransferId) {
        alert('กรุณากดปุ่ม AI Safety Check ก่อนเพื่อสร้างบันทึก');
        return;
    }
    try {
        const res = await fetch(`${API_BASE}/transfers/${currentTransferId}/lock`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        const json = await res.json();
        if (json.success) {
            // ล็อค UI เหมือนเดิม
            const cards = document.querySelectorAll('.card');
            for (let i = 0; i <= 4; i++) {
                if (cards[i]) {
                    cards[i].querySelectorAll('input, select, textarea, button')
                             .forEach(el => el.disabled = true);
                    cards[i].style.pointerEvents = 'none';
                    cards[i].style.opacity = '0.85';
                }
            }
            alert(`✅ ล็อคข้อมูลส่วนที่ 1-6 สำเร็จ (Transfer #${currentTransferId})`);
        } else {
            alert('เกิดข้อผิดพลาด: ' + (json.message || 'ไม่ทราบสาเหตุ'));
        }
    } catch (err) {
        console.error(err);
        alert('ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' + err.message);
    }
}

/** POST /transfers/{id}/complete  (Section 7 Destination Reassessment) */
async function apiComplete() {
    if (!currentTransferId) {
        alert('ไม่พบ Transfer ID กรุณากด AI Safety Check ก่อน');
        return;
    }

    const isChild = document.querySelector('input[name="age_group"]:checked')?.value === 'child';
    const payload = {
        dest_time_in      : document.getElementById('dest_time_in')?.value      || null,
        dest_receiver     : document.getElementById('dest_receiver')?.value     || null,
        dest_rr           : document.getElementById('dest_rr')?.value           || null,
        dest_spo2         : document.getElementById('dest_spo2')?.value         || null,
        dest_airo2        : document.getElementById('dest_airo2')?.value        || 'room',
        dest_consciousness: document.getElementById('dest_consciousness')?.value || 'alert',
        dest_sbp          : document.getElementById('dest_sbp')?.value          || null,
        dest_pulse        : document.getElementById('dest_pulse')?.value        || null,
        dest_temp         : document.getElementById('dest_temp')?.value         || null,
        dest_copd         : document.getElementById('dest_copd')?.checked       || false,
        dest_hr_peds      : document.getElementById('dest_hr_peds')?.value      || null,
        dest_resp_effort  : document.getElementById('dest_resp_effort')?.value  || 'normal',
        dest_news_score   : parseInt(document.getElementById('dest-score-value')?.innerText || '0'),
        dest_pews_score   : parseInt(document.getElementById('dest-score-value')?.innerText || '0'),
    };

    try {
        const res = await fetch(`${API_BASE}/transfers/${currentTransferId}/complete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const json = await res.json();
        if (json.success) {
            alert(`✅ บันทึกขนย้ายเสร็จสิ้น (Transfer #${currentTransferId})`);
        } else {
            alert('เกิดข้อผิดพลาด: ' + (json.message || 'ไม่ทราบสาเหตุ'));
        }
    } catch (err) {
        console.error(err);
        alert('ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' + err.message);
    }
}

/** POST /transfers/{id}/waypoints  (เรียกหลัง addWaypoint คำนวณ Score แล้ว) */
async function apiSaveWaypoint(wpNum, score) {
    if (!currentTransferId) return; // ยังไม่มี Transfer — ข้าม

    const isChild = document.querySelector('input[name="age_group"]:checked')?.value === 'child';
    const gv = id => { const el = document.getElementById(id); return el?.value || null; };

    const payload = {
        location_name : gv(`wp_location_${wpNum}`) || `จุดแวะที่ ${wpNum}`,
        time_departed : gv(`wp_time_${wpNum}`),
        rr            : gv(`wp_rr_${wpNum}`),
        spo2          : gv(`wp_spo2_${wpNum}`),
        airo2         : gv(`wp_airo2_${wpNum}`) || 'room',
        consciousness : gv(`wp_consciousness_${wpNum}`) || 'alert',
        sbp           : gv(`wp_sbp_${wpNum}`),
        pulse         : gv(`wp_pulse_${wpNum}`),
        temp          : gv(`wp_temp_${wpNum}`),
        hr_peds       : gv(`wp_hr_peds_${wpNum}`),
        resp_effort   : gv(`wp_resp_effort_${wpNum}`) || 'normal',
        news_score    : isChild ? 0 : score,
        pews_score    : isChild ? score : 0,
        note          : gv(`wp_note_${wpNum}`),
    };

    try {
        const res = await fetch(`${API_BASE}/transfers/${currentTransferId}/waypoints`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const json = await res.json();
        if (json.success && json.data?.id) {
            // เก็บ waypoint DB id ไว้ที่ DOM element เพื่อลบทีหลัง
            const entry = document.getElementById(`wp-entry-${wpNum}`);
            if (entry) entry.dataset.waypointDbId = json.data.id;
        }
    } catch (err) {
        console.error('[Waypoint Save]', err);
    }
}

/** DELETE /transfers/{id}/waypoints/{wid} */
async function apiDeleteWaypoint(wpNum) {
    const entry = document.getElementById(`wp-entry-${wpNum}`);
    const dbId  = entry?.dataset?.waypointDbId;

    if (currentTransferId && dbId) {
        try {
            await fetch(`${API_BASE}/transfers/${currentTransferId}/waypoints/${dbId}`, {
                method: 'DELETE'
            });
        } catch (err) {
            console.error('[Waypoint Delete]', err);
        }
    }
}

// =====================================================================
//  OVERRIDE FUNCTIONS  (วาง ถัดจาก block บน — override ฟังก์ชันเดิม)
// =====================================================================

/**
 * Override lockSections1to6 — เพิ่ม apiLock()
 * แทนที่ฟังก์ชันเดิมที่มีแค่ alert
 */
function lockSections1to6() {
    apiLock();   // async — จัดการ UI ล็อคภายใน apiLock แล้ว
}

/**
 * Override completeTransfer — เพิ่ม apiComplete()
 */
function completeTransfer() {
    apiComplete();
}

/**
 * Override removeWaypoint — เพิ่ม apiDeleteWaypoint ก่อนลบ DOM
 */
function removeWaypoint(wpNum) {
    apiDeleteWaypoint(wpNum);   // ลบจาก DB (ไม่ต้อง await)
    const entry = document.getElementById('wp-entry-' + wpNum);
    if (entry) {
        entry.style.animation = 'fadeOut 0.2s ease';
        setTimeout(() => entry.remove(), 200);
    }
}

/**
 * Override calculateWaypointScore — เพิ่ม apiSaveWaypoint หลังคำนวณ
 * (รอ 800ms หลังหยุดพิมพ์ เพื่อไม่ให้ยิง API ทุก keystroke)
 */
const _waypointSaveTimers = {};
const _origCalculateWaypointScore = calculateWaypointScore;

function calculateWaypointScore(wpNum) {
    _origCalculateWaypointScore(wpNum);   // คำนวณ score เหมือนเดิม

    // debounce 800ms แล้วบันทึก
    clearTimeout(_waypointSaveTimers[wpNum]);
    _waypointSaveTimers[wpNum] = setTimeout(() => {
        const scoreEl = document.getElementById(`wp-score-value-${wpNum}`);
        const score   = parseInt(scoreEl?.innerText || '0');
        apiSaveWaypoint(wpNum, score);
    }, 800);
}
