<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Assessment & Early Warning Score</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #eff6ff;
            --primary-hover: #2563eb;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition: all 0.2s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            padding: 2rem 1rem;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 700px;
        }

        .card {
            background-color: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .step-badge {
            background-color: var(--primary-light);
            color: var(--primary);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
        }

        h2 {
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 700;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .grid-1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        label svg {
            width: 16px;
            height: 16px;
            color: var(--text-muted);
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
            background-color: #f9fafb;
            color: var(--text-main);
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background-color: #ffffff;
        }

        hr {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 2rem 0;
        }

        /* Radio & Checkbox Styles */
        .radio-group {
            display: flex;
            gap: 2rem;
            margin-top: 0.5rem;
        }

        .radio-label,
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            user-select: none;
        }

        .checkbox-group {
            margin-bottom: 1.5rem;
        }

        input[type="radio"],
        input[type="checkbox"] {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Score Banner */
        .score-banner {
            background-color: var(--primary-light);
            color: var(--primary-hover);
            padding: 1rem;
            border-radius: var(--radius-md);
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 2rem;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .score-banner.score-low {
            background-color: #d1fae5;
            color: #047857;
        }

        .score-banner.score-medium {
            background-color: #fef3c7;
            color: #b45309;
        }

        .score-banner.score-high {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        @media (max-width: 600px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .fade-enter {
            opacity: 0;
            transform: translateY(-10px);
        }

        .fade-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 300ms, transform 300ms;
        }

        .pediatric-field {
            display: none;
            /* Hidden by default, shown for kids */
        }

        .evaluate-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
        }

        .evaluate-btn:hover {
            background-color: var(--primary-hover);
        }

        .ai-btn {
            background-image: linear-gradient(to right, #8b5cf6, #d946ef);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ai-btn:hover {
            opacity: 0.9;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);
        }

        .result-box {
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
        }

        .result-box h3 {
            margin-bottom: 0.5rem;
            color: inherit;
        }

        .result-box ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }

        .result-box li {
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="header">
                <span class="step-badge">1</span>
                <h2>ข้อมูลผู้ป่วย & <span id="assessment-title">NEWS</span></h2>
            </div>

            <form id="assessment-form">
                <div class="grid-2">
                    <div class="form-group">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            Hospital Number (HN)
                        </label>
                        <input type="text" placeholder="ระบุ HN">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            ชื่อ
                        </label>
                        <input type="text" placeholder="ระบุชื่อผู้ป่วย">
                    </div>
                    <div class="form-group">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Ward
                        </label>
                        <input type="text" placeholder="ระบุวอร์ด">
                    </div>
                </div>

                <div class="grid-1">
                    <div class="form-group">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            ปลายทาง
                        </label>
                        <input type="text" placeholder="ระบุปลายทาง">
                    </div>
                </div>

                <hr>

                <!-- Age Group Selection -->
                <div class="grid-1">
                    <div class="form-group">
                        <label>ประเภทผู้ป่วย (สำหรับการประเมิน)</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="age_group" value="adult" checked
                                    onchange="toggleAssessmentType()">
                                ผู้ใหญ่ (ระบบจะใช้ NEWS Score)
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="age_group" value="child" onchange="toggleAssessmentType()">
                                เด็ก (ระบบจะใช้ PEWS Score)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>RR (ครั้ง/นาที)</label>
                        <input type="number" id="rr" class="calc-input" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>SpO2 (%)</label>
                        <input type="number" id="spo2" class="calc-input" placeholder="0">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group" id="airo2-group">
                        <label>Air/O2</label>
                        <select id="airo2" class="calc-input">
                            <option value="room">Room Air</option>
                            <option value="oxygen">Oxygen</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Consciousness</label>
                        <select id="consciousness" class="calc-input">
                            <option value="alert">Alert</option>
                            <option value="voice">Voice / Somnolent</option>
                            <option value="pain">Pain / Irritable</option>
                            <option value="unresponsive">Unresponsive / Lethargic</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2 adult-field">
                    <div class="form-group">
                        <label>SBP (mmHg)</label>
                        <input type="number" id="sbp" class="calc-input" placeholder="0">
                    </div>
                    <div class="form-group adult-field">
                        <label>Pulse (ครั้ง/นาที)</label>
                        <input type="number" id="pulse" class="calc-input" placeholder="0">
                    </div>
                </div>

                <div class="grid-2 adult-field">
                    <div class="form-group">
                        <label>Temp (°C)</label>
                        <input type="number" id="temp" class="calc-input" placeholder="0.0" step="0.1">
                    </div>
                </div>

                <div class="grid-2 pediatric-field">
                    <div class="form-group">
                        <label>Heart Rate (ครั้ง/นาที)</label>
                        <input type="number" id="hr_peds" class="calc-input" placeholder="0">
                    </div>
                    <!-- Some simplified versions of PEWS also use capillary refill or specific respiratory effort -->
                    <div class="form-group">
                        <label>Respiratory Effort</label>
                        <select id="resp_effort" class="calc-input">
                            <option value="normal">Normal</option>
                            <option value="mild">Mild (Tachypnea)</option>
                            <option value="moderate">Moderate (Retractions)</option>
                            <option value="severe">Severe (Grunting/Apnea)</option>
                        </select>
                    </div>
                </div>

                <div class="checkbox-group adult-field" id="copd-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="copd" class="calc-input">
                        COPD (ใช้ SpO2 Scale 2)
                    </label>
                </div>

                <div class="score-banner" id="score-banner">
                    <span id="score-label">NEWS:</span> <span id="score-value">0</span>
                </div>
            </form>
        </div>

        <!-- Section 2: Severity of Transfer Assessment -->
        <div class="card" style="margin-top: 2rem;">
            <div class="header">
                <span class="step-badge">2</span>
                <h2>ระดับความรุนแรงของการเคลื่อนย้าย</h2>
            </div>

            <div class="grid-1">
                <div class="form-group">
                    <label>2.1 สื่อสารได้</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="comm" value="yes" checked>
                            ได้
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="comm" value="no">
                            ไม่ได้
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>2.2 การหายใจ</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="breath" value="no_o2" checked onchange="toggleO2Options()">
                            ไม่ใช้ Oxygen support
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="breath" value="o2" onchange="toggleO2Options()">
                            ใช้ Oxygen support
                        </label>
                    </div>
                    <div id="o2-options" style="display: none; margin-top: 0.5rem; margin-left: 1rem;">
                        <select id="o2_type" class="calc-input">
                            <option value="">-- เลือกประเภทการให้ออกซิเจน --</option>
                            <option value="cannula">Cannula</option>
                            <option value="mask_c_bag">Mask c Bag</option>
                            <option value="hfnc">HFNC</option>
                            <option value="niv">NIV</option>
                            <option value="ventilator">Ventilator</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>2.3 สัญญาณชีพ</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="vitals" value="stable" checked onchange="toggleVitalOptions()">
                            คงที่
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="vitals" value="unstable" onchange="toggleVitalOptions()">
                            ไม่คงที่
                        </label>
                    </div>
                    <div id="vital-options" style="display: none; margin-top: 0.5rem; margin-left: 1rem;">
                        <select id="vital_monitor" class="calc-input">
                            <option value="">-- เลือกระยะเวลาการประเมิน --</option>
                            <option value="15min">ประเมินทุก 15 นาที</option>
                            <option value="1hr">ประเมินทุก 1 ชั่วโมง</option>
                            <option value="2-4hr">ประเมินทุก 2-4 ชั่วโมง</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>2.4 ได้รับยา sedation</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="sedation" value="no" checked>
                            ไม่ได้รับ
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="sedation" value="yes">
                            ได้รับ
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>2.5 CTAS level</label>
                    <select id="ctas" class="calc-input">
                        <option value="">-- เลือกระดับ CTAS --</option>
                        <option value="1">Level 1 : Resuscitation</option>
                        <option value="2">Level 2 : Emergent</option>
                        <option value="3">Level 3 : Urgent</option>
                        <option value="4">Level 4 : Less Urgent</option>
                        <option value="5">Level 5 : Non-Urgent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>2.6 ความเสี่ยง</label>
                    <div class="checkbox-group"
                        style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                        <label class="checkbox-label">
                            <input type="checkbox" id="risk_suicide" value="suicide">
                            มีประวัติหนี / ฆ่าตัวตาย
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="risk_mdrs" value="mdrs">
                            เชื้อดื้อยา (MDRS)
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="risk_child" value="child">
                            เด็ก & ทารก
                        </label>
                        <label class="checkbox-label" style="align-items: flex-start;">
                            <input type="checkbox" id="risk_other_check" value="other" onchange="toggleOtherRisk()">
                            ความเสี่ยงอื่นๆ
                        </label>
                        <div id="other-risk-input" style="display: none; margin-left: 1.5rem; margin-top: 0.25rem;">
                            <input type="text" id="risk_other_text" placeholder="ระบุความเสี่ยงอื่นๆ"
                                style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <button type="button" onclick="evaluateTransportSeverity()" class="evaluate-btn">
                ประเมินผลระดับความรุนแรง
            </button>

            <div id="severity-result" style="display: none; margin-top: 1.5rem;">
                <!-- Result dynamically injected here -->
            </div>

            <hr>

            <!-- 2.8 AI Safety Check -->
            <div style="text-align: center;">
                <button type="button" onclick="runAISafetyCheck()" class="ai-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="margin-right: 8px;">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    AI Safety Check (Gemini)
                </button>
                <div
                    style="font-size: 0.8rem; color: var(--danger); margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                    AI อาจผิดพลาดได้ ควรใช้ในการประกอบการตัดสินใจ
                </div>

                <div id="ai-loading" style="display: none; margin-top: 1rem; color: var(--text-muted);">
                    กำลังประมวลผลด้วย AI...
                </div>

                <div id="ai-result"
                    style="display: none; margin-top: 1rem; text-align: left; background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: var(--radius-md); padding: 1rem; color: #831843; font-size: 0.95rem;">
                    <!-- AI result dynamically injected here -->
                </div>
            </div>
        </div>

        <!-- Section 3: Pre-Transport Checklist & Personnel -->
        <div class="card" style="margin-top: 2rem; margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">3</span>
                <h2>รายการตรวจสอบก่อนการเคลื่อนย้าย & เตรียมความพร้อม</h2>
            </div>

            <div class="grid-1">
                <div class="form-group">
                    <label>3.1 คำสั่งการรักษา</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_identify" class="calc-input">
                            Identify และ ตรวจสอบคำสั่งการรักษา
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>3.2 การประเมินพื้นฐาน (ABCDE)</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_abc" class="calc-input">
                            ตรวจสอบ Airway, Breathing, Circulation, Disability, Drain & Splint
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>3.3 เครื่องมือแพทย์และสิ่งที่นำไปด้วย</label>
                    <div class="checkbox-group"
                        style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem; margin-left:1rem;">
                        <label class="checkbox-label">
                            <input type="checkbox" id="eq_emergency_bag" class="calc-input">
                            กระเป๋าฉุกเฉิน อุปกรณ์ครบ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="eq_infusion" class="calc-input">
                            Infusion pump แบตเตอรี่เพียงพอ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="eq_ventilator" class="calc-input">
                            Mobile ventilator แบตเตอรี่เพียงพอ
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="eq_ekg" class="calc-input">
                            EKG monitor และ Pulse oximeter
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" id="eq_records" class="calc-input">
                            ประวัติการรักษา
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label>3.4 บุคลากรทางการแพทย์ที่ร่วมเดินทาง</label>
                    <div id="personnel-warning"
                        style="display: none; padding: 0.75rem; background-color: #fee2e2; color: #b91c1c; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 1rem; border: 1px solid #fecaca;">
                        <strong>⚠️ แจ้งเตือน:</strong> ระดับความรุนแรงของการเคลื่อนย้ายอยู่ในระดับประเมินสูง แนะนำให้มี
                        <strong>แพทย์</strong> และ <strong>พยาบาล</strong> ร่วมเดินทางไปด้วย
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">

                        <!-- Doctor -->
                        <div style="display: flex; flex-direction: column;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="pers_doctor"
                                    onchange="togglePersonnelInput('pers_doctor', 'name_doctor')">
                                แพทย์
                            </label>
                            <div id="name_doctor" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                                <input type="text" placeholder="ระบุชื่อแพทย์">
                            </div>
                        </div>

                        <!-- RN -->
                        <div style="display: flex; flex-direction: column;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="pers_rn"
                                    onchange="togglePersonnelInput('pers_rn', 'name_rn')">
                                พยาบาล
                            </label>
                            <div id="name_rn" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                                <input type="text" placeholder="ระบุชื่อพยาบาล">
                            </div>
                        </div>

                        <!-- PN (Practical Nurse) -->
                        <div style="display: flex; flex-direction: column;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="pers_pn"
                                    onchange="togglePersonnelInput('pers_pn', 'name_pn')">
                                ผู้ช่วยพยาบาล
                            </label>
                            <div id="name_pn" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                                <input type="text" placeholder="ระบุชื่อผู้ช่วยพยาบาล">
                            </div>
                        </div>

                        <!-- Nurse Aide -->
                        <div style="display: flex; flex-direction: column;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="pers_aide"
                                    onchange="togglePersonnelInput('pers_aide', 'name_aide')">
                                ผู้ช่วยเหลือคนไข้
                            </label>
                            <div id="name_aide" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                                <input type="text" placeholder="ระบุชื่อผู้ช่วยเหลือคนไข้">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Section 4: Oxygen Calculation -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">4</span>
                <h2>การคำนวณปริมาณ Oxygen (O2) ที่เหลืออยู่</h2>
            </div>

            <div class="grid-1">
                <div class="form-group"
                    style="padding: 1rem; background-color: var(--primary-light); border-radius: var(--radius-md); border: 1px solid #bfdbfe;">
                    <strong
                        style="color: var(--primary-hover); margin-bottom: 0.5rem; display: block;">ข้อมูลอุปกรณ์จากส่วนที่
                        2:</strong>
                    <div id="o2-device-display" style="font-weight: 500;">
                        <!-- Automatically populated via JS -->
                        ยังไม่ได้ประเมิน
                    </div>
                </div>
            </div>

            <div class="grid-2" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>ขนาดถัง Oxygen</label>
                    <select id="o2_cylinder_size" class="calc-input" onchange="calculateOxygen()">
                        <option value="0.28">ถัง E (0.28 L/psi)</option>
                        <option value="0.16">ถัง D (0.16 L/psi)</option>
                        <option value="2.41">ถัง G (2.41 L/psi)</option>
                        <option value="3.14">ถัง H/K (3.14 L/psi)</option>
                        <option value="custom">กำหนดค่า Factor เอง</option>
                    </select>
                </div>

                <div class="form-group" id="o2_custom_factor_group" style="display: none;">
                    <label>Cylinder Factor (L/psi)</label>
                    <input type="number" id="o2_custom_factor" class="calc-input" placeholder="เช่น 0.28" step="0.01"
                        oninput="calculateOxygen()">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>แรงดันในถัง (PSI)</label>
                    <input type="number" id="o2_pressure" class="calc-input" placeholder="เช่น 1500"
                        oninput="calculateOxygen()">
                </div>
                
                <div class="form-group">
                    <label>Safe Residual Pressure (PSI)</label>
                    <input type="number" id="o2_safe_residual" class="calc-input" value="200"
                        oninput="calculateOxygen()">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">*ปกติเว้น 200 PSI เพื่อความปลอดภัย</small>
                </div>
            </div>
            
            <!-- Standard O2 flow -->
            <div class="grid-1" id="o2_group_standard">
                <div class="form-group">
                    <label>อัตราการให้ O2 (Flow rate: L/min)</label>
                    <input type="number" id="o2_flow_rate" class="calc-input" placeholder="เช่น 5"
                        oninput="calculateOxygen()">
                </div>
            </div>

            <!-- HFNC inputs -->
            <div class="grid-2" id="o2_group_hfnc" style="display: none;">
                <div class="form-group">
                    <label>Total Flow (L/min)</label>
                    <input type="number" id="hfnc_flow" class="calc-input" placeholder="เช่น 40" oninput="calculateOxygen()">
                </div>
                <div class="form-group">
                    <label>Target FiO2 (%)</label>
                    <input type="number" id="hfnc_fio2" class="calc-input" placeholder="เช่น 40" oninput="calculateOxygen()">
                </div>
            </div>

            <!-- NIV inputs -->
            <div class="grid-2" id="o2_group_niv" style="display: none;">
                <div class="form-group">
                    <label>Total O2 Consumption (L/min)</label>
                    <input type="number" id="niv_total_flow" class="calc-input" placeholder="เช่น 15" oninput="calculateOxygen()">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">*รวม MV + Leak compensation จาก Ventilator</small>
                </div>
                <div class="form-group" style="justify-content: flex-start; display: flex; flex-direction: column;">
                    <label class="checkbox-label" style="margin-top: 1.7rem;">
                        <input type="checkbox" id="niv_safety_margin" onchange="calculateOxygen()">
                        เพิ่ม Safety Margin (-20% เผื่อ Leak)
                    </label>
                </div>
            </div>

            <!-- Ventilator inputs -->
            <div class="grid-2" id="o2_group_vent" style="display: none;">
                <div class="form-group">
                    <label>Tidal Volume (mL)</label>
                    <input type="number" id="vent_tv" class="calc-input" placeholder="เช่น 400" oninput="calculateOxygen()">
                </div>
                <div class="form-group">
                    <label>Respiratory Rate (bpm)</label>
                    <input type="number" id="vent_rr" class="calc-input" placeholder="เช่น 16" oninput="calculateOxygen()">
                </div>
            </div>
            <div class="grid-2" id="o2_group_vent2" style="display: none;">
                <div class="form-group">
                    <label>Target FiO2 (%)</label>
                    <input type="number" id="vent_fio2" class="calc-input" placeholder="เช่น 40" oninput="calculateOxygen()">
                </div>
                <div class="form-group">
                    <label>Ventilator Type</label>
                    <div class="radio-group" style="margin-top:0.25rem;">
                        <label class="radio-label">
                            <input type="radio" name="vent_type" value="turbine" checked onchange="calculateOxygen()">
                            Turbine-driven
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="vent_type" value="pneumatic" onchange="calculateOxygen()">
                            Pneumatic-driven
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <div id="o2-result-banner" class="score-banner"
                style="display: none; flex-direction: column; padding: 1.5rem;">
                <div style="font-size: 1rem; color: var(--text-main); font-weight: normal; margin-bottom: 0.5rem;">
                    เวลาที่สามารถใช้งาน Oxygen ได้:</div>
                <div id="o2-time-result" style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">0 นาที</div>
                <div id="o2-warning" style="font-size: 0.9rem; color: var(--danger); display: none;">
                    ⚠️ Oxygen อาจไม่เพียงพอต่อการเคลื่อนย้าย
                </div>
            </div>

        </div>
        
        </div>

        <!-- Section 6: ย้ายหอผู้ป่วย/ย้ายหน่วยงาน (Handoff Information) -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">6</span>
                <h2>ย้ายหอผู้ป่วย / ย้ายหน่วยงาน</h2>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="checkbox-label" style="font-size: 1rem; font-weight: 600; color: var(--primary); padding: 1rem; background-color: var(--primary-light); border-radius: var(--radius-md); border: 1px solid #bfdbfe;">
                    <input type="checkbox" id="toggle_sec6" onchange="toggleSection6()"> เปิดการบันทึกข้อมูลย้ายหอผู้ป่วย / ส่งเวร (Handoff Information)
                </label>
            </div>

            <div id="sec6_content" style="display: none;">
                <div class="grid-2">
                    <div class="form-group">
                        <label>6.1 Consciousness (ดึงอัตโนมัติ)</label>
                        <input type="text" id="sec6_consciousness" class="calc-input" readonly placeholder="ยังไม่ได้ประเมิน" style="background-color: #f3f4f6; color: var(--text-muted);">
                    </div>
                    <div class="form-group">
                        <label>6.2 การหายใจ / O2 (ดึงอัตโนมัติ)</label>
                        <input type="text" id="sec6_breathing" class="calc-input" readonly placeholder="ยังไม่ได้ประเมิน" style="background-color: #f3f4f6; color: var(--text-muted);">
                    </div>
                </div>

            <hr style="margin: 1.5rem 0;">

            <div class="grid-2">
                <!-- 6.3 IV -->
                <div class="form-group">
                    <label>6.3 IV (สายน้ำเกลือ)</label>
                    <div class="checkbox-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                        <label class="checkbox-label">
                            <input type="checkbox" id="iv_pls" value="PLS lock"> PLS lock
                        </label>
                        <label class="checkbox-label" style="align-items: flex-start;">
                            <input type="checkbox" id="iv_central_check" onchange="toggleGroup('iv_central_check', 'iv_central_options')"> Central Line
                        </label>
                        <div id="iv_central_options" style="display: none; margin-left: 1.5rem; margin-top: 0.25rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                                <label class="checkbox-label"><input type="checkbox" id="iv_aline" value="A-line"> A-line</label>
                                <label class="checkbox-label"><input type="checkbox" id="iv_cline" value="C-line"> C-line</label>
                                <label class="checkbox-label"><input type="checkbox" id="iv_dlc" value="DLC"> DLC</label>
                                <label class="checkbox-label"><input type="checkbox" id="iv_perm" value="Perm cath"> Perm cath</label>
                                <label class="checkbox-label"><input type="checkbox" id="iv_other_check" onchange="toggleGroup('iv_other_check', 'iv_other_text')"> อื่นๆ</label>
                                <input type="text" id="iv_other_text" placeholder="ระบุชนิด Central Line อื่นๆ" style="display: none; width: 100%; margin-top: 0.25rem;" class="calc-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6.4 Tube -->
                <div class="form-group">
                    <label>6.4 Tube (ท่อ / สายระบาย)</label>
                    <div class="checkbox-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                        <label class="checkbox-label"><input type="checkbox" id="tube_ng" value="NG"> NG</label>
                        <label class="checkbox-label"><input type="checkbox" id="tube_icd" value="ICD"> ICD</label>
                        <label class="checkbox-label"><input type="checkbox" id="tube_fcath" value="F-cath"> F-cath</label>
                        <label class="checkbox-label"><input type="checkbox" id="tube_wound" value="wound drain"> wound drain</label>
                        <label class="checkbox-label"><input type="checkbox" id="tube_other_check" onchange="toggleGroup('tube_other_check', 'tube_other_text')"> อื่นๆ</label>
                        <input type="text" id="tube_other_text" placeholder="ระบุชนิด Tube อื่นๆ" style="display: none; width: 100%; margin-left: 1.5rem; margin-top: 0.25rem;" class="calc-input">
                    </div>
                </div>
            </div>
            
            <div class="grid-2">
                 <div class="form-group">
                    <label>6.5 Ostomy</label>
                    <div class="checkbox-group" style="margin-top: 0.5rem;">
                        <label class="checkbox-label"><input type="checkbox" id="sec6_ostomy" value="Ostomy"> มี Ostomy</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>6.6 Traction</label>
                    <div class="checkbox-group" style="margin-top: 0.5rem;">
                        <label class="checkbox-label"><input type="checkbox" id="sec6_traction" value="Traction"> มี Traction</label>
                    </div>
                </div>
            </div>

            <div class="grid-1">
                <div class="form-group">
                    <label>6.7 อื่นๆ (อุปกรณ์เสริม / หมายเหตุ)</label>
                    <input type="text" id="sec6_others" class="calc-input" placeholder="ระบุข้อมูลเพิ่มเติมถ้ามี">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">

            <!-- 6.8 Rights & 6.9 Billing -->
            <div class="grid-2">
                <div class="form-group">
                    <label>6.8 สิทธิการรักษา</label>
                    <div class="radio-group" style="margin-bottom: 0.5rem;">
                        <label class="radio-label"><input type="radio" name="rights_status" value="has_rights" onchange="toggleRights()"> มีสิทธิ</label>
                        <label class="radio-label"><input type="radio" name="rights_status" value="no_rights" onchange="toggleRights()"> ไม่มีสิทธิ</label>
                    </div>
                    
                    <div id="rights_has_options" style="display: none; margin-left: 1rem;">
                        <select id="rights_has_select" class="calc-input">
                            <option value="">-- เลือกสิทธิ --</option>
                            <option value="บัตรทอง">บัตรทอง</option>
                            <option value="ประกันสังคม">ประกันสังคม</option>
                            <option value="เบิกตรง">เบิกตรง</option>
                            <option value="ประกันชีวิต">ประกันชีวิต</option>
                            <option value="นิธิสงฆ์">นิธิสงฆ์</option>
                        </select>
                    </div>
                    
                    <div id="rights_no_options" style="display: none; margin-left: 1rem;">
                        <select id="rights_no_select" class="calc-input">
                            <option value="">-- เลือกสาเหตุ --</option>
                            <option value="ต่างด้าว">ต่างด้าว</option>
                            <option value="ท99">ท99</option>
                            <option value="นิธิสงฆ์ไม่ทราบสิทธิ">นิธิสงฆ์ไม่ทราบสิทธิ</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>6.9 ค่ารักษาพยาบาล</label>
                    <div class="radio-group" style="margin-bottom: 0.5rem;">
                        <label class="radio-label"><input type="radio" name="billing_status" value="คิดแล้ว"> คิดแล้ว</label>
                        <label class="radio-label"><input type="radio" name="billing_status" value="ยังไม่ได้คิด"> ยังไม่ได้คิด</label>
                        <label class="radio-label"><input type="radio" name="billing_status" value="other" onchange="toggleBillingOther()"> อื่นๆ</label>
                    </div>
                    <input type="text" id="billing_other_text" style="display: none; margin-top: 0.5rem;" class="calc-input" placeholder="ระบุข้อมูลเพิ่มเติม">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">

            <div class="grid-1">
                <div class="form-group">
                    <label>6.10 ยา / อุปกรณที่ค้างคืนหอผู้ป่วยต้นทาง</label>
                    <textarea id="sec6_leftover_meds" class="calc-input" rows="3" placeholder="ระบุรายการยาหรืออุปกรณ์ที่ค้างคืน..."></textarea>
                </div>
                <div class="form-group">
                    <label>6.11 ปัญหา / ความต้องการของผู้ป่วย</label>
                    <textarea id="sec6_problems" class="calc-input" rows="3" placeholder="ระบุปัญหาหรือความต้องการของผู้ป่วย..."></textarea>
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">

            <!-- 6.12 AI Safety Check (Section 6) -->
            <div style="text-align: center;">
                <button type="button" onclick="runSection6AI()" class="ai-btn" style="background-image: linear-gradient(to right, #0ea5e9, #6366f1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    AI ประเมินข้อมูลการส่งเวร (Handoff Analysis)
                </button>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    AI อาจผิดพลาดได้ กรุณาใช้เป็น Second opinion
                </div>
                
                <div id="ai-loading-sec6" style="display: none; margin-top: 1rem; color: var(--text-muted);">
                    กำลังประมวลผลข้อมูลการส่งเวรด้วย AI...
                </div>
                
                <div id="ai-result-sec6" style="display: none; margin-top: 1rem; text-align: left; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: var(--radius-md); padding: 1rem; color: #0369a1; font-size: 0.95rem;">
                    <!-- AI result dynamically injected here -->
                </div>
            </div>
            </div> <!-- End of sec6_content -->
        </div>

        <!-- Section 7: ประเมินซ้ำปลายทาง -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">7</span>
                <h2>ประเมินซ้ำปลายทาง (Destination Reassessment)</h2>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>7.1 เวลาที่ผู้ป่วยถึงหน่วยงานปลายทาง</label>
                    <input type="time" id="dest_time_in" class="calc-input" oninput="document.getElementById('log_time_in').value = this.value;">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้รับ (ปลายทาง)</label>
                    <input type="text" id="dest_receiver" class="calc-input" placeholder="ระบุชื่อผู้รับปลายทาง" oninput="document.getElementById('log_receiver').value = this.value;">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">
            <h3 style="margin-bottom: 1rem; color: var(--primary); font-size: 1.1rem;">7.2 ประเมิน (<span id="dest-assessment-title">NEWS</span>) ซ้ำ</h3>
            
            <form id="dest-assessment-form">
                <div class="grid-2">
                    <div class="form-group">
                        <label>RR (ครั้ง/นาที)</label>
                        <input type="number" id="dest_rr" class="calc-input dest-calc" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>SpO2 (%)</label>
                        <input type="number" id="dest_spo2" class="calc-input dest-calc" placeholder="0">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group" id="dest_airo2-group">
                        <label>Air/O2</label>
                        <select id="dest_airo2" class="calc-input dest-calc">
                            <option value="room">Room Air</option>
                            <option value="oxygen">Oxygen</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Consciousness</label>
                        <select id="dest_consciousness" class="calc-input dest-calc">
                            <option value="alert">Alert</option>
                            <option value="voice">Voice / Somnolent</option>
                            <option value="pain">Pain / Irritable</option>
                            <option value="unresponsive">Unresponsive / Lethargic</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2 adult-field-dest">
                    <div class="form-group">
                        <label>SBP (mmHg)</label>
                        <input type="number" id="dest_sbp" class="calc-input dest-calc" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Pulse (ครั้ง/นาที)</label>
                        <input type="number" id="dest_pulse" class="calc-input dest-calc" placeholder="0">
                    </div>
                </div>

                <div class="grid-2 adult-field-dest">
                    <div class="form-group">
                        <label>Temp (°C)</label>
                        <input type="number" id="dest_temp" class="calc-input dest-calc" placeholder="0.0" step="0.1">
                    </div>
                </div>

                <div class="grid-2 pediatric-field-dest" style="display: none;">
                    <div class="form-group">
                        <label>Heart Rate (ครั้ง/นาที)</label>
                        <input type="number" id="dest_hr_peds" class="calc-input dest-calc" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Respiratory Effort</label>
                        <select id="dest_resp_effort" class="calc-input dest-calc">
                            <option value="normal">Normal</option>
                            <option value="mild">Mild (Tachypnea)</option>
                            <option value="moderate">Moderate (Retractions)</option>
                            <option value="severe">Severe (Grunting/Apnea)</option>
                        </select>
                    </div>
                </div>

                <div class="checkbox-group adult-field-dest" id="dest-copd-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="dest_copd" class="calc-input dest-calc">
                        COPD (ใช้ SpO2 Scale 2)
                    </label>
                </div>

                <div class="score-banner" id="dest-score-banner">
                    <span id="dest-score-label">NEWS:</span> <span id="dest-score-value">0</span>
                </div>
            </form>
        </div>
        
        <!-- Section 5: Transfer Log (Moved to the bottom before print) -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">5</span>
                <h2>บันทึกการเคลื่อนย้าย (Transfer Log)</h2>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>5.1 เวลาส่งออก (ต้นทาง)</label>
                    <input type="time" id="log_time_out" class="calc-input">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้ส่ง (ต้นทาง)</label>
                    <input type="text" id="log_sender" class="calc-input" placeholder="ระบุชื่อผู้ส่ง (ต้นทาง)">
                </div>
            </div>

            <div class="grid-2" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>5.2 เวลาถึง (ปลายทาง) <span style="font-size: 0.8em; color: var(--text-muted);">(ซิงค์จาก 7.1)</span></label>
                    <input type="time" id="log_time_in" class="calc-input" oninput="document.getElementById('dest_time_in').value = this.value;">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้รับ (ปลายทาง) <span style="font-size: 0.8em; color: var(--text-muted);">(ซิงค์จาก 7.1)</span></label>
                    <input type="text" id="log_receiver" class="calc-input" placeholder="ระบุชื่อผู้รับ (ปลายทาง)" oninput="document.getElementById('dest_receiver').value = this.value;">
                </div>
            </div>
            
            <hr>
            
            <!-- Complete / Print Button -->
            <button type="button" onclick="printForm()" class="evaluate-btn" style="background-color: var(--success); display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 1.1rem; padding: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                🖨️ พิมพ์สรุป 1 หน้า A4
            </button>
        </div>
    </div>

    <script>
        // Elements
        const inputs = document.querySelectorAll('.calc-input');
        const scoreLabel = document.getElementById('score-label');
        const scoreValue = document.getElementById('score-value');
        const scoreBanner = document.getElementById('score-banner');
        const assessmentTitle = document.getElementById('assessment-title');

        const adultFields = document.querySelectorAll('.adult-field');
        const pedFields = document.querySelectorAll('.pediatric-field');

        // Attach event listeners
        inputs.forEach(input => {
            input.addEventListener('input', calculateScore);
            input.addEventListener('change', calculateScore);
        });

        function toggleAssessmentType() {
            const isChild = document.querySelector('input[name="age_group"]:checked').value === 'child';

            if (isChild) {
                assessmentTitle.innerText = 'PEWS';
                scoreLabel.innerText = 'PEWS:';
                adultFields.forEach(el => el.style.display = 'none');
                pedFields.forEach(el => el.style.display = 'grid');
                // adjust block display for non grid items if needed
                document.getElementById('copd-group').style.display = 'none';
            } else {
                assessmentTitle.innerText = 'NEWS';
                scoreLabel.innerText = 'NEWS:';
                adultFields.forEach(el => {
                    // Return to original display based on class
                    if (el.classList.contains('grid-2')) el.style.display = 'grid';
                    else if (el.classList.contains('checkbox-group')) el.style.display = 'block';
                    else el.style.display = 'flex'; // form-group wrapper
                });
                pedFields.forEach(el => el.style.display = 'none');
                document.getElementById('copd-group').style.display = 'block';
            }

            // Recalculate
            calculateScore();
        }

        function calculateScore() {
            const isChild = document.querySelector('input[name="age_group"]:checked').value === 'child';
            let totalScore = 0;

            if (isChild) {
                totalScore = calculatePEWS();
            } else {
                totalScore = calculateNEWS();
            }

            scoreValue.innerText = totalScore;

            // Styling the score banner dynamically
            scoreBanner.className = 'score-banner'; // reset
            if (totalScore >= 7) {
                scoreBanner.classList.add('score-high');
            } else if (totalScore >= 5 || (isChild && totalScore >= 4)) {
                scoreBanner.classList.add('score-medium');
            } else if (totalScore > 0) {
                scoreBanner.classList.add('score-low');
            }
        }

        // Helper to get number
        function getVal(id) {
            const val = parseFloat(document.getElementById(id).value);
            return isNaN(val) ? null : val;
        }

        function calculateNEWS() {
            let score = 0;
            const rr = getVal('rr');
            const spo2 = getVal('spo2');
            const airo2 = document.getElementById('airo2').value;
            const sbp = getVal('sbp');
            const pulse = getVal('pulse');
            const temp = getVal('temp');
            const consciousness = document.getElementById('consciousness').value;
            const isCOPD = document.getElementById('copd').checked;

            // RR Score
            if (rr !== null) {
                if (rr <= 8 || rr >= 25) score += 3;
                else if (rr >= 21 && rr <= 24) score += 2;
                else if (rr >= 9 && rr <= 11) score += 1;
            }

            // SpO2 Score
            if (spo2 !== null) {
                if (isCOPD) {
                    if (spo2 <= 83) score += 3;
                    else if (spo2 >= 84 && spo2 <= 85) score += 2;
                    else if (spo2 >= 86 && spo2 <= 87) score += 1;
                    else if (spo2 >= 93 && airo2 === 'oxygen') {
                        if (spo2 <= 94) score += 1;
                        else if (spo2 <= 96) score += 2;
                        else score += 3;
                    }
                } else {
                    if (spo2 <= 91) score += 3;
                    else if (spo2 >= 92 && spo2 <= 93) score += 2;
                    else if (spo2 >= 94 && spo2 <= 95) score += 1;
                }
            }

            // Air/O2
            if (airo2 === 'oxygen') score += 2;

            // SBP
            if (sbp !== null) {
                if (sbp <= 90 || sbp >= 220) score += 3;
                else if (sbp >= 91 && sbp <= 100) score += 2;
                else if (sbp >= 101 && sbp <= 110) score += 1;
            }

            // Pulse
            if (pulse !== null) {
                if (pulse <= 40 || pulse >= 131) score += 3;
                else if (pulse >= 111 && pulse <= 130) score += 2;
                else if ((pulse >= 41 && pulse <= 50) || (pulse >= 91 && pulse <= 110)) score += 1;
            }

            // Consciousness
            if (consciousness !== 'alert') score += 3;

            // Temp
            if (temp !== null) {
                if (temp <= 35.0) score += 3;
                else if (temp >= 39.1) score += 2;
                else if ((temp >= 35.1 && temp <= 36.0) || (temp >= 38.1 && temp <= 39.0)) score += 1;
            }

            return score;
        }

        function calculatePEWS() {
            // Simplified PEWS scoring logic for demonstration
            let score = 0;
            const rr = getVal('rr');
            const spo2 = getVal('spo2');
            const airo2 = document.getElementById('airo2').value;
            const consciousness = document.getElementById('consciousness').value;

            const hr = getVal('hr_peds');
            const respEffort = document.getElementById('resp_effort').value;

            // Respiratory Effort
            if (respEffort === 'mild') score += 1;
            else if (respEffort === 'moderate') score += 2;
            else if (respEffort === 'severe') score += 3;

            // HR/RR values depend heavily on pediatric age.
            // A placeholder score increment for abnormal values:
            if (rr !== null) {
                if (rr > 60 || rr < 10) score += 3;
                else if (rr > 50) score += 2;
                else if (rr > 40) score += 1;
            }

            if (hr !== null) {
                if (hr > 180 || hr < 60) score += 3;
                else if (hr > 160) score += 2;
                else if (hr > 140) score += 1;
            }

            // O2 Requirement
            if (spo2 !== null) {
                if (spo2 < 90) score += 3;
                else if (spo2 <= 93) score += 2;
                else if (spo2 <= 95) score += 1;
            }
            if (airo2 === 'oxygen') {
                score += 2; // Generic O2 requirement score
            }

            // Behavior/Consciousness
            if (consciousness === 'unresponsive') score += 3;
            else if (consciousness === 'pain') score += 2;
            else if (consciousness === 'voice') score += 1;

            return score;
        }

        // Initialize display state
        toggleAssessmentType();
        toggleO2Options();
        toggleVitalOptions();

        // -------------------------------------------------------------
        // Section 2 Logic
        // -------------------------------------------------------------

        function toggleO2Options() {
            const val = document.querySelector('input[name="breath"]:checked').value;
            const o2Div = document.getElementById('o2-options');
            if (val === 'o2') {
                o2Div.style.display = 'block';
            } else {
                o2Div.style.display = 'none';
                document.getElementById('o2_type').value = '';
            }
            updateO2DeviceDisplay();
        }

        function toggleVitalOptions() {
            const val = document.querySelector('input[name="vitals"]:checked').value;
            const vitalDiv = document.getElementById('vital-options');
            if (val === 'unstable') {
                vitalDiv.style.display = 'block';
            } else {
                vitalDiv.style.display = 'none';
                document.getElementById('vital_monitor').value = '';
            }
        }

        function toggleOtherRisk() {
            const isChecked = document.getElementById('risk_other_check').checked;
            const otherDiv = document.getElementById('other-risk-input');
            if (isChecked) {
                otherDiv.style.display = 'block';
            } else {
                otherDiv.style.display = 'none';
                document.getElementById('risk_other_text').value = '';
            }
        }

        function togglePersonnelInput(checkboxId, inputId) {
            const isChecked = document.getElementById(checkboxId).checked;
            const inputDiv = document.getElementById(inputId);
            if (isChecked) {
                inputDiv.style.display = 'block';
            } else {
                inputDiv.style.display = 'none';
                inputDiv.querySelector('input').value = '';
            }
            checkPersonnelWarning(); // Re-evaluate warning when selections change
        }

        // Global variable to store current severity level
        let currentSeverityLevel = 1;

        function checkPersonnelWarning() {
            const warningDiv = document.getElementById('personnel-warning');

            if (currentSeverityLevel >= 3) {
                const hasDoctor = document.getElementById('pers_doctor').checked;
                const hasRN = document.getElementById('pers_rn').checked;

                if (!hasDoctor || !hasRN) {
                    warningDiv.style.display = 'block';
                } else {
                    warningDiv.style.display = 'none';
                }
            } else {
                warningDiv.style.display = 'none';
            }
        }

        function evaluateTransportSeverity() {
            // Get inputs
            const isChild = document.querySelector('input[name="age_group"]:checked').value === 'child';
            const scoreValueText = document.getElementById('score-value').innerText;
            const score = parseInt(scoreValueText) || 0;
            const consciousness = document.getElementById('consciousness').value;

            const comm = document.querySelector('input[name="comm"]:checked').value; // yes/no
            const breath = document.querySelector('input[name="breath"]:checked').value; // no_o2 / o2
            const vitals = document.querySelector('input[name="vitals"]:checked').value; // stable / unstable
            const vitalMonitor = document.getElementById('vital_monitor').value; // 15min, 1hr, 2-4hr
            const sedation = document.querySelector('input[name="sedation"]:checked').value; // no / yes
            const ctas = parseInt(document.getElementById('ctas').value) || 0;

            // Check risks
            const hasSuicide = document.getElementById('risk_suicide').checked;
            const hasMdrs = document.getElementById('risk_mdrs').checked;
            const hasChildRisk = document.getElementById('risk_child').checked;
            const hasOther = document.getElementById('risk_other_check').checked;
            const otherText = document.getElementById('risk_other_text').value;
            const hasAnyRisk = hasSuicide || hasMdrs || hasChildRisk || hasOther;

            let level = 1; // Default to Level 1

            // Helper to check consciousness changes
            const isConsciousChanged = consciousness !== 'alert';
            const isUnconscious = consciousness === 'unresponsive'; // Just an example mapping

            // Level 4 (รุนแรงมาก)
            if (
                isConsciousChanged || // ความรู้สึกตัวเปลี่ยนแปลง หรือ acute confusion
                vitals === 'unstable' || // สัญญาณชีพไม่คงที่ต้องการการดูแลใกล้ชิด (15 min)
                sedation === 'yes' || // อาจต้องได้รับยา sedation
                ctas === 1 ||
                score >= 7
            ) {
                level = 4;
            }
            // Level 3 (รุนแรงปานกลาง)
            else if (
                consciousness === 'pain' || consciousness === 'voice' || // ซึม/ความรู้สึกตัวลดลง
                vitalMonitor === '1hr' || // สัญญาณชีพคงที่แต่ต้อง observe ทุก 1 ชั่วโมง
                ctas === 2 ||
                (score === 5 || score === 6) // NEWS 5-6 
            ) {
                level = 3;
            }
            // Level 2 (รุนแรงเล็กน้อย)
            else if (
                vitalMonitor === '2-4hr' || // สัญญาณชีพคงที่วัดทุก 2-4 ชั่วโมง
                hasAnyRisk || // มีความเสี่ยงอื่นๆ
                ctas === 3 ||
                (score === 3 || score === 4) // NEWS 3-4
            ) {
                level = 2;
            }
            // Level 1: Default fallthrough if none above match
            else {
                level = 1;
            }

            currentSeverityLevel = level;
            displaySeverityResult(level);
            checkPersonnelWarning(); // Prompt the warning immediately if needed
            updateO2DeviceDisplay(); // Update Section 4 based on Evaluation
        }

        function displaySeverityResult(level) {
            const resDiv = document.getElementById('severity-result');
            resDiv.style.display = 'block';

            let title = '';
            let color = '';
            let carePlan = '';

            if (level === 1) {
                title = 'ระดับ 1: ไม่รุนแรง';
                color = 'var(--success)';
                carePlan = `
                    <ul>
                        <li>ประสานงานหน่วยงานปลายทาง</li>
                        <li>เตรียมเอกสารการเคลื่อนย้าย (ประวัติการรักษา, ใบ x-ray ให้อยู่ในกล่อง)</li>
                        <li>ใบนำทาง(สีฟ้า) หรือ Transport passport</li>
                        <li>ติดตามและบันทึกผลการเคลื่อนย้าย</li>
                    </ul>
                `;
            } else if (level === 2) {
                title = 'ระดับ 2: รุนแรงเล็กน้อย';
                color = '#eab308'; // yellow-500
                carePlan = `
                    <ul>
                        <li>ประสานงานหน่วยงานปลายทาง</li>
                        <li>เตรียมเอกสารการเคลื่อนย้าย (ประวัติการรักษา, ใบ x-ray)</li>
                        <li>เตรียมอุปกรณ์ที่เกี่ยวข้อง (Oxygen, เครื่องวัด SpO2)</li>
                        <li>เฝ้าระวังและติดตามอาการอย่างต่อเนื่อง</li>
                        <li>บันทึกอาการระหว่างการเคลื่อนย้าย และผลลัพธ์</li>
                    </ul>
                `;
            } else if (level === 3) {
                title = 'ระดับ 3: รุนแรงปานกลาง';
                color = '#f97316'; // orange-500
                carePlan = `
                    <ul>
                        <li>ปฏิบัติตามแนวทางการเคลื่อนย้ายผู้ป่วยภาวะวิกฤต</li>
                        <li>ประสานงานล่วงหน้าอย่างใกล้ชิด</li>
                        <li>อาจต้องมีพยาบาลวิชาชีพร่วมเดินทาง</li>
                    </ul>
                `;
            } else if (level === 4) {
                title = 'ระดับ 4: รุนแรงมาก';
                color = 'var(--danger)';
                carePlan = `
                    <ul>
                        <li>ปฏิบัติตามแนวทางการเคลื่อนย้ายผู้ป่วยภาวะวิกฤต</li>
                        <li>ผู้ป่วยความเสี่ยงสูงมาก ต้องมีทีมแพทย์/พยาบาลดูแลระบบทางเดินหายใจและสัญญาณชีพตลอดทาง</li>
                    </ul>
                `;
            }

            resDiv.innerHTML = `
                <div class="result-box" style="background-color: ${color}15; border: 1px solid ${color}; color: var(--text-main);">
                    <h3 style="color: ${color};">${title}</h3>
                    <strong>แนวทางการดูแล:</strong>
                    ${carePlan}
                </div>
            `;
        }

        async function runAISafetyCheck() {
            const aiBtn = document.querySelector('.ai-btn');
            const aiLoading = document.getElementById('ai-loading');
            const aiResult = document.getElementById('ai-result');

            // Collect necessary data to send to Gemini
            const formData = {
                score: document.getElementById('score-value').innerText,
                assessment_type: document.getElementById('assessment-title').innerText, // NEWS or PEWS
                vitals_stable: document.querySelector('input[name="vitals"]:checked').value === 'stable',
                consciousness: document.getElementById('consciousness').value,
                sedation: document.querySelector('input[name="sedation"]:checked').value === 'yes',
                o2_support: document.querySelector('input[name="breath"]:checked').value === 'o2',
                risk_suicide: document.getElementById('risk_suicide').checked,
                risk_mdrs: document.getElementById('risk_mdrs').checked,
                risk_child: document.getElementById('risk_child').checked
            };

            aiBtn.disabled = true;
            aiBtn.style.opacity = '0.7';
            aiLoading.style.display = 'block';
            aiResult.style.display = 'none';

            try {
                const response = await fetch('gemini_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.error) {
                    aiResult.innerHTML = `<strong>Error:</strong> ${data.error}`;
                } else {
                    // Convert simple newlines to <br> for HTML display
                    const formattedText = data.result.replace(/\n/g, '<br>');
                    aiResult.innerHTML = `<strong>AI Analysis:</strong><br><br>${formattedText}`;
                }

            } catch (err) {
                console.error(err);
                aiResult.innerHTML = `<strong>เกิดข้อผิดพลาดในการเชื่อมต่อ:</strong> ${err.message}`;
            } finally {
                aiBtn.disabled = false;
                aiBtn.style.opacity = '1';
                aiLoading.style.display = 'none';
                aiResult.style.display = 'block';
            }
        }

        // -------------------------------------------------------------
        // Section 4 Logic: Oxygen Calculation
        // -------------------------------------------------------------
        function updateO2DeviceDisplay() {
            const isO2 = document.querySelector('input[name="breath"]:checked').value === 'o2';
            const o2Type = document.getElementById('o2_type').options[document.getElementById('o2_type').selectedIndex]?.text;
            const displayDiv = document.getElementById('o2-device-display');

            if (isO2) {
                if (document.getElementById('o2_type').value !== "") {
                    displayDiv.innerHTML = `ใช้ Oxygen support ชนิด: <strong>${o2Type}</strong>`;
                } else {
                    displayDiv.innerHTML = `ใช้ Oxygen support <strong>(ยังไม่ได้ระบุชนิด)</strong>`;
                }
            } else {
                displayDiv.innerHTML = `<span style="color: var(--text-muted);">ไม่ใช้ Oxygen support</span>`;
            }

            // Toggle dynamic inputs
            const typeVal = document.getElementById('o2_type').value;
            document.getElementById('o2_group_standard').style.display = 'none';
            document.getElementById('o2_group_hfnc').style.display = 'none';
            document.getElementById('o2_group_niv').style.display = 'none';
            document.getElementById('o2_group_vent').style.display = 'none';
            document.getElementById('o2_group_vent2').style.display = 'none';

            if (isO2) {
                if (typeVal === 'hfnc') {
                    document.getElementById('o2_group_hfnc').style.display = 'grid';
                } else if (typeVal === 'niv') {
                    document.getElementById('o2_group_niv').style.display = 'grid';
                } else if (typeVal === 'ventilator') {
                    document.getElementById('o2_group_vent').style.display = 'grid';
                    document.getElementById('o2_group_vent2').style.display = 'grid';
                } else {
                    document.getElementById('o2_group_standard').style.display = 'block';
                }
            } else {
                document.getElementById('o2_group_standard').style.display = 'block';
            }

            calculateOxygen(); // trigger recalculation
        }

        function toggleSection6() {
            const isChecked = document.getElementById('toggle_sec6').checked;
            const sec6Content = document.getElementById('sec6_content');
            if (isChecked) {
                sec6Content.style.display = 'block';
                updateSection6Sync(); // Re-sync data when opened
            } else {
                sec6Content.style.display = 'none';
            }
        }

        function calculateOxygen() {
            // Handle Custom Factor Dropdown
            const sizeSelect = document.getElementById('o2_cylinder_size').value;
            const customGroup = document.getElementById('o2_custom_factor_group');

            let factor = 0;
            if (sizeSelect === 'custom') {
                customGroup.style.display = 'block';
                factor = parseFloat(document.getElementById('o2_custom_factor').value);
            } else {
                customGroup.style.display = 'none';
                factor = parseFloat(sizeSelect);
            }

            const isO2 = document.querySelector('input[name="breath"]:checked').value === 'o2';
            const typeVal = isO2 ? document.getElementById('o2_type').value : '';
            const pressure = parseFloat(document.getElementById('o2_pressure').value);

            const resultBanner = document.getElementById('o2-result-banner');
            const timeResult = document.getElementById('o2-time-result');
            const o2Warning = document.getElementById('o2-warning');

            if (isNaN(pressure) || isNaN(factor)) {
                resultBanner.style.display = 'none';
                return;
            }

            let requiredO2Flow = 0;
            let safetyThreshold = 30; // 30 minutes default
            let useNivMargin = false;

            if (typeVal === 'hfnc') {
                const totalFlow = parseFloat(document.getElementById('hfnc_flow').value);
                const targetFiO2 = parseFloat(document.getElementById('hfnc_fio2').value);
                if (isNaN(totalFlow) || isNaN(targetFiO2) || totalFlow <= 0) {
                    resultBanner.style.display = 'none';
                    return;
                }
                if (targetFiO2 <= 21) {
                    requiredO2Flow = 0;
                } else {
                    requiredO2Flow = totalFlow * ((targetFiO2 - 21) / 79);
                }
                safetyThreshold = 30;
            } else if (typeVal === 'niv') {
                const totalFlow = parseFloat(document.getElementById('niv_total_flow').value);
                if (isNaN(totalFlow) || totalFlow <= 0) {
                    resultBanner.style.display = 'none';
                    return;
                }
                requiredO2Flow = totalFlow;
                safetyThreshold = 30;
                useNivMargin = document.getElementById('niv_safety_margin').checked;
            } else if (typeVal === 'ventilator') {
                const tv = parseFloat(document.getElementById('vent_tv').value);
                const rr = parseFloat(document.getElementById('vent_rr').value);
                const targetFiO2 = parseFloat(document.getElementById('vent_fio2').value);
                const ventType = document.querySelector('input[name="vent_type"]:checked').value;
                
                if (isNaN(tv) || isNaN(rr) || isNaN(targetFiO2) || tv <= 0 || rr <= 0) {
                    resultBanner.style.display = 'none';
                    return;
                }
                
                const minuteVolume = (tv * rr) / 1000;
                const biasFlow = 3; // Default 3 L/min
                const totalFlowNeeded = minuteVolume + biasFlow;
                
                if (ventType === 'turbine') {
                    if (targetFiO2 <= 21) {
                        requiredO2Flow = 0;
                    } else {
                        requiredO2Flow = totalFlowNeeded * ((targetFiO2 - 21) / 79);
                    }
                } else {
                    // Pneumatic-driven
                    requiredO2Flow = totalFlowNeeded;
                }
                safetyThreshold = 45;
            } else {
                // Standard
                requiredO2Flow = parseFloat(document.getElementById('o2_flow_rate').value);
                if (isNaN(requiredO2Flow) || requiredO2Flow <= 0) {
                    resultBanner.style.display = 'none';
                    return;
                }
                safetyThreshold = 30;
            }

            // If requiredO2Flow is effectively 0, they aren't using O2 from the tank (e.g. FiO2 21%)
            if (requiredO2Flow <= 0.01) {
                // Not consuming O2
                timeResult.innerText = 'ไม่ใช้ออกซิเจนจากถัง (หรือใช้น้อยมาก)';
                timeResult.style.color = '#047857';
                resultBanner.style.display = 'flex';
                resultBanner.style.backgroundColor = '#d1fae5';
                o2Warning.style.display = 'none';
                return;
            }

            // Handle safe residual pressure
            const safeResidualInput = document.getElementById('o2_safe_residual');
            const safeResidual = safeResidualInput && safeResidualInput.value !== "" ? parseFloat(safeResidualInput.value) : 200;
            const safePressure = Math.max(0, pressure - safeResidual);

            // Time = (Pressure * Factor) / Required O2 Flow
            let totalMinutes = Math.floor((safePressure * factor) / requiredO2Flow);
            
            if (useNivMargin) {
                totalMinutes = Math.floor(totalMinutes * 0.8);
            }

            if (totalMinutes <= 0) {
                timeResult.innerText = '0 นาที (ต้องเปลี่ยนถัง)';
                timeResult.style.color = 'var(--danger)';
                resultBanner.style.display = 'flex';
                resultBanner.style.backgroundColor = '#fee2e2'; // Light red
                o2Warning.style.display = 'block';
                return;
            }

            const hours = Math.floor(totalMinutes / 60);
            const mins = Math.floor(totalMinutes % 60);

            let timeString = '';
            if (hours > 0) {
                timeString += `${hours} ชั่วโมง `;
            }
            timeString += `${mins} นาที`;

            timeResult.innerText = timeString;

            // Simple visual warning logic based on safetyThreshold (30 or 45 mins)
            resultBanner.style.display = 'flex';
            if (totalMinutes < safetyThreshold) {
                // Warning
                timeResult.style.color = 'var(--danger)';
                resultBanner.style.backgroundColor = '#fee2e2';
                o2Warning.style.display = 'block';
                o2Warning.innerText = `⚠️ Oxygen ไม่เพียงพอต่อการเคลื่อนย้าย (น้อยกว่า ${safetyThreshold} นาที)`;
            } else if (totalMinutes < safetyThreshold + 30) {
                // Caution
                timeResult.style.color = '#b45309';
                resultBanner.style.backgroundColor = '#fef3c7';
                o2Warning.style.display = 'none';
            } else {
                // Safe
                timeResult.style.color = '#047857';
                resultBanner.style.backgroundColor = '#d1fae5';
                o2Warning.style.display = 'none';
            }
        }

        function updateSection6Sync() {
            try {
                // Update 6.1 Consciousness
                const conscSelect = document.getElementById('consciousness');
                if (conscSelect) {
                    const conscText = conscSelect.options[conscSelect.selectedIndex]?.text || '-';
                    const s6Consc = document.getElementById('sec6_consciousness');
                    if (s6Consc) s6Consc.value = conscText;
                }

                // Update 6.2 Breathing (from Section 2 & 4)
                const breathOption = document.querySelector('input[name="breath"]:checked');
                const s6Breath = document.getElementById('sec6_breathing');
                if (breathOption && s6Breath) {
                    const isO2 = breathOption.value === 'o2';
                    if (isO2) {
                        const o2Select = document.getElementById('o2_type');
                        const o2Type = o2Select && o2Select.selectedIndex >= 0 ? o2Select.options[o2Select.selectedIndex].text : '';
                        const flowElement = document.getElementById('o2_flow_rate');
                        const flowRate = flowElement ? flowElement.value : '';
                        
                        let text = `ใช้ Oxygen: ${o2Type}`;
                        if (flowRate) text += ` (${flowRate} L/min)`;
                        s6Breath.value = text;
                    } else {
                        s6Breath.value = 'Room Air (ไม่ใช้ Oxygen)';
                    }
                }
            } catch (err) {
                console.error('Error synchronizing section 6 elements:', err);
            }
        }

        // Attach listeners to sync Section 6
        document.getElementById('consciousness').addEventListener('change', updateSection6Sync);
        document.getElementById('o2_type').addEventListener('change', function() {
            updateSection6Sync();
            updateO2DeviceDisplay();
        });
        document.getElementById('o2_flow_rate').addEventListener('input', updateSection6Sync);
        document.querySelectorAll('input[name="breath"]').forEach(el => el.addEventListener('change', updateSection6Sync));

        // Call once to initialize
        updateSection6Sync();

        // -------------------------------------------------------------
        // Section 6 Logic: Handoff Information
        // -------------------------------------------------------------

        function toggleGroup(checkboxId, targetId) {
            const isChecked = document.getElementById(checkboxId).checked;
            const targetDiv = document.getElementById(targetId);
            if (isChecked) {
                targetDiv.style.display = 'block';
            } else {
                targetDiv.style.display = 'none';
                // Find all inputs within targetDiv and clear or uncheck them
                const inputs = targetDiv.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else if (input.type === 'text') {
                        input.value = '';
                    }
                });
            }
        }

        function toggleRights() {
            const val = document.querySelector('input[name="rights_status"]:checked')?.value;
            const hasOptions = document.getElementById('rights_has_options');
            const noOptions = document.getElementById('rights_no_options');
            
            if (val === 'has_rights') {
                hasOptions.style.display = 'block';
                noOptions.style.display = 'none';
                document.getElementById('rights_no_select').value = '';
            } else if (val === 'no_rights') {
                hasOptions.style.display = 'none';
                noOptions.style.display = 'block';
                document.getElementById('rights_has_select').value = '';
            }
        }

        function toggleBillingOther() {
            const val = document.querySelector('input[name="billing_status"]:checked')?.value;
            const otherText = document.getElementById('billing_other_text');
            if (val === 'other') {
                otherText.style.display = 'block';
            } else {
                otherText.style.display = 'none';
                otherText.value = '';
            }
        }

        async function runSection6AI() {
            const aiBtn = document.querySelector('.ai-btn[onclick="runSection6AI()"]');
            const aiLoading = document.getElementById('ai-loading-sec6');
            const aiResult = document.getElementById('ai-result-sec6');

            // Gather IV Info
            let ivs = [];
            if (document.getElementById('iv_pls').checked) ivs.push('PLS lock');
            if (document.getElementById('iv_central_check').checked) {
                if (document.getElementById('iv_aline').checked) ivs.push('A-line');
                if (document.getElementById('iv_cline').checked) ivs.push('C-line');
                if (document.getElementById('iv_dlc').checked) ivs.push('DLC');
                if (document.getElementById('iv_perm').checked) ivs.push('Perm cath');
                if (document.getElementById('iv_other_check').checked) {
                    ivs.push(document.getElementById('iv_other_text').value || 'Central Line อื่นๆ');
                }
            }

            // Gather Tube Info
            let tubes = [];
            if (document.getElementById('tube_ng').checked) tubes.push('NG');
            if (document.getElementById('tube_icd').checked) tubes.push('ICD');
            if (document.getElementById('tube_fcath').checked) tubes.push('F-cath');
            if (document.getElementById('tube_wound').checked) tubes.push('wound drain');
            if (document.getElementById('tube_other_check').checked) {
                tubes.push(document.getElementById('tube_other_text').value || 'Tube อื่นๆ');
            }

            let formData = {
                form_mode: 'handoff',
                consciousness: document.getElementById('sec6_consciousness').value,
                breathing: document.getElementById('sec6_breathing').value,
                iv: ivs,
                tube: tubes,
                ostomy: document.getElementById('sec6_ostomy').checked,
                traction: document.getElementById('sec6_traction').checked,
                other_equip: document.getElementById('sec6_others').value,
                leftover_meds: document.getElementById('sec6_leftover_meds').value,
                problems: document.getElementById('sec6_problems').value
            };

            aiBtn.disabled = true;
            aiBtn.style.opacity = '0.7';
            aiLoading.style.display = 'block';
            aiResult.style.display = 'none';

            try {
                const response = await fetch('gemini_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.error) {
                    aiResult.innerHTML = `<strong>Error:</strong> ${data.error}`;
                } else {
                    const formattedText = data.result.replace(/\n/g, '<br>');
                    aiResult.innerHTML = `<strong>AI Analysis (Handoff):</strong><br><br>${formattedText}`;
                }

            } catch (err) {
                console.error(err);
                aiResult.innerHTML = `<strong>เกิดข้อผิดพลาดในการเชื่อมต่อ:</strong> ${err.message}`;
            } finally {
                aiBtn.disabled = false;
                aiBtn.style.opacity = '1';
                aiLoading.style.display = 'none';
                aiResult.style.display = 'block';
            }
        }
        
        // -------------------------------------------------------------
        // Section 7 Logic: Destination Reassessment
        // -------------------------------------------------------------
        
        const destInputs = document.querySelectorAll('.dest-calc');
        const destScoreValue = document.getElementById('dest-score-value');
        const destScoreBanner = document.getElementById('dest-score-banner');

        destInputs.forEach(input => {
            input.addEventListener('input', calculateDestScore);
            input.addEventListener('change', calculateDestScore);
        });

        function calculateDestScore() {
            const isChild = document.querySelector('input[name="age_group"]:checked').value === 'child';
            let totalScore = 0;

            if (isChild) {
                totalScore = calculateDestPEWS();
            } else {
                totalScore = calculateDestNEWS();
            }

            destScoreValue.innerText = totalScore;
            updateDestScoreColor(totalScore);
        }

        function calculateDestNEWS() {
            let score = 0;
            const rr = parseFloat(document.getElementById('dest_rr').value);
            const spo2 = parseFloat(document.getElementById('dest_spo2').value);
            const airo2 = document.getElementById('dest_airo2').value;
            const sbp = parseFloat(document.getElementById('dest_sbp').value);
            const pulse = parseFloat(document.getElementById('dest_pulse').value);
            const temp = parseFloat(document.getElementById('dest_temp').value);
            const consciousness = document.getElementById('dest_consciousness').value;
            const isCOPD = document.getElementById('dest_copd').checked;

            // RR
            if (!isNaN(rr)) {
                if (rr <= 8) score += 3;
                else if (rr >= 9 && rr <= 11) score += 1;
                else if (rr >= 12 && rr <= 20) score += 0;
                else if (rr >= 21 && rr <= 24) score += 2;
                else if (rr >= 25) score += 3;
            }

            // SpO2
            if (!isNaN(spo2)) {
                if (isCOPD) {
                    if (spo2 <= 83) score += 3;
                    else if (spo2 >= 84 && spo2 <= 85) score += 2;
                    else if (spo2 >= 86 && spo2 <= 87) score += 1;
                    else if (spo2 >= 88 && spo2 <= 92 && airo2 === 'room') score += 0;
                    else if (spo2 >= 93 && spo2 <= 94 && airo2 === 'oxygen') score += 1;
                    else if (spo2 >= 95 && spo2 <= 96 && airo2 === 'oxygen') score += 2;
                    else if (spo2 >= 97 && airo2 === 'oxygen') score += 3;
                } else {
                    if (spo2 <= 91) score += 3;
                    else if (spo2 >= 92 && spo2 <= 93) score += 2;
                    else if (spo2 >= 94 && spo2 <= 95) score += 1;
                    else if (spo2 >= 96) score += 0;
                }
            }

            // Air/O2
            if (airo2 === 'oxygen') score += 2;

            // SBP
            if (!isNaN(sbp)) {
                if (sbp <= 90) score += 3;
                else if (sbp >= 91 && sbp <= 100) score += 2;
                else if (sbp >= 101 && sbp <= 110) score += 1;
                else if (sbp >= 111 && sbp <= 219) score += 0;
                else if (sbp >= 220) score += 3;
            }

            // Pulse
            if (!isNaN(pulse)) {
                if (pulse <= 40) score += 3;
                else if (pulse >= 41 && pulse <= 50) score += 1;
                else if (pulse >= 51 && pulse <= 90) score += 0;
                else if (pulse >= 91 && pulse <= 110) score += 1;
                else if (pulse >= 111 && pulse <= 130) score += 2;
                else if (pulse >= 131) score += 3;
            }

            // Temp
            if (!isNaN(temp)) {
                if (temp <= 35.0) score += 3;
                else if (temp >= 35.1 && temp <= 36.0) score += 1;
                else if (temp >= 36.1 && temp <= 38.0) score += 0;
                else if (temp >= 38.1 && temp <= 39.0) score += 1;
                else if (temp >= 39.1) score += 2;
            }

            // Consciousness
            if (consciousness !== 'alert') score += 3;

            return score;
        }

        function calculateDestPEWS() {
            let score = 0;
            // Example PEWS logic based on limited parameters
            const isChild = true;
            const airo2 = document.getElementById('dest_airo2').value;
            const consciousness = document.getElementById('dest_consciousness').value;
            const effort = document.getElementById('dest_resp_effort').value;
            const hr = parseFloat(document.getElementById('dest_hr_peds').value);

            if (airo2 === 'oxygen') score += 2;

            if (consciousness === 'voice') score += 1;
            else if (consciousness === 'pain') score += 2;
            else if (consciousness === 'unresponsive') score += 3;

            if (effort === 'mild') score += 1;
            else if (effort === 'moderate') score += 2;
            else if (effort === 'severe') score += 3;

            if (!isNaN(hr)) {
                if (hr < 60 || hr > 160) score += 3;
                else if (hr > 140) score += 2;
                else if (hr > 120) score += 1;
            }

            return score;
        }

        function updateDestScoreColor(score) {
            destScoreBanner.className = 'score-banner'; // reset classes

            if (score === 0) {
                destScoreBanner.style.backgroundColor = 'var(--gray-border)';
                destScoreBanner.style.color = 'var(--text-main)';
            } else if (score >= 1 && score <= 4) {
                destScoreBanner.classList.add('score-low');
            } else if (score >= 5 && score <= 6) {
                destScoreBanner.classList.add('score-medium');
            } else if (score >= 7) {
                destScoreBanner.classList.add('score-high');
            }
        }
    </script>

    <script>
    function printForm() {
        // ---- Helpers ----
        function v(id) {
            const el = document.getElementById(id);
            if (!el) return '-';
            if (el.type === 'checkbox') return el.checked ? '✓' : '☐';
            return el.value || '-';
        }
        function radio(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            return el ? el.value : '-';
        }
        function radioLabel(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            if (!el) return '-';
            const lbl = el.closest('label') || el.parentElement;
            return lbl ? lbl.innerText.trim() : el.value;
        }
        function selectText(id) {
            const el = document.getElementById(id);
            if (!el || el.selectedIndex < 0) return '-';
            const opt = el.options[el.selectedIndex];
            return opt && opt.text ? opt.text.replace('-- ', '').replace(' --', '') : '-';
        }
        function chk(id) { const el = document.getElementById(id); return el && el.checked; }
        function txt(id) { const el = document.getElementById(id); return el ? (el.value || '') : ''; }

        // ---- Gather Data ----
        const isChild = radio('age_group') === 'child';
        const scoreType = isChild ? 'PEWS' : 'NEWS';
        const scoreLive = txt('score-value') || document.getElementById('score-value')?.innerText || '0';
        const destScoreLive = txt('dest-score-value') || document.getElementById('dest-score-value')?.innerText || '0';

        const breath = radio('breath');
        const isO2 = breath === 'o2';
        const o2TypeVal = document.getElementById('o2_type')?.value || '';
        const o2TypeNames = {cannula:'Cannula', mask_c_bag:'Mask c Bag', hfnc:'HFNC', niv:'NIV', ventilator:'Ventilator'};
        const o2TypeText = isO2 ? (o2TypeNames[o2TypeVal] || 'ระบุชนิด') : 'Room Air';

        // O2 duration from banner
        const o2DurationEl = document.getElementById('o2-time-result');
        const o2Duration = o2DurationEl && document.getElementById('o2-result-banner')?.style.display !== 'none' ? o2DurationEl.innerText : '-';

        let flowText = '-';
        if (isO2) {
            if (o2TypeVal === 'hfnc') flowText = 'HFNC ' + txt('hfnc_flow') + ' L/min';
            else if (o2TypeVal === 'niv') flowText = 'NIV ' + txt('niv_total_flow') + ' L/min';
            else if (o2TypeVal === 'ventilator') flowText = 'Vent TV ' + txt('vent_tv') + 'mL';
            else flowText = txt('o2_flow_rate') + (txt('o2_flow_rate') ? ' L/min' : '');
        }

        // Severity result
        const severityEl = document.querySelector('#severity-result h3');
        const severityText = severityEl ? severityEl.innerText : '-';

        // Personnel
        let personnel = [];
        if (chk('pers_doctor')) personnel.push('แพทย์' + (txt('name_doctor') ? ` (${txt('name_doctor')})` : ''));
        if (chk('pers_rn'))     personnel.push('พยาบาล' + (txt('name_rn') ? ` (${txt('name_rn')})` : ''));
        if (chk('pers_pn'))     personnel.push('ผู้ช่วยพยาบาล' + (txt('name_pn') ? ` (${txt('name_pn')})` : ''));
        if (chk('pers_aide'))   personnel.push('ผู้ช่วยเหลือคนไข้' + (txt('name_aide') ? ` (${txt('name_aide')})` : ''));

        // Checklist
        const chklist = [];
        if (chk('chk_identify'))     chklist.push('✓ คำสั่งการรักษา');
        if (chk('chk_abc'))          chklist.push('✓ ABCDE');
        if (chk('eq_emergency_bag')) chklist.push('✓ กระเป๋าฉุกเฉิน');
        if (chk('eq_infusion'))      chklist.push('✓ Infusion pump');
        if (chk('eq_ventilator'))    chklist.push('✓ Mobile ventilator');
        if (chk('eq_ekg'))           chklist.push('✓ EKG/SpO2');
        if (chk('eq_records'))       chklist.push('✓ ประวัติการรักษา');

        // Risks
        const risks = [];
        if (chk('risk_suicide')) risks.push('หนี/ฆ่าตัวตาย');
        if (chk('risk_mdrs'))    risks.push('MDRS');
        if (chk('risk_child'))   risks.push('เด็ก/ทารก');
        if (chk('risk_other_check') && txt('risk_other_text')) risks.push(txt('risk_other_text'));

        // Sec6 IV & Tubes
        const ivs = [];
        if (chk('iv_pls'))   ivs.push('PLS lock');
        if (chk('iv_aline')) ivs.push('A-line');
        if (chk('iv_cline')) ivs.push('C-line');
        if (chk('iv_dlc'))   ivs.push('DLC');
        if (chk('iv_perm'))  ivs.push('Perm cath');
        if (chk('iv_other_check') && txt('iv_other_text')) ivs.push(txt('iv_other_text'));
        const tubes = [];
        if (chk('tube_ng'))    tubes.push('NG');
        if (chk('tube_icd'))   tubes.push('ICD');
        if (chk('tube_fcath')) tubes.push('F-cath');
        if (chk('tube_wound')) tubes.push('Wound drain');
        if (chk('tube_other_check') && txt('tube_other_text')) tubes.push(txt('tube_other_text'));

        // Billing / Rights
        const billStatus = radioLabel('billing_status');
        const rightsStatus = radio('rights_status');
        let rightsText = '-';
        if (rightsStatus === 'has_rights') rightsText = selectText('rights_has_select');
        else if (rightsStatus === 'no_rights') rightsText = selectText('rights_no_select');

        const now = new Date();
        const dateStr = now.toLocaleDateString('th-TH', {day:'2-digit', month:'2-digit', year:'numeric'});
        const timeStr = now.toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit'});

        // Score color
        const scoreNum = parseInt(scoreLive) || 0;
        const scoreColor = scoreNum >= 7 ? '#b91c1c' : scoreNum >= 5 ? '#b45309' : scoreNum > 0 ? '#047857' : '#1f2937';
        const scoreBg   = scoreNum >= 7 ? '#fee2e2' : scoreNum >= 5 ? '#fef3c7' : scoreNum > 0 ? '#d1fae5' : '#eff6ff';

        // ---- Build HTML ----
        const html = `<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Safety Transfer Summary</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
  body { background: white; color: #1f2937; font-size: 8.5pt; }
  @page { size: A4 portrait; margin: 8mm 8mm 8mm 8mm; }
  @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } .no-print { display: none !important; } }
  h1 { font-size: 11pt; text-align: center; color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 3px; margin-bottom: 6px; }
  .meta { display: flex; justify-content: space-between; font-size: 7.5pt; color: #6b7280; margin-bottom: 6px; }
  .grid { display: grid; gap: 4px; margin-bottom: 6px; }
  .g2 { grid-template-columns: 1fr 1fr; }
  .g3 { grid-template-columns: 1fr 1fr 1fr; }
  .g4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
  .box { border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 6px; background: #f9fafb; }
  .box-blue { background: #eff6ff; border-color: #bfdbfe; }
  .box-green { background: #ecfdf5; border-color: #a7f3d0; }
  .section { margin-bottom: 5px; }
  .sec-title { font-size: 8pt; font-weight: 700; color: #1e40af; background: #eff6ff; padding: 2px 6px; border-radius: 3px; margin-bottom: 3px; border-left: 3px solid #3b82f6; }
  .lbl { font-size: 7pt; color: #6b7280; font-weight: 600; display: block; margin-bottom: 1px; }
  .val { font-size: 8.5pt; font-weight: 500; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 9pt; }
  .score-box { text-align: center; border-radius: 5px; padding: 5px; }
  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 4px 0; }
  .tag { display: inline-block; background: #e0f2fe; color: #0369a1; font-size: 7pt; border-radius: 3px; padding: 1px 5px; margin: 1px; }
  .tag-red { background: #fee2e2; color: #b91c1c; }
  .tag-green { background: #d1fae5; color: #047857; }
  .chk-row { display: flex; flex-wrap: wrap; gap: 4px; }
  .print-btn { display: block; margin: 8px auto; padding: 8px 24px; background: #15803d; color: white; border: none; border-radius: 6px; font-size: 10pt; cursor: pointer; }
  .footer { text-align: center; color: #9ca3af; font-size: 6.5pt; margin-top: 4px; border-top: 1px solid #e5e7eb; padding-top: 3px; }
</style>
</head>
<body>
  <button class="print-btn no-print" onclick="window.print()">🖨️ พิมพ์</button>

  <h1>📋 Safety Transfer Summary — ใบเคลื่อนย้ายผู้ป่วย</h1>
  <div class="meta"><span>วันที่/เวลา: ${dateStr} ${timeStr}</span><span>เวลาออก: ${txt('log_time_out') || '-'} → เวลาถึง: ${txt('log_time_in') || '-'}</span></div>

  <!-- Row 1: Patient info + Score -->
  <div class="section">
    <div class="sec-title">ส่วนที่ 1 — ข้อมูลผู้ป่วย & ${scoreType} Score</div>
    <div class="grid g4">
      <div class="box"><span class="lbl">HN</span><span class="val">${txt('hn') || (() => { const el = document.querySelector('input[placeholder="ระบุ HN"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">ชื่อ</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุชื่อผู้ป่วย"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">Ward</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุวอร์ด"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">ปลายทาง</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุปลายทาง"]'); return el ? el.value || '-' : '-'; })()}</span></div>
    </div>
    <div class="grid g4">
      <div class="box"><span class="lbl">RR (ครั้ง/นาที)</span><span class="val">${txt('rr')}</span></div>
      <div class="box"><span class="lbl">SpO2 (%)</span><span class="val">${txt('spo2')}</span></div>
      <div class="box"><span class="lbl">SBP / Pulse</span><span class="val">${txt('sbp')} / ${txt('pulse')}</span></div>
      <div class="box"><span class="lbl">Temp (°C)</span><span class="val">${txt('temp')}</span></div>
    </div>
    <div class="grid g3">
      <div class="box"><span class="lbl">Air/O2</span><span class="val">${selectText('airo2')}</span></div>
      <div class="box"><span class="lbl">Consciousness</span><span class="val">${selectText('consciousness')}</span></div>
      <div class="score-box" style="background:${scoreBg}; border: 1px solid ${scoreColor}40;">
        <span class="lbl" style="color:${scoreColor}">${scoreType} Score (ต้นทาง)</span>
        <span class="badge" style="background:${scoreColor}; color:white; font-size:13pt;">${scoreLive}</span>
      </div>
    </div>
  </div>

  <!-- Row 2: Severity + O2 -->
  <div class="grid g2" style="margin-bottom:5px;">
    <div class="section">
      <div class="sec-title">ส่วนที่ 2 — ระดับความรุนแรง</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px;">
        <div class="box"><span class="lbl">สื่อสาร</span><span class="val">${radioLabel('comm')}</span></div>
        <div class="box"><span class="lbl">การหายใจ</span><span class="val">${o2TypeText}</span></div>
        <div class="box"><span class="lbl">สัญญาณชีพ</span><span class="val">${radioLabel('vitals')}</span></div>
        <div class="box"><span class="lbl">Sedation</span><span class="val">${radioLabel('sedation')}</span></div>
        <div class="box"><span class="lbl">CTAS</span><span class="val">${selectText('ctas')}</span></div>
        <div class="box"><span class="lbl">ความเสี่ยง</span><span class="val">${risks.length ? risks.join(', ') : 'ไม่มี'}</span></div>
      </div>
      ${severityText !== '-' ? `<div class="box box-blue" style="margin-top:3px; text-align:center;"><strong>${severityText}</strong></div>` : ''}
    </div>

    <div class="section">
      <div class="sec-title">ส่วนที่ 4 — O2 ที่เหลือในถัง</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px;">
        <div class="box"><span class="lbl">อุปกรณ์ O2</span><span class="val">${o2TypeText}</span></div>
        <div class="box"><span class="lbl">ขนาดถัง</span><span class="val">${selectText('o2_cylinder_size')}</span></div>
        <div class="box"><span class="lbl">แรงดัน (PSI)</span><span class="val">${txt('o2_pressure')}</span></div>
        <div class="box"><span class="lbl">Flow rate</span><span class="val">${flowText}</span></div>
      </div>
      <div class="score-box box-green" style="margin-top:3px; padding:4px;">
        <span class="lbl" style="color:#047857;">ระยะเวลา O2 ที่ใช้ได้</span>
        <span style="font-size:12pt; font-weight:800; color:#047857;">${o2Duration}</span>
      </div>
    </div>
  </div>

  <!-- Row 3: Checklist + Personnel -->
  <div class="grid g2" style="margin-bottom:5px;">
    <div class="section">
      <div class="sec-title">ส่วนที่ 3 — รายการตรวจสอบ</div>
      <div class="chk-row">
        ${chklist.length ? chklist.map(c => `<span class="tag tag-green">${c}</span>`).join('') : '<span style="color:#9ca3af;">-</span>'}
      </div>
    </div>
    <div class="section">
      <div class="sec-title">บุคลากรร่วมเดินทาง</div>
      <div class="chk-row">
        ${personnel.length ? personnel.map(p => `<span class="tag">${p}</span>`).join('') : '<span style="color:#9ca3af;">-</span>'}
      </div>
    </div>
  </div>

  <!-- Row 4: Handoff -->
  <div class="section">
    <div class="sec-title">ส่วนที่ 6 — Handoff Information (ย้ายหอผู้ป่วย)</div>
    <div class="grid g4" style="margin-bottom:3px;">
      <div class="box"><span class="lbl">Consciousness</span><span class="val">${txt('sec6_consciousness')}</span></div>
      <div class="box"><span class="lbl">การหายใจ/O2</span><span class="val">${txt('sec6_breathing')}</span></div>
      <div class="box"><span class="lbl">IV / Central</span><span class="val">${ivs.length ? ivs.join(', ') : 'ไม่มี'}</span></div>
      <div class="box"><span class="lbl">Tube / สายระบาย</span><span class="val">${tubes.length ? tubes.join(', ') : 'ไม่มี'}</span></div>
    </div>
    <div class="grid g4">
      <div class="box"><span class="lbl">Ostomy</span><span class="val">${chk('sec6_ostomy') ? '✓ มี' : 'ไม่มี'}</span></div>
      <div class="box"><span class="lbl">Traction</span><span class="val">${chk('sec6_traction') ? '✓ มี' : 'ไม่มี'}</span></div>
      <div class="box"><span class="lbl">สิทธิ์</span><span class="val">${rightsText}</span></div>
      <div class="box"><span class="lbl">ค่ารักษา</span><span class="val">${billStatus}</span></div>
    </div>
    ${txt('sec6_leftover_meds') !== '-' && txt('sec6_leftover_meds') ? `<div class="box" style="margin-top:3px;"><span class="lbl">ยา/อุปกรณ์ค้างคืน</span><span class="val">${txt('sec6_leftover_meds')}</span></div>` : ''}
    ${txt('sec6_problems') !== '-' && txt('sec6_problems') ? `<div class="box" style="margin-top:3px;"><span class="lbl">ปัญหา/ความต้องการ</span><span class="val">${txt('sec6_problems')}</span></div>` : ''}
  </div>

  <!-- Row 5: Destination Reassessment + Transfer Log -->
  <div class="grid g2" style="margin-bottom:5px;">
    <div class="section">
      <div class="sec-title">ส่วนที่ 7 — ประเมินซ้ำปลายทาง</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px;">
        <div class="box"><span class="lbl">RR</span><span class="val">${txt('dest_rr')}</span></div>
        <div class="box"><span class="lbl">SpO2</span><span class="val">${txt('dest_spo2')}</span></div>
        <div class="box"><span class="lbl">SBP/Pulse</span><span class="val">${txt('dest_sbp')}/${txt('dest_pulse')}</span></div>
        <div class="box"><span class="lbl">Temp</span><span class="val">${txt('dest_temp')}</span></div>
        <div class="box"><span class="lbl">Consciousness</span><span class="val">${selectText('dest_consciousness')}</span></div>
        <div class="score-box" style="background:${scoreBg}; border:1px solid ${scoreColor}40; padding:3px;">
          <span class="lbl" style="color:${scoreColor}">${scoreType} ปลายทาง</span>
          <span class="badge" style="background:${scoreColor}; color:white;">${destScoreLive}</span>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="sec-title">ส่วนที่ 5 — Transfer Log</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px;">
        <div class="box"><span class="lbl">เวลาส่งออก</span><span class="val">${txt('log_time_out')}</span></div>
        <div class="box"><span class="lbl">ผู้ส่ง</span><span class="val">${txt('log_sender')}</span></div>
        <div class="box"><span class="lbl">เวลาถึงปลายทาง</span><span class="val">${txt('log_time_in')}</span></div>
        <div class="box"><span class="lbl">ผู้รับ</span><span class="val">${txt('log_receiver')}</span></div>
      </div>
    </div>
  </div>

  <div class="footer">Safety Transfer Form — พิมพ์เมื่อ: ${dateStr} ${timeStr}</div>
  <button class="print-btn no-print" onclick="window.print()">🖨️ พิมพ์</button>
</body></html>`;

        const win = window.open('', '_blank', 'width=800,height=900');
        win.document.write(html);
        win.document.close();
        win.focus();
    }
    </script>
</body>
</html>