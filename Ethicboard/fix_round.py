import re

with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'r') as f:
    content = f.read()

# 1. Replace CSS
css_target = """        .bed-input-row {
            display: flex; gap: 6px; margin-bottom: 8px; flex-wrap: wrap;
        }

        .bed-tag {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 5px 10px; border-radius: 20px;
            background: #FEF3C7; border: 1px solid #FCD34D;
            font-size: 13px; font-weight: 600; color: #92400E;
        }

        .bed-tag .remove-bed {
            width: 16px; height: 16px; border-radius: 50%;
            border: none; background: rgba(146,64,14,0.15);
            color: #92400E; font-size: 10px;
            cursor: pointer; display: flex;
            align-items: center; justify-content: center;
            line-height: 1;
        }

        .add-bed-input {
            display: flex; gap: 4px;
        }

        .add-bed-input input {
            width: 80px; padding: 5px 10px;
            border: 1.5px solid #FCD34D;
            border-radius: 20px; font-size: 13px;
            font-family: inherit; background: white;
            text-align: center;
        }

        .add-bed-input input:focus {
            outline: none; border-color: #F59E0B;
        }

        .add-bed-input button {
            width: 28px; height: 28px; border-radius: 50%;
            border: none; background: #F59E0B; color: white;
            font-size: 16px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }"""

css_replacement = """        .bed-issue-item {
            border: 1px solid #FCD34D;
            background: white;
            border-radius: var(--radius-sm);
            padding: 10px;
            margin-bottom: 10px;
        }
        .bed-issue-item:last-child {
            margin-bottom: 0;
        }
        .bed-issue-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bed-issue-header .bed-num-input {
            width: 80px; padding: 5px 10px;
            border: 1.5px solid #FCD34D;
            border-radius: 20px; font-size: 13px;
            font-family: inherit; background: white;
            text-align: center;
        }
        .bed-issue-header .bed-num-input:focus {
            outline: none; border-color: #F59E0B;
        }
        .btn-remove-issue {
            margin-left: auto;
            background: #FEF2F2; color: #DC2626; border: none;
            border-radius: 20px; width: 24px; height: 24px; font-size: 12px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .btn-add-bed {
            width: 100%; padding: 8px; border: 1.5px dashed #FCD34D;
            border-radius: var(--radius-sm); background: #FFFBEB;
            color: #92400E; font-size: 13px; font-weight: 600;
            cursor: pointer; margin-top: 10px;
        }"""

content = content.replace(css_target, css_replacement)

# 2. Replace HTML panels using Regex
# We want to match from <div class="issue-panel-inner"> up to the closing </div> of issue-panel-inner
# inside the cards that have <div class="issue-label">⚠️ ระบุเตียงที่พบปัญหา</div>

pattern = re.compile(
    r'<div class="issue-panel-inner">\s*<div class="issue-label">⚠️ ระบุเตียงที่พบปัญหา</div>\s*<div class="bed-input-row">.*?<textarea class="issue-note" placeholder="ระบุปัญหาที่พบ..."></textarea>(.*?)</div>\s*</div>',
    re.DOTALL
)

def html_repl(match):
    quick_tags = match.group(1).rstrip()
    
    return f"""<div class="issue-panel-inner">
                    <div class="bed-issues-list">
                        <div class="bed-issue-item">
                            <div class="bed-issue-header">
                                <span class="issue-label" style="margin-bottom:0;">⚠️ เตียง</span>
                                <input type="text" class="bed-num-input" placeholder="เลขเตียง">
                                <button class="btn-remove-issue" onclick="removeBedIssue(this)">✕</button>
                            </div>
                            <div class="issue-label" style="margin-top:8px;">📝 ปัญหาที่พบ</div>
                            <textarea class="issue-note" placeholder="ระบุปัญหา..."></textarea>{quick_tags}
                        </div>
                    </div>
                    <button class="btn-add-bed" onclick="addBedIssue(this)">+ เพิ่มเตียงอื่น</button>
                </div>
            </div>"""

content = pattern.sub(html_repl, content)

# 3. Replace JS functions
js_target_1 = """// ===== QUICK ADD HELPER =====
function appendQuickNote(chip, text) {
    const parent = chip.closest('.issue-panel-inner') || chip.closest('.assess-detail-inner');
    if (!parent) return;
    const textarea = parent.querySelector('textarea');
    if (!textarea) return;
    const val = textarea.value.trim();
    if (val) {
        textarea.value = val + ', ' + text;
    } else {
        textarea.value = text;
    }
    textarea.focus();
}"""

js_repl_1 = """// ===== QUICK ADD HELPER =====
function appendQuickNote(chip, text) {
    const parent = chip.closest('.bed-issue-item') || chip.closest('.assess-detail-inner');
    if (!parent) return;
    const textarea = parent.querySelector('textarea');
    if (!textarea) return;
    const val = textarea.value.trim();
    if (val) {
        textarea.value = val + ', ' + text;
    } else {
        textarea.value = text;
    }
    textarea.focus();
}"""

content = content.replace(js_target_1, js_repl_1)

js_target_2 = """// ===== BED TAGS =====
function bedKeydown(e, input) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addBed(input.closest('.add-bed-input').querySelector('button'));
    }
}

function addBed(addBtn) {
    const panel = addBtn.closest('.issue-panel-inner');
    const input = panel.querySelector('.bed-num-input');
    const val = input.value.trim();
    if (!val) return;

    const area = panel.querySelector('.bed-tags-area');
    const tag = document.createElement('span');
    tag.className = 'bed-tag';
    tag.innerHTML = `🛏 ${val} <button class="remove-bed" onclick="this.parentElement.remove()">✕</button>`;
    area.appendChild(tag);
    input.value = '';
    input.focus();
}"""

js_repl_2 = """// ===== BED ISSUES =====
function addBedIssue(btn) {
    const panelInner = btn.closest('.issue-panel-inner');
    const list = panelInner.querySelector('.bed-issues-list');
    const firstItem = list.querySelector('.bed-issue-item');
    const newItem = firstItem.cloneNode(true);
    newItem.querySelector('.bed-num-input').value = '';
    newItem.querySelector('.issue-note').value = '';
    list.appendChild(newItem);
    newItem.querySelector('.bed-num-input').focus();
}

function removeBedIssue(btn) {
    const item = btn.closest('.bed-issue-item');
    const list = item.parentElement;
    if (list.querySelectorAll('.bed-issue-item').length > 1) {
        item.remove();
    } else {
        item.querySelector('.bed-num-input').value = '';
        item.querySelector('.issue-note').value = '';
    }
}"""

content = content.replace(js_target_2, js_repl_2)

js_target_3 = """    // Collect issue details
    const issues = {};
    document.querySelectorAll('.round-item').forEach(item => {
        const key = item.dataset.round;
        if (roundStatuses[key] === 'issue') {
            const beds = [];
            item.querySelectorAll('.bed-tag').forEach(tag => {
                beds.push(tag.textContent.replace('✕', '').replace('🛏', '').trim());
            });
            const note = item.querySelector('.issue-note').value.trim();
            issues[key] = { beds, note };
        }
    });"""

js_repl_3 = """    // Collect issue details
    const issues = {};
    document.querySelectorAll('.round-item').forEach(item => {
        const key = item.dataset.round;
        if (roundStatuses[key] === 'issue') {
            const problems = [];
            item.querySelectorAll('.bed-issue-item').forEach(issueItem => {
                const bed = issueItem.querySelector('.bed-num-input').value.trim();
                const note = issueItem.querySelector('.issue-note').value.trim();
                if (bed || note) {
                    problems.push({ bed, note });
                }
            });
            issues[key] = problems;
        }
    });"""

content = content.replace(js_target_3, js_repl_3)

js_target_4 = """    // Clear round statuses
    Object.keys(roundStatuses).forEach(k => delete roundStatuses[k]);
    document.querySelectorAll('.round-item').forEach(item => {
        item.querySelectorAll('.st-btn').forEach(b => b.classList.remove('active'));
        item.querySelector('.issue-panel').classList.remove('open');
        item.querySelector('.bed-tags-area').innerHTML = '';
        item.querySelector('.bed-num-input').value = '';
        item.querySelector('.issue-note').value = '';
    });"""

js_repl_4 = """    // Clear round statuses
    Object.keys(roundStatuses).forEach(k => delete roundStatuses[k]);
    document.querySelectorAll('.round-item').forEach(item => {
        item.querySelectorAll('.st-btn').forEach(b => b.classList.remove('active'));
        item.querySelector('.issue-panel').classList.remove('open');
        const list = item.querySelector('.bed-issues-list');
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

content = content.replace(js_target_4, js_repl_4)


with open('/Applications/XAMPP/xamppfiles/htdocs/Ethic board/isi_down_round.php', 'w') as f:
    f.write(content)

print("Done replacing.")
