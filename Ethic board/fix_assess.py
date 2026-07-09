import re

with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'r') as f:
    content = f.read()

# 1. Replace Spiritual
sp_target = """                <div class="assess-detail-inner">
                    <label class="form-label">ระบุเตียงและรายละเอียด</label>
                    <textarea class="assess-textarea" id="note-spiritual" placeholder="เช่น เตียง 5 - ผู้ป่วยต้องการพบพระ..."></textarea>
                    <div class="quick-tags-row">"""

sp_repl = """                <div class="assess-detail-inner issue-panel-inner">
                    <div class="bed-issues-list">
                        <div class="bed-issue-item">
                            <div class="bed-issue-header">
                                <span class="issue-label" style="margin-bottom:0;">⚠️ เตียง</span>
                                <input type="text" class="bed-num-input" placeholder="เลขเตียง">
                                <button class="btn-remove-issue" onclick="removeBedIssue(this)">✕</button>
                            </div>
                            <div class="issue-label" style="margin-top:8px;">📝 รายละเอียดเพิ่มเติม</div>
                            <textarea class="assess-textarea issue-note" placeholder="เช่น ผู้ป่วยต้องการพบพระ..."></textarea>
                            <div class="quick-tags-row">"""

content = content.replace(sp_target, sp_repl)

sp_target_end = """                        <span class="quick-tag-chip" onclick="appendQuickNote(this, 'อยู่: เตียงแข็งไป')">เตียงแข็งไป</span>
                        <span class="quick-tag-chip" onclick="appendQuickNote(this, 'อยู่: อยากย้ายเตียง')">อยากย้ายเตียง</span>
                    </div>
                </div>"""

sp_repl_end = """                        <span class="quick-tag-chip" onclick="appendQuickNote(this, 'อยู่: เตียงแข็งไป')">เตียงแข็งไป</span>
                        <span class="quick-tag-chip" onclick="appendQuickNote(this, 'อยู่: อยากย้ายเตียง')">อยากย้ายเตียง</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn-add-bed" onclick="addBedIssue(this)">+ เพิ่มเตียงอื่น</button>
                </div>"""

content = content.replace(sp_target_end, sp_repl_end)

# 2. Replace Ethics
et_target = """            <!-- หมายเหตุ -->
            <div class="ethics-note-area">
                <label class="form-label">📝 หมายเหตุจริยธรรม (ถ้ามี)</label>
                <textarea class="assess-textarea" id="note-ethics" placeholder="ระบุรายละเอียดปัญหาจริยธรรมที่พบ..."></textarea>
            </div>"""

et_repl = """            <!-- หมายเหตุ -->
            <div class="ethics-note-area issue-panel-inner">
                <div class="bed-issues-list">
                    <div class="bed-issue-item">
                        <div class="bed-issue-header">
                            <span class="issue-label" style="margin-bottom:0;">⚠️ เตียง</span>
                            <input type="text" class="bed-num-input" placeholder="เลขเตียง">
                            <button class="btn-remove-issue" onclick="removeBedIssue(this)">✕</button>
                        </div>
                        <div class="issue-label" style="margin-top:8px;">📝 หมายเหตุจริยธรรม (ถ้ามี)</div>
                        <textarea class="assess-textarea issue-note" placeholder="ระบุรายละเอียดปัญหาจริยธรรมที่พบ..."></textarea>
                    </div>
                </div>
                <button class="btn-add-bed" onclick="addBedIssue(this)">+ เพิ่มเตียงอื่น</button>
            </div>"""

content = content.replace(et_target, et_repl)

# 3. Replace submitForm part
js_sub_target = """    // Collect assess notes
    const assessNotes = {};
    ['spiritual', 'ethics'].forEach(k => {
        const note = document.getElementById('note-' + k);
        if (note && note.value.trim()) assessNotes[k] = note.value.trim();
    });"""

js_sub_repl = """    // Collect assess notes
    const assessNotes = {};
    
    const spIssues = [];
    document.querySelectorAll('#det-spiritual .bed-issue-item').forEach(item => {
        const bed = item.querySelector('.bed-num-input').value.trim();
        const note = item.querySelector('.issue-note').value.trim();
        if (bed || note) spIssues.push({ bed, note });
    });
    if (spIssues.length > 0) assessNotes['spiritual'] = spIssues;

    const etIssues = [];
    document.querySelectorAll('.ethics-note-area .bed-issue-item').forEach(item => {
        const bed = item.querySelector('.bed-num-input').value.trim();
        const note = item.querySelector('.issue-note').value.trim();
        if (bed || note) etIssues.push({ bed, note });
    });
    if (etIssues.length > 0) assessNotes['ethics'] = etIssues;"""

content = content.replace(js_sub_target, js_sub_repl)

# 4. Replace resetAll part
js_res_target = """    // Clear assess
    Object.keys(assessStatuses).forEach(k => delete assessStatuses[k]);
    document.querySelectorAll('.assess-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.assess-detail').forEach(d => d.classList.remove('open'));
    ['spiritual', 'ethics'].forEach(k => {
        const n = document.getElementById('note-' + k);
        if (n) n.value = '';
    });"""

js_res_repl = """    // Clear assess
    Object.keys(assessStatuses).forEach(k => delete assessStatuses[k]);
    document.querySelectorAll('.assess-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.assess-detail').forEach(d => d.classList.remove('open'));
    
    ['#det-spiritual', '.ethics-note-area'].forEach(selector => {
        const list = document.querySelector(selector + ' .bed-issues-list');
        if (list) {
            const issues = list.querySelectorAll('.bed-issue-item');
            issues.forEach((issue, index) => {
                if (index > 0) {
                    issue.remove();
                } else {
                    issue.querySelector('.bed-num-input').value = '';
                    issue.querySelector('.issue-note').value = '';
                }
            });
        }
    });"""

content = content.replace(js_res_target, js_res_repl)

with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'w') as f:
    f.write(content)

print("Done.")
