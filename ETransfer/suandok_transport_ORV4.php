<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Assessment & Early Warning Score</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
          --med-50:#eff8ff;--med-100:#dbeffe;--med-200:#bfe3fe;--med-400:#60b3fd;
          --med-500:#3b93fa;--med-600:#2573ef;--med-700:#1d5ed8;--med-800:#1e4db0;
          --surface:#f0f6ff;--card:#ffffff;--border:#dbeffe;
          --text:#0f2149;--muted:#5a7a9f;
          --green-bg:#ecfdf5;--green-border:#6ee7b7;--green-text:#065f46;
          --orange-bg:#fff7ed;--orange-border:#fdba74;--orange-text:#9a3412;
          --red-bg:#fef2f2;--red-border:#fca5a5;--red-text:#991b1b;
          --radius:12px;--radius-lg:16px;--radius-xl:20px;
          --shadow:0 1px 3px rgba(30,78,175,.08),0 4px 16px rgba(30,78,175,.06);
          --shadow-hover:0 16px 48px rgba(30,78,175,.16);
          --transition:all .2s ease;
          /* legacy compat */
          --primary:#2573ef;--primary-light:#eff8ff;--primary-hover:#1d5ed8;
          --text-main:#0f2149;--text-muted:#5a7a9f;--border-color:#dbeffe;
          --bg-body:#f0f6ff;--bg-card:#fff;--danger:#ef4444;--warning:#f59e0b;
          --success:#10b981;--radius-md:12px;
          --shadow-sm:0 1px 2px rgba(0,0,0,.05);
          --shadow-md:0 4px 16px rgba(30,78,175,.1);
        }
        *{box-sizing:border-box;margin:0;padding:0;font-family:'IBM Plex Sans Thai','Inter',sans-serif;}
        body{background:var(--surface);color:var(--text);min-height:100vh;padding-bottom:4rem;}

        /* HERO */
        .hero{background:linear-gradient(135deg,#1e3f8f 0%,#2573ef 60%,#1d5ed8 100%);padding:2.5rem 1rem 4rem;text-align:center;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 20% 50%,rgba(255,255,255,.07) 0%,transparent 60%),radial-gradient(circle at 80% 20%,rgba(255,255,255,.05) 0%,transparent 50%);}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.25);border-radius:100px;padding:.4rem 1.2rem;font-size:.8rem;color:#bfe3fe;font-weight:500;margin-bottom:1.25rem;letter-spacing:.05em;}
        .hero h1{font-size:clamp(1.4rem,4vw,2.1rem);font-weight:800;color:#fff;line-height:1.25;margin-bottom:.75rem;}
        .hero h1 .grad{background:linear-gradient(90deg,#93ceff,#60b3fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
        .hero-sub{color:rgba(255,255,255,.75);font-size:.9rem;max-width:500px;margin:0 auto;}

        /* STEPPER */
        .stepper-wrap{background:#fff;border-bottom:1px solid var(--border);position:-webkit-sticky;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(30,78,175,.07);}
        .stepper{display:flex;justify-content:center;width:100%;max-width:100%;margin:0 auto;padding:0 1.5rem;overflow-x:auto;scrollbar-width:none;gap:0.25rem;}
        @media(max-width:1024px){
          .stepper{justify-content:flex-start;}
        }
        .stepper::-webkit-scrollbar{display:none;}
        .step-item{display:flex;flex-direction:column;align-items:center;gap:.35rem;padding:1rem 1.75rem;cursor:pointer;border-bottom:3px solid transparent;transition:var(--transition);min-width:110px;flex:1;}
        .step-item:hover{background:#f0f6ff;}
        .step-item.active{border-bottom-color:var(--med-600);}
        .step-dot{width:38px;height:38px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.05rem;font-weight:700;color:var(--muted);background:#fff;transition:var(--transition);}
        .step-item.active .step-dot{background:var(--med-600);border-color:var(--med-600);color:#fff;box-shadow:0 3px 10px rgba(37,115,239,.35);}
        .step-item.done .step-dot{background:var(--med-100);border-color:var(--med-400);color:var(--med-700);}
        .step-label{font-size:.85rem;font-weight:600;color:var(--muted);text-align:center;line-height:1.3;white-space:nowrap;}
        .step-item.active .step-label{color:var(--med-700);}

        /* CONTAINER & CARDS */
        .container{max-width:780px;margin:0 auto;padding:2rem 1rem;}
        .card{background:var(--card);border-radius:var(--radius-xl);box-shadow:var(--shadow);border:1px solid var(--border);padding:2rem;margin-bottom:1.75rem;transition:box-shadow .25s;}
        .card:hover{box-shadow:var(--shadow-hover);}

        /* HEADER */
        .header{display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);}
        .step-badge{background:linear-gradient(135deg,var(--med-600),var(--med-800));color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;flex-shrink:0;box-shadow:0 3px 10px rgba(37,115,239,.35);}
        h2{font-size:1.1rem;color:var(--text);font-weight:700;}
        h3{font-size:1rem;color:var(--text);font-weight:700;}

        /* GRID & FORM */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;}
        .grid-1{margin-bottom:1.25rem;}
        .form-group{display:flex;flex-direction:column;gap:.5rem;}
        label{font-size:.82rem;font-weight:600;color:var(--muted);display:flex;align-items:center;gap:.4rem;}
        label svg{width:15px;height:15px;color:var(--muted);flex-shrink:0;}
        input[type=text],input[type=number],input[type=time],select,textarea{
          width:100%;padding:.8rem 1rem;border:2px solid var(--border);border-radius:var(--radius);
          font-size:.93rem;background:#fafcff;color:var(--text);outline:none;transition:var(--transition);
          font-family:'IBM Plex Sans Thai','Inter',sans-serif;}
        input[type=text]:focus,input[type=number]:focus,select:focus,textarea:focus{
          border-color:var(--med-500);background:#fff;box-shadow:0 0 0 4px rgba(37,115,239,.1);}
        hr{border:none;border-top:1px solid var(--border);margin:1.5rem 0;}
        textarea{resize:vertical;}
        small.hint{font-size:.73rem;color:var(--muted);margin-top:.2rem;display:block;}

        /* CARD-STYLE RADIO & CHECKBOX */
        .radio-group{display:flex;flex-wrap:wrap;gap:.6rem;margin-top:.35rem;}
        .radio-label{display:flex;align-items:center;gap:.5rem;padding:.6rem 1rem;border:2px solid var(--border);border-radius:10px;cursor:pointer;font-size:.875rem;font-weight:500;color:var(--text);background:#fafcff;transition:var(--transition);user-select:none;}
        .radio-label:hover{border-color:var(--med-400);background:var(--med-50);transform:translateY(-1px);}
        input[type=radio]{accent-color:var(--med-600);width:16px;height:16px;cursor:pointer;flex-shrink:0;}
        input[type=radio]:checked+span,.radio-label:has(input:checked){border-color:var(--med-600)!important;background:var(--med-50)!important;color:var(--med-700)!important;font-weight:600!important;box-shadow:0 0 0 3px rgba(37,115,239,.1)!important;}

        .checkbox-group{display:flex;flex-direction:column;gap:.5rem;margin-top:.35rem;}
        .checkbox-label{display:flex;align-items:center;gap:.6rem;padding:.65rem 1rem;border:2px solid var(--border);border-radius:10px;cursor:pointer;font-size:.875rem;font-weight:500;color:var(--text);background:#fafcff;transition:var(--transition);user-select:none;}
        .checkbox-label:hover{border-color:var(--med-400);background:var(--med-50);}
        input[type=checkbox]{accent-color:var(--med-600);width:17px;height:17px;cursor:pointer;flex-shrink:0;}
        .checkbox-label:has(input:checked){border-color:var(--med-500);background:var(--med-50);color:var(--med-800);font-weight:600;}

        /* SCORE BANNER */
        .score-banner{display:flex;align-items:center;justify-content:center;gap:1.5rem;padding:1.5rem;border-radius:var(--radius-lg);margin-top:1.5rem;transition:all .4s;border:2px solid;position:relative;overflow:hidden;font-weight:700;}
        .score-banner.score-low,.score-banner.low{background:var(--green-bg);border-color:var(--green-border);color:var(--green-text);}
        .score-banner.score-medium,.score-banner.medium{background:var(--orange-bg);border-color:var(--orange-border);color:var(--orange-text);}
        .score-banner.score-high,.score-banner.high{background:var(--red-bg);border-color:var(--red-border);color:var(--red-text);}
        #score-label,#dest-score-label{font-size:1rem;font-weight:600;}
        #score-value,#dest-score-value{font-size:2.8rem;font-weight:800;line-height:1;}

        /* SEVERITY RESULT */
        .result-box{padding:1.25rem 1.5rem;border-radius:var(--radius-lg);margin-bottom:1rem;border:2px solid;}
        .result-box h3{margin-bottom:.6rem;font-size:1rem;}
        .result-box ul{margin-left:1.5rem;margin-top:.5rem;}
        .result-box li{margin-bottom:.35rem;font-size:.875rem;}

        /* BUTTONS */
        .evaluate-btn{background:linear-gradient(135deg,var(--med-600),var(--med-800));color:#fff;border:none;padding:.9rem 1.75rem;border-radius:var(--radius);font-size:.95rem;font-weight:600;cursor:pointer;width:100%;transition:var(--transition);font-family:'IBM Plex Sans Thai','Inter',sans-serif;pointer-events:auto;}
        .evaluate-btn:hover{box-shadow:0 8px 24px rgba(37,115,239,.4);transform:translateY(-2px);}
        .ai-btn{background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;border:none;padding:.9rem 1.75rem;border-radius:var(--radius);font-size:.95rem;font-weight:600;cursor:pointer;width:100%;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:.5rem;font-family:'IBM Plex Sans Thai','Inter',sans-serif;pointer-events:auto;}
        .ai-btn:hover{box-shadow:0 8px 24px rgba(124,58,237,.35);transform:translateY(-2px);}

        /* Locked Card Styling */
        .card.locked input,
        .card.locked select,
        .card.locked textarea,
        .card.locked .radio-label,
        .card.locked .checkbox-label {
            pointer-events: none !important;
            cursor: not-allowed !important;
        }
        .card.locked .evaluate-btn,
        .card.locked .ai-btn {
            pointer-events: auto !important;
            cursor: pointer !important;
        }

        /* SUB PANEL */
        .sub-expand{background:var(--med-50);border:1.5px solid var(--border);border-radius:var(--radius);padding:1rem;margin-top:.6rem;}
        .info-box{background:var(--med-50);border:1.5px solid var(--med-200);border-radius:var(--radius);padding:1rem;font-size:.875rem;color:var(--med-700);}

        /* WARNING */
        #personnel-warning{border-radius:var(--radius);}

        /* AI */
        .ai-result-box{border-radius:var(--radius);padding:1.25rem;border:1.5px solid #c4b5fd;background:#faf5ff;color:#4c1d95;font-size:.9rem;line-height:1.65;}
        .spinner-inline{display:inline-block;width:18px;height:18px;border:3px solid var(--med-200);border-top-color:var(--med-600);border-radius:50%;animation:spin .8s linear infinite;vertical-align:middle;margin-right:.4rem;}
        @keyframes spin{to{transform:rotate(360deg);}}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-6px);}to{opacity:1;transform:translateY(0);}}
        @keyframes fadeOut{from{opacity:1;transform:translateY(0);}to{opacity:0;transform:translateY(-6px);}}

        /* PEDIATRIC */
        .adult-field{display:block;}
        .pediatric-field{display:none;}

        /* O2 */
        #o2-result-banner{border-radius:var(--radius-lg)!important;border:2px solid var(--green-border)!important;}
        #o2-time-result{font-size:2.25rem!important;font-weight:800!important;}

        /* ===== SEARCH SELECT ===== */
        .search-select-container {
            position: relative;
            width: 100%;
        }

        .search-select-container .arrow-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: transform 0.2s;
            width: 12px;
            height: 12px;
        }

        .search-select-container.open .arrow-icon {
            transform: translateY(-50%) rotate(180deg);
        }

        .search-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-hover);
            max-height: 250px;
            overflow-y: auto;
            display: none;
            margin-top: 4px;
        }

        .search-select-dropdown.open {
            display: block;
        }

        .search-select-group-header {
            background: #f0f6ff;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            border-top: 1px solid var(--border);
        }
        .search-select-group-header:first-child {
            border-top: none;
        }

        .search-select-option {
            padding: 10px 12px;
            font-size: 14px;
            color: var(--text);
            cursor: pointer;
            transition: background 0.15s;
        }

        .search-select-option:hover,
        .search-select-option.highlighted {
            background: var(--primary-light);
            color: var(--primary-hover);
        }

        .search-select-option.selected {
            background: var(--primary);
            color: white;
        }

        /* RESPONSIVE */
        @media(max-width:600px){
          .grid-2{grid-template-columns:1fr;}
          .stepper{gap:0;}
          .step-item{min-width:70px;padding:.5rem .4rem;}
          .step-label{font-size:.58rem;}
          .card{padding:1.25rem;}
        }
        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 33, 73, 0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.show { opacity: 1; }
        .modal-content { background: var(--card); width: 100%; max-width: 700px; border-radius: var(--radius-xl); box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: scale(0.95); transition: transform 0.3s ease; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; }
        .modal-overlay.show .modal-content { transform: scale(1); }
        .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to right, #fee2e2, #fef2f2); border-top-left-radius: var(--radius-xl); border-top-right-radius: var(--radius-xl); }
        .modal-header h2 { color: var(--red-text); font-size: 1.2rem; display: flex; align-items: center; gap: 0.5rem; margin: 0; }
        .modal-close { background: none; border: none; font-size: 1.5rem; color: var(--muted); cursor: pointer; line-height: 1; }
        .modal-body { padding: 1.5rem; overflow-y: auto; font-size: 0.95rem; line-height: 1.6; color: var(--text); }
        .modal-body ol, .modal-body ul { margin-left: 1.5rem; margin-bottom: 1rem; }
        .modal-body li { margin-bottom: 0.5rem; }
        .urgent-btn { background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; border: none; padding: 1rem; border-radius: var(--radius); font-size: 1.1rem; font-weight: 700; cursor: pointer; width: 100%; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .urgent-btn:hover { box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4); transform: translateY(-2px); }

        @media print{.hero,.stepper-wrap,.evaluate-btn,.ai-btn,.modal-overlay,.urgent-btn{display:none!important;}}
    </style>
</head>

<body>











    <!-- HERO -->
    <div class="hero">
        <div class="hero-badge">🏥 Patient Safety Transfer System</div>
        <h1>ระบบประเมินความปลอดภัย<br><span class="grad">การเคลื่อนย้ายผู้ป่วย</span></h1>
        <p class="hero-sub">NEWS / PEWS Score · ระดับความรุนแรง · Pre-transfer Checklist · Oxygen Calculator · AI assessment </p>
    </div>

    <!-- STEPPER -->
    <div class="stepper-wrap">
        <div class="stepper">
            <div class="step-item active" id="nav-1" onclick="goToSection(1)">
                <div class="step-dot">1</div>
                <div class="step-label">ข้อมูลผู้ป่วย<br>& NEWS</div>
            </div>
            <div class="step-item" id="nav-2" onclick="goToSection(2)">
                <div class="step-dot">2</div>
                <div class="step-label">ระดับ<br>ความรุนแรง</div>
            </div>
            <div class="step-item" id="nav-3" onclick="goToSection(3)">
                <div class="step-dot">3</div>
                <div class="step-label">Pre-transfer<br>Checklist</div>
            </div>
            <div class="step-item" id="nav-4" onclick="goToSection(4)">
                <div class="step-dot">4</div>
                <div class="step-label">Oxygen<br>Calculator</div>
            </div>
            <div class="step-item" id="nav-5" onclick="goToSection(5)">
                <div class="step-dot">5</div>
                <div class="step-label">Handoff<br>Info</div>
            </div>
            <div class="step-item" id="nav-wp" onclick="goToSection('wp')">
                <div class="step-dot">📍</div>
                <div class="step-label">Waypoint<br>Assessment</div>
            </div>
            <div class="step-item" id="nav-6" onclick="goToSection(6)">
                <div class="step-dot">6</div>
                <div class="step-label">ประเมินซ้ำ<br>ปลายทาง</div>
            </div>
            <div class="step-item" id="nav-7" onclick="goToSection(7)">
                <div class="step-dot">7</div>
                <div class="step-label">Transfer<br>Log</div>
            </div>
            <div style="display: flex; align-items: center; padding-left: 1rem; padding-right: 0.5rem; border-left: 1px solid var(--border); margin-left: 0.5rem; flex-shrink: 0;">
                <button type="button" onclick="openUrgentModal()" class="urgent-btn" style="margin-top: 0; padding: 0.6rem 1.2rem; font-size: 0.9rem; width: auto; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);">
                    🚨 OR ด่วนที่สุด
                </button>
            </div>
        </div>
    </div>

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
                        <input type="text" id="hn" placeholder="ระบุ HN">
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
                            ชื่อผู้ป่วย
                        </label>
                        <input type="text" id="patient_name" placeholder="ระบุชื่อผู้ป่วย">
                    </div>
                    <div class="form-group">
                        <label>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            หน่วยงานต้นทาง
                        </label>
                        <div class="search-select-container" id="wardContainer">
                            <input type="text" id="ward" placeholder="ระบุหน่วยงานต้นทาง" autocomplete="off" style="padding-right: 34px;">
                            <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#94A3B8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4l4 4 4-4"/></svg>
                            <div class="search-select-dropdown" id="wardDropdown"></div>
                        </div>
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
                            หน่วยงานปลายทาง
                        </label>
                        <div class="search-select-container" id="destinationContainer">
                            <input type="text" id="destination" placeholder="ระบุหน่วยงานปลายทาง" autocomplete="off" style="padding-right: 34px;">
                            <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#94A3B8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4l4 4 4-4"/></svg>
                            <div class="search-select-dropdown" id="destinationDropdown"></div>
                        </div>
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
                    <div style="font-size:2.8rem;font-weight:800;line-height:1;min-width:3rem;text-align:center;" id="score-value">0</div>
                    <div style="display:flex;flex-direction:column;gap:.3rem;">
                        <span style="font-size:.75rem;font-weight:600;opacity:.75;letter-spacing:.06em;text-transform:uppercase;" id="score-label">NEWS Score</span>
                        <span style="font-size:.95rem;font-weight:700;" id="score-risk-label">ปกติ</span>
                        <span style="font-size:.75rem;opacity:.7;">กรอกข้อมูล Vital Signs เพื่อคำนวณ</span>
                    </div>
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

            <button type="button" onclick="evaluateTransportSeverity()" class="evaluate-btn" data-keep-enabled="true">
                ประเมินผลระดับความรุนแรง
            </button>

            <div id="severity-result" style="display: none; margin-top: 1.5rem;">
                <!-- Result dynamically injected here -->
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
                    <label>3.1 การประเมิน Tube / Drain</label>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; margin-bottom: 1rem; margin-left:1rem;">
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_tube_ng" class="calc-input"> NG/OG
                        </label>
                        
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_tube_peg" class="calc-input"> PEG
                        </label>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="chk_tube_icd" class="calc-input" onchange="document.getElementById('icd_side_group').style.display = this.checked ? 'block' : 'none';"> ICD
                            </label>
                            <div id="icd_side_group" style="display: none; margin-left: 1.5rem;">
                                <select id="val_tube_icd" class="calc-input" style="width: auto; margin-top: 0.25rem;">
                                    <option value="">-- ระบุข้าง --</option>
                                    <option value="ซ้าย">ซ้าย</option>
                                    <option value="ขวา">ขวา</option>
                                    <option value="ซ้ายและขวา">ทั้ง 2 ข้าง</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="chk_tube_redivac" class="calc-input" onchange="document.getElementById('redivac_group').style.display = this.checked ? 'block' : 'none';"> Redivac
                            </label>
                            <div id="redivac_group" style="display: none; margin-left: 1.5rem;">
                                <input type="text" id="val_tube_redivac" class="calc-input" placeholder="ระบุบริเวณ" style="margin-top: 0.25rem;">
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="chk_tube_pcn" class="calc-input" onchange="document.getElementById('pcn_group').style.display = this.checked ? 'block' : 'none';"> PCN
                            </label>
                            <div id="pcn_group" style="display: none; margin-left: 1.5rem;">
                                <input type="text" id="val_tube_pcn" class="calc-input" placeholder="ระบุบริเวณ" style="margin-top: 0.25rem;">
                            </div>
                        </div>

                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_tube_foley" class="calc-input"> Foley
                        </label>

                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="chk_tube_other" class="calc-input" onchange="document.getElementById('other_tube_group').style.display = this.checked ? 'block' : 'none';"> อื่นๆ
                            </label>
                            <div id="other_tube_group" style="display: none; margin-left: 1.5rem;">
                                <input type="text" id="val_tube_other" class="calc-input" placeholder="ระบุชนิด/ตำแหน่ง" style="margin-top: 0.25rem;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>3.2 คำสั่งการรักษา</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_identify" class="calc-input">
                            Identify และ ตรวจสอบคำสั่งการรักษา
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>3.3 การประเมินพื้นฐาน (ABCDE)</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="chk_abc" class="calc-input">
                            ตรวจสอบ Airway, Breathing, Circulation, Disability, Drain & Splint
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>3.4 เครื่องมือแพทย์และสิ่งที่นำไปด้วย</label>
                    <div class="checkbox-group"
                        style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem; margin-left:1rem;">
                        <div style="display: flex; flex-direction: column;">
                            <label class="checkbox-label">
                                <input type="checkbox" id="eq_emergency_bag" class="calc-input" onchange="toggleGroup('eq_emergency_bag', 'emergency_bag_details')">
                                กระเป๋าฉุกเฉิน (Emergency Bag)
                            </label>
                            <div id="emergency_bag_details" style="display: none; margin-left: 1.5rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fffbeb; border: 1px dashed #f59e0b; border-radius: 4px;">
                                <div style="font-size: 0.8rem; font-weight: 600; color: #b45309; margin-bottom: 0.25rem;">รายการยาตาม Protocol:</div>
                                <div style="font-size: 0.8rem; color: #92400e; display: flex; flex-direction: column; gap: 0.25rem;">
                                    <label><input type="checkbox" checked disabled> Diazepam (10 mg) 3 ampules</label>
                                    <label><input type="checkbox" checked disabled> Adrenaline (1 mg) 3 ampules</label>
                                    <label><input type="checkbox" checked disabled> Atropine (0.6 mg) 2 ampules</label>
                                    <label><input type="checkbox" checked disabled> อุปกรณ์พื้นฐาน (Syringe, Needle, water, alcohol, stethoscope)</label>
                                </div>
                                <div id="emergency_bag_child_items" style="display: none; margin-top: 0.5rem; border-top: 1px solid #fde68a; padding-top: 0.25rem;">
                                    <div style="font-size: 0.8rem; font-weight: 600; color: #b91c1c;">เพิ่มเติมสำหรับผู้ป่วยเด็ก:</div>
                                    <div style="font-size: 0.8rem; color: #991b1b; display: flex; flex-direction: column; gap: 0.25rem;">
                                        <label><input type="checkbox" checked disabled> Laryngoscope & Blade ขนาดเหมาะสม</label>
                                        <label><input type="checkbox" checked disabled> Endotracheal tube ขนาดเหมาะสม</label>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <label>3.5 บุคลากรทางการแพทย์ที่ร่วมเดินทาง</label>
    <div id="personnel-warning"
        style="display: none; padding: 0.75rem; background-color: #fee2e2; color: #b91c1c; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 1rem; border: 1px solid #fecaca;">
        <strong>⚠️ แจ้งเตือน:</strong> ระดับความรุนแรงของการเคลื่อนย้ายอยู่ในระดับประเมินสูง แนะนำให้มี
        <strong>แพทย์</strong> และ <strong>พยาบาล</strong> ร่วมเดินทางไปด้วย
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <div style="display: flex; flex-direction: column;">
            <label class="checkbox-label">
                <input type="checkbox" id="pers_doctor" onchange="togglePersonnelInput('pers_doctor', 'name_doctor')">
                แพทย์
            </label>
            <div id="name_doctor" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                <input type="text" id="val_name_doctor" placeholder="ระบุชื่อแพทย์">
            </div>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label class="checkbox-label">
                <input type="checkbox" id="pers_rn" onchange="togglePersonnelInput('pers_rn', 'name_rn')">
                พยาบาล
            </label>
            <div id="name_rn" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                <input type="text" id="val_name_rn" placeholder="ระบุชื่อพยาบาล">
            </div>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label class="checkbox-label">
                <input type="checkbox" id="pers_pn" onchange="togglePersonnelInput('pers_pn', 'name_pn')">
                ผู้ช่วยพยาบาล
            </label>
            <div id="name_pn" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                <input type="text" id="val_name_pn" placeholder="ระบุชื่อผู้ช่วยพยาบาล">
            </div>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label class="checkbox-label">
                <input type="checkbox" id="pers_aide" onchange="togglePersonnelInput('pers_aide', 'name_aide')">
                ผู้ช่วยเหลือคนไข้
            </label>
            <div id="name_aide" style="display: none; margin-top: 0.5rem; margin-left: 1.5rem;">
                <input type="text" id="val_name_aide" placeholder="ระบุชื่อผู้ช่วยเหลือคนไข้">
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
                    <label>Minute Volume (L/min)</label>
                    <input type="number" id="vent_mv" class="calc-input" placeholder="เช่น 6.4" step="0.1" oninput="calculateOxygen()">
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

            <hr style="margin: 2rem 0;">

            <!-- AI Safety Check -->
            <div style="text-align: center; margin-bottom: 1rem;">
                <button type="button" onclick="runAISafetyCheck()" class="ai-btn" data-keep-enabled="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="margin-right: 8px;">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    AI Safety Check (ประมวลผลส่วนที่ 1-4)
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

        <!-- Section 5: ย้ายหอผู้ป่วย/ย้ายหน่วยงาน (Handoff Information) -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">5</span>
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
                        <label>5.1 Consciousness (ดึงอัตโนมัติ)</label>
                        <input type="text" id="sec6_consciousness" class="calc-input" readonly placeholder="ยังไม่ได้ประเมิน" style="background-color: #f3f4f6; color: var(--text-muted);">
                    </div>
                    <div class="form-group">
                        <label>5.2 การหายใจ / O2 (ดึงอัตโนมัติ)</label>
                        <input type="text" id="sec6_breathing" class="calc-input" readonly placeholder="ยังไม่ได้ประเมิน" style="background-color: #f3f4f6; color: var(--text-muted);">
                    </div>
                </div>

            <hr style="margin: 1.5rem 0;">

            <div class="grid-2">
                <!-- 6.3 IV -->
                <div class="form-group">
                    <label>5.3 IV (สายน้ำเกลือ)</label>
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
                    <label>5.4 Tube (ท่อ / สายระบาย) (ดึงข้อมูลจาก 3.1 อัตโนมัติ)</label>
                    <input type="text" id="sec6_tube_auto" class="calc-input" readonly placeholder="ไม่มี" style="background-color: #f3f4f6; color: var(--text-muted);">
                </div>
            </div>
            
            <div class="grid-2">
                 <div class="form-group">
                    <label>5.5 Ostomy</label>
                    <div class="checkbox-group" style="margin-top: 0.5rem;">
                        <label class="checkbox-label"><input type="checkbox" id="sec6_ostomy" value="Ostomy"> มี Ostomy</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>5.6 Traction</label>
                    <div class="checkbox-group" style="margin-top: 0.5rem;">
                        <label class="checkbox-label"><input type="checkbox" id="sec6_traction" value="Traction"> มี Traction</label>
                    </div>
                </div>
            </div>

            <div class="grid-1">
                <div class="form-group">
                    <label>5.7 อื่นๆ (อุปกรณ์เสริม / หมายเหตุ)</label>
                    <input type="text" id="sec6_others" class="calc-input" placeholder="ระบุข้อมูลเพิ่มเติมถ้ามี">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">

            <!-- 6.8 Rights & 6.9 Billing -->
            <div class="grid-2">
                <div class="form-group">
                    <label>5.8 สิทธิการรักษา</label>
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
                    <label>5.9 ค่ารักษาพยาบาล</label>
                    <div class="radio-group" style="margin-bottom: 0.5rem;">
                        <label class="radio-label"><input type="radio" name="billing_status" value="คิดแล้ว" onchange="toggleBillingOther()"> คิดแล้ว</label>
                        <label class="radio-label"><input type="radio" name="billing_status" value="ยังไม่ได้คิด" onchange="toggleBillingOther()"> ยังไม่ได้คิด</label>
                        <label class="radio-label"><input type="radio" name="billing_status" value="other" onchange="toggleBillingOther()"> อื่นๆ</label>
                    </div>
                    <input type="text" id="billing_other_text" style="display: none; margin-top: 0.5rem;" class="calc-input" placeholder="ระบุข้อมูลเพิ่มเติม">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">

            <div class="grid-1">
                <div class="form-group">
                    <label>5.10 ยา / อุปกรณที่ค้างคืนหอผู้ป่วยต้นทาง</label>
                    <textarea id="sec6_leftover_meds" class="calc-input" rows="3" placeholder="ระบุรายการยาหรืออุปกรณ์ที่ค้างคืน..."></textarea>
                </div>
                <div class="form-group">
                    <label>5.11 ปัญหา / ความต้องการของผู้ป่วย</label>
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
            
            <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; max-width: 600px; margin: 0 auto;">
                <div style="display: flex; width: 100%; gap: 0.5rem; margin-bottom: 0.5rem; align-items: stretch;">
                    <input type="text" id="pre_log_sender" placeholder="ระบุชื่อผู้ส่ง" style="flex: 2; border: 1px solid var(--med-200); border-radius: var(--radius-md); padding: 0.5rem 1rem; font-size: 1rem;" oninput="document.getElementById('log_sender').value = this.value;">
                    <button type="button" onclick="lockSections1to6()" class="evaluate-btn" style="background: linear-gradient(135deg, #10b981, #059669); margin: 0; padding: 0 1.5rem; flex: 1; white-space: nowrap; font-size: 1.05rem;">
                        ส่งผู้ป่วยออก
                    </button>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-align: center;">
                    * เมื่อกดปุ่มจะทำการบันทึกข้อมูลลง Database, stamp เวลาส่งออก และล็อคข้อมูลส่วนที่ 1-5
                </div>
            </div>
        </div>

        <!-- Waypoint Assessment Section -->
        <div class="card" id="waypoint-section" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge" style="background: linear-gradient(135deg, #f59e0b, #d97706);">📍</span>
                <h2>Waypoint Assessment (ประเมินระหว่างทาง)</h2>
            </div>

            <div class="info-box" style="margin-bottom: 1.25rem; background: #fffbeb; border-color: #fcd34d; color: #92400e;">
                <strong>💡 คำแนะนำ:</strong> หากมีการแวะหน่วยงานระหว่างต้นทาง → ปลายทาง ให้กดปุ่มด้านล่างเพื่อบันทึกการประเมินขาออกจากแต่ละจุดแวะ
            </div>

            <div id="waypoint-list">
                <!-- Waypoint entries will be dynamically added here -->
            </div>

            <div style="display: flex; justify-content: center; margin-top: 1rem;">
                <button type="button" onclick="addWaypoint()" class="evaluate-btn" style="background: linear-gradient(135deg, #f59e0b, #d97706); max-width: 400px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    เพิ่มจุดแวะ (Add Waypoint)
                </button>
            </div>

            <!-- Vitals Timer Section -->
            <div id="vitals-timer-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 1.5rem; padding: 1rem; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: var(--radius-md);">
                <div style="font-size: 1rem; font-weight: 600; color: #0369a1; margin-bottom: 0.5rem;">⏱️ นาฬิกาจับเวลาแจ้งเตือนประเมิน Vitals ซ้ำ (ทุก 15 นาที)</div>
                <div style="font-size: 0.8rem; color: #0284c7; margin-bottom: 0.75rem;">สำหรับกรณีเคลื่อนย้ายใช้เวลานาน ให้เริ่มจับเวลาเพื่อเตือนประเมินซ้ำตาม Protocol</div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div id="vitals-timer-display" style="font-size: 1.8rem; font-weight: 800; color: #0284c7; font-variant-numeric: tabular-nums; min-width: 80px; text-align: center;">00:00</div>
                    <button type="button" onclick="startVitalsTimer()" class="evaluate-btn" style="background: #0ea5e9; margin: 0; padding: 0.5rem 1rem; width: auto; font-size: 0.9rem;">▶️ เริ่มจับเวลา</button>
                    <button type="button" onclick="resetVitalsTimer()" class="evaluate-btn" style="background: #94a3b8; margin: 0; padding: 0.5rem 1rem; width: auto; font-size: 0.9rem;">🔄 รีเซ็ต</button>
                </div>
            </div>
        </div>

        <!-- Section 6: ประเมินซ้ำปลายทาง -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">6</span>
                <h2>ประเมินซ้ำปลายทาง (Destination Reassessment)</h2>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>6.1 เวลาที่ผู้ป่วยถึงหน่วยงานปลายทาง</label>
                    <input type="time" id="dest_time_in" class="calc-input" oninput="document.getElementById('log_time_in').value = this.value;">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้รับ (ปลายทาง)</label>
                    <input type="text" id="dest_receiver" class="calc-input" placeholder="ระบุชื่อผู้รับปลายทาง" oninput="document.getElementById('log_receiver').value = this.value;">
                </div>
            </div>

            <hr style="margin: 1.5rem 0;">
            <h3 style="margin-bottom: 1rem; color: var(--primary); font-size: 1.1rem;">6.2 ประเมิน (<span id="dest-assessment-title">NEWS</span>) ซ้ำ</h3>
            
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
            
            <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: center;">
                <button type="button" onclick="completeTransfer()" class="evaluate-btn" style="background: linear-gradient(135deg, #3b82f6, #2563eb); max-width: 350px;">
                    ขนย้ายเสร็จสิ้น
                </button>
            </div>
        </div>
        
        <!-- Section 7: Transfer Log (Moved to the bottom before print) -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="header">
                <span class="step-badge">7</span>
                <h2>บันทึกการเคลื่อนย้าย (Transfer Log)</h2>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>7.1 เวลาส่งออก (ต้นทาง)</label>
                    <input type="time" id="log_time_out" class="calc-input">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้ส่ง (ต้นทาง)</label>
                    <input type="text" id="log_sender" class="calc-input" placeholder="ระบุชื่อผู้ส่ง (ต้นทาง)">
                </div>
            </div>

            <!-- Dynamic Waypoints Container in Transfer Log -->
            <div id="log-waypoints-container" style="margin-top: 1rem; margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
            </div>

            <div class="grid-2" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>7.2 เวลาถึง (ปลายทาง) <span style="font-size: 0.8em; color: var(--text-muted);">(ซิงค์จาก 6.1)</span></label>
                    <input type="time" id="log_time_in" class="calc-input" oninput="document.getElementById('dest_time_in').value = this.value;">
                </div>
                <div class="form-group">
                    <label>ชื่อผู้รับ (ปลายทาง) <span style="font-size: 0.8em; color: var(--text-muted);">(ซิงค์จาก 6.1)</span></label>
                    <input type="text" id="log_receiver" class="calc-input" placeholder="ระบุชื่อผู้รับ (ปลายทาง)" oninput="document.getElementById('dest_receiver').value = this.value;">
                </div>
            </div>
            
            <hr>
            
            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; margin-top: 1rem;">
                <!-- Complete / Print Button -->
                <button type="button" onclick="printForm()" class="evaluate-btn" style="width: 100%; max-width: 350px; background-color: var(--success); display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 1.1rem; padding: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    🖨️ พิมพ์สรุป 1 หน้า A4
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentTransferId = null;

        // ===== WARD DATA =====
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

        function setupSearchableSelect(inputId, dropdownId, containerId) {
            const fWardInput = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            const container = document.getElementById(containerId);
            if (!fWardInput || !dropdown || !container) return;

            let activeOptionIndex = -1;

            // Build the dropdown HTML
            dropdown.innerHTML = '';
            
            for (const [dept, wards] of Object.entries(wardData)) {
                const groupHeader = document.createElement('div');
                groupHeader.className = 'search-select-group-header';
                groupHeader.textContent = dept;
                dropdown.appendChild(groupHeader);
                
                wards.forEach(ward => {
                    const option = document.createElement('div');
                    option.className = 'search-select-option';
                    option.textContent = ward;
                    option.dataset.value = ward;
                    
                    option.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        selectWard(ward);
                    });
                    
                    dropdown.appendChild(option);
                });
            }

            fWardInput.addEventListener('focus', () => openDropdown());
            fWardInput.addEventListener('click', () => openDropdown());
            fWardInput.addEventListener('blur', () => closeDropdown());
            fWardInput.addEventListener('input', () => filterWards());

            fWardInput.addEventListener('keydown', (e) => {
                const visibleOptions = Array.from(dropdown.querySelectorAll('.search-select-option')).filter(opt => opt.style.display !== 'none');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!container.classList.contains('open')) {
                        openDropdown();
                        return;
                    }
                    activeOptionIndex = (activeOptionIndex + 1) % visibleOptions.length;
                    highlightOption(visibleOptions);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (!container.classList.contains('open')) {
                        openDropdown();
                        return;
                    }
                    activeOptionIndex = (activeOptionIndex - 1 + visibleOptions.length) % visibleOptions.length;
                    highlightOption(visibleOptions);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeOptionIndex >= 0 && activeOptionIndex < visibleOptions.length) {
                        selectWard(visibleOptions[activeOptionIndex].dataset.value);
                    } else if (visibleOptions.length > 0) {
                        selectWard(visibleOptions[0].dataset.value);
                    }
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            function openDropdown() {
                container.classList.add('open');
                dropdown.classList.add('open');
                filterWards();
            }

            function closeDropdown() {
                container.classList.remove('open');
                dropdown.classList.remove('open');
                activeOptionIndex = -1;
                
                dropdown.querySelectorAll('.search-select-option').forEach(opt => opt.classList.remove('highlighted'));
                
                const val = fWardInput.value.trim();
                let isValid = false;
                for (const [dept, wards] of Object.entries(wardData)) {
                    if (wards.includes(val)) {
                        isValid = true;
                        break;
                    }
                }
                if (!isValid && val !== '') {
                    fWardInput.value = '';
                    alert('⚠️ กรุณาเลือกหน่วยงานจากรายการที่กำหนด');
                }
            }

            function selectWard(value) {
                fWardInput.value = value;
                closeDropdown();
                fWardInput.blur();
                fWardInput.dispatchEvent(new Event('change'));
                fWardInput.dispatchEvent(new Event('input'));
            }

            function highlightOption(options) {
                dropdown.querySelectorAll('.search-select-option').forEach(opt => opt.classList.remove('highlighted'));
                if (options[activeOptionIndex]) {
                    options[activeOptionIndex].classList.add('highlighted');
                    options[activeOptionIndex].scrollIntoView({ block: 'nearest' });
                }
            }

            function filterWards() {
                const query = fWardInput.value.trim().toLowerCase();
                let currentHeader = null;
                let visibleOptionsInGroup = 0;
                
                const children = Array.from(dropdown.children);
                
                children.forEach(child => {
                    if (child.classList.contains('search-select-group-header')) {
                        if (currentHeader && visibleOptionsInGroup === 0) {
                            currentHeader.style.display = 'none';
                        }
                        currentHeader = child;
                        currentHeader.style.display = 'block';
                        visibleOptionsInGroup = 0;
                    } else if (child.classList.contains('search-select-option')) {
                        const text = child.textContent.toLowerCase();
                        if (text.includes(query)) {
                            child.style.display = 'block';
                            visibleOptionsInGroup++;
                        } else {
                            child.style.display = 'none';
                        }
                    }
                });
                
                if (currentHeader && visibleOptionsInGroup === 0) {
                    currentHeader.style.display = 'none';
                }
                
                activeOptionIndex = -1;
            }
        }

        // ── Stepper navigation ──────────────────────────────────
        // DOM card order: 1, 2, 3, 4, 5, waypoint, 6, 7
        const sectionOrder = [1, 2, 3, 4, 5, 'wp', 6, 7];

        function goToSection(n) {
            document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active','done'));
            // Mark previous steps as done
            const navOrder = [1,2,3,4,5,'wp',6,7];
            const targetIdx = navOrder.indexOf(n);
            for(let i=0;i<targetIdx;i++){
                const el=document.getElementById('nav-'+navOrder[i]);
                if(el) el.classList.add('done');
            }
            const active=document.getElementById('nav-'+n);
            if(active) active.classList.add('active');

            // Scroll to the corresponding card
            if (n === 'wp') {
                const wpSec = document.getElementById('waypoint-section');
                if(wpSec) {
                    const y = wpSec.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({top: y, behavior: 'smooth'});
                }
                return;
            }
            const cards = document.querySelectorAll('.card');
            // Map n to correct index based on DOM order: 1, 2, 3, 4, 5, waypoint, 6, 7
            let idx = n - 1;
            if (n === 6) idx = 6;
            if (n === 7) idx = 7;

            if(cards[idx]) {
                const y = cards[idx].getBoundingClientRect().top + window.scrollY - 100;
                window.scrollTo({top: y, behavior: 'smooth'});
            }
        }

        // Highlight stepper on scroll
        window.addEventListener('scroll', () => {
            const cards = document.querySelectorAll('.card');
            // DOM order: 0=sec1, 1=sec2, 2=sec3, 3=sec4, 4=sec5, 5=waypoint, 6=sec6, 7=sec7
            const cardToNav = [1, 2, 3, 4, 5, 'wp', 6, 7];
            let current = 1;
            cards.forEach((card, i) => {
                const rect = card.getBoundingClientRect();
                if(rect.top <= 120 && i < cardToNav.length) {
                    current = cardToNav[i];
                }
            });
            document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active','done'));
            const navOrder = [1,2,3,4,5,'wp',6,7];
            const currentIdx = navOrder.indexOf(current);
            for(let i=0;i<currentIdx;i++){
                const el=document.getElementById('nav-'+navOrder[i]);
                if(el) el.classList.add('done');
            }
            const a=document.getElementById('nav-'+current);
            if(a) a.classList.add('active');
        }, {passive:true});

        // ── Score banner enhanced display ───────────────────────
        function getRiskLabel(score, isChild) {
            if(score === 0) return 'ปกติ';
            if(isChild) {
                if(score >= 7) return 'ความเสี่ยงสูงมาก — ต้องการแพทย์';
                if(score >= 4) return 'ความเสี่ยงปานกลาง';
                return 'ความเสี่ยงต่ำ';
            }
            if(score >= 7) return 'ความเสี่ยงสูงมาก — Emergency';
            if(score >= 5) return 'ความเสี่ยงปานกลาง — Urgent';
            if(score >= 1) return 'ความเสี่ยงต่ำ — Monitor';
            return 'ปกติ';
        }

        // ── Elements ─────────────────────────────────────────────
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
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
            const childBag = document.getElementById('emergency_bag_child_items');

            if (isChild) {
                assessmentTitle.innerText = 'PEWS';
                scoreLabel.innerText = 'PEWS:';
                adultFields.forEach(el => el.style.display = 'none');
                pedFields.forEach(el => el.style.display = 'grid');
                document.getElementById('copd-group').style.display = 'none';
                if(childBag) childBag.style.display = 'block';
            } else {
                assessmentTitle.innerText = 'NEWS';
                scoreLabel.innerText = 'NEWS:';
                if(childBag) childBag.style.display = 'none';
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

            // Bug #17/#18: Update destination form fields and title
            const destAdultFields = document.querySelectorAll('.adult-field-dest');
            const destPedFields = document.querySelectorAll('.pediatric-field-dest');
            const destAssessTitle = document.getElementById('dest-assessment-title');
            if (isChild) {
                destAdultFields.forEach(el => el.style.display = 'none');
                destPedFields.forEach(el => el.style.display = 'grid');
                if(destAssessTitle) destAssessTitle.innerText = 'PEWS';
            } else {
                destAdultFields.forEach(el => {
                    if (el.classList.contains('grid-2')) el.style.display = 'grid';
                    else if (el.classList.contains('checkbox-group')) el.style.display = 'block';
                    else el.style.display = 'flex';
                });
                destPedFields.forEach(el => el.style.display = 'none');
                if(destAssessTitle) destAssessTitle.innerText = 'NEWS';
            }
            calculateDestScore();
        }

        // --- Vitals Timer Logic ---
        let vitalsTimerInterval;
        let vitalsSeconds = 0;
        function startVitalsTimer() {
            if(vitalsTimerInterval) clearInterval(vitalsTimerInterval);
            vitalsSeconds = 0;
            updateVitalsTimerDisplay();
            vitalsTimerInterval = setInterval(() => {
                vitalsSeconds++;
                updateVitalsTimerDisplay();
                // Alert at 15 minutes (900 seconds)
                if(vitalsSeconds === 900) {
                    alert("⏱️ แจ้งเตือน: ครบ 15 นาทีแล้ว! กรุณาประเมิน Vitals ของผู้ป่วยซ้ำตาม Protocol");
                }
                // Alert at 30 minutes (1800 seconds)
                if(vitalsSeconds === 1800) {
                    alert("⏱️ แจ้งเตือน: ครบ 30 นาทีแล้ว! กรุณาประเมิน Vitals ของผู้ป่วยซ้ำ");
                }
            }, 1000);
        }
        function resetVitalsTimer() {
            if(vitalsTimerInterval) clearInterval(vitalsTimerInterval);
            vitalsSeconds = 0;
            updateVitalsTimerDisplay();
        }
        function updateVitalsTimerDisplay() {
            const display = document.getElementById('vitals-timer-display');
            if(!display) return;
            const m = Math.floor(vitalsSeconds / 60).toString().padStart(2, '0');
            const s = (vitalsSeconds % 60).toString().padStart(2, '0');
            display.innerText = `${m}:${s}`;
            if(vitalsSeconds >= 900) {
                display.style.color = '#ef4444'; // red
            } else {
                display.style.color = '#0284c7'; // blue
            }
        }

        function calculateScore() {
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
            let totalScore = 0;

            if (isChild) {
                totalScore = calculatePEWS();
            } else {
                totalScore = calculateNEWS();
            }

            scoreValue.innerText = totalScore;

            // Enhanced banner with risk label
            const riskLabelEl = document.getElementById('score-risk-label');
            if(riskLabelEl) riskLabelEl.innerText = getRiskLabel(totalScore, isChild);

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

        // Initialize display state moved to the bottom of the script block to prevent ReferenceError due to Temporal Dead Zone

        // -------------------------------------------------------------
        // Section 2 Logic
        // -------------------------------------------------------------

        function toggleO2Options() {
            const breathEl = document.querySelector('input[name="breath"]:checked');
            const val = breathEl ? breathEl.value : 'no_o2';
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
            const vitalsEl = document.querySelector('input[name="vitals"]:checked');
            const val = vitalsEl ? vitalsEl.value : 'stable';
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
        let _personnelUpdating = false;

        function checkPersonnelWarning() {
            if (_personnelUpdating) return;
            _personnelUpdating = true;
            const warningDiv = document.getElementById('personnel-warning');

            if (currentSeverityLevel === 4) {
                document.getElementById('pers_doctor').checked = true;
                document.getElementById('pers_rn').checked = true;
                togglePersonnelInput('pers_doctor', 'name_doctor');
                togglePersonnelInput('pers_rn', 'name_rn');
                warningDiv.innerHTML = `<strong>🚨 คำแนะนำ (Level 4):</strong> ระดับความรุนแรง "รุนแรงมาก" บังคับต้องมี <strong>แพทย์</strong> และ <strong>พยาบาล</strong> ร่วมเดินทาง (ระบบเลือกให้อัตโนมัติ)`;
                warningDiv.style.backgroundColor = '#fee2e2';
                warningDiv.style.color = '#b91c1c';
                warningDiv.style.borderColor = '#fecaca';
                warningDiv.style.display = 'block';
            } else if (currentSeverityLevel === 3) {
                document.getElementById('pers_rn').checked = true;
                togglePersonnelInput('pers_rn', 'name_rn');
                warningDiv.innerHTML = `<strong>⚠️ คำแนะนำ (Level 3):</strong> ระดับความรุนแรง "รุนแรงปานกลาง" บังคับต้องมี <strong>พยาบาลวิชาชีพ</strong> ร่วมเดินทางเป็นอย่างน้อย (ระบบเลือกให้อัตโนมัติ)`;
                warningDiv.style.backgroundColor = '#fffbeb';
                warningDiv.style.color = '#b45309';
                warningDiv.style.borderColor = '#fde68a';
                warningDiv.style.display = 'block';
            } else if (currentSeverityLevel === 2) {
                document.getElementById('pers_pn').checked = true;
                togglePersonnelInput('pers_pn', 'name_pn');
                warningDiv.innerHTML = `<strong>💡 คำแนะนำ (Level 2):</strong> ระดับความรุนแรง "รุนแรงเล็กน้อย" ต้องมี <strong>พนักงานช่วยการพยาบาล (PN/Helper)</strong> ร่วมเดินทางเป็นอย่างน้อย`;
                warningDiv.style.backgroundColor = '#eff6ff';
                warningDiv.style.color = '#1d4ed8';
                warningDiv.style.borderColor = '#bfdbfe';
                warningDiv.style.display = 'block';
            } else {
                document.getElementById('pers_aide').checked = true;
                togglePersonnelInput('pers_aide', 'name_aide');
                warningDiv.innerHTML = `<strong>✅ คำแนะนำ (Level 1):</strong> ระดับความรุนแรง "ไม่รุนแรง" ผู้ป่วยสามารถไปเอง หรือไปกับ <strong>พนักงานเปล</strong> ได้`;
                warningDiv.style.backgroundColor = '#ecfdf5';
                warningDiv.style.color = '#047857';
                warningDiv.style.borderColor = '#a7f3d0';
                warningDiv.style.display = 'block';
            }
            _personnelUpdating = false;
        }

        function evaluateTransportSeverity() {
            // Get inputs safely
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
            
            const scoreValueEl = document.getElementById('score-value');
            const scoreValueText = scoreValueEl ? scoreValueEl.innerText : '0';
            const score = parseInt(scoreValueText) || 0;
            
            const consciousnessEl = document.getElementById('consciousness');
            const consciousness = consciousnessEl ? consciousnessEl.value : 'alert';

            const commEl = document.querySelector('input[name="comm"]:checked');
            const comm = commEl ? commEl.value : 'yes'; // default yes/no
            
            const breathEl = document.querySelector('input[name="breath"]:checked');
            const breath = breathEl ? breathEl.value : 'no_o2'; // default no_o2 / o2
            
            const vitalsEl = document.querySelector('input[name="vitals"]:checked');
            const vitals = vitalsEl ? vitalsEl.value : 'stable'; // default stable / unstable
            
            const vitalMonitorEl = document.getElementById('vital_monitor');
            const vitalMonitor = vitalMonitorEl ? vitalMonitorEl.value : ''; // 15min, 1hr, 2-4hr
            
            const sedationEl = document.querySelector('input[name="sedation"]:checked');
            const sedation = sedationEl ? sedationEl.value : 'no'; // default no / yes
            
            const ctasEl = document.getElementById('ctas');
            const ctas = ctasEl ? (parseInt(ctasEl.value) || 0) : 0;

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
                consciousness === 'unresponsive' || // ไม่รู้สึกตัว หรือ acute confusion
                vitals === 'unstable' || // สัญญาณชีพไม่คงที่
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
            const aiBtn = document.querySelector('.ai-btn[onclick="runAISafetyCheck()"]') || document.querySelector('.ai-btn');
            const aiLoading = document.getElementById('ai-loading');
            const aiResult = document.getElementById('ai-result');

            // Collect necessary data to send to Gemini (Sections 1-4)
            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value : '';
            const getChk = (id) => document.getElementById(id) ? document.getElementById(id).checked : false;

            const formData = {
                form_mode: 'safety_check',
                hn: document.querySelector('input[placeholder="ระบุ HN"]')?.value || 'UNKNOWN',
    transfer_id: currentTransferId,
                // Section 1: Pre-assessment
                age_group: document.querySelector('input[name="age_group"]:checked')?.value,
                vitals: {
                    rr: getVal('rr'),
                    spo2: getVal('spo2'),
                    sbp: getVal('sbp'),
                    pulse: getVal('pulse'),
                    temp: getVal('temp'),
                    airo2: getVal('airo2'),
                    copd: getChk('copd')
                },
                // Section 2: Severity
                score: document.getElementById('score-value')?.innerText,
                assessment_type: document.getElementById('assessment-title')?.innerText,
                vitals_stable: document.querySelector('input[name="vitals"]:checked')?.value === 'stable',
                consciousness: getVal('consciousness'),
                sedation: document.querySelector('input[name="sedation"]:checked')?.value === 'yes',
                o2_support: document.querySelector('input[name="breath"]:checked')?.value === 'o2',
                risk_suicide: getChk('risk_suicide'),
                risk_mdrs: getChk('risk_mdrs'),
                risk_child: getChk('risk_child'),
                severity_level: typeof currentSeverityLevel !== 'undefined' ? currentSeverityLevel : null,
                // Section 3: Checklist
                checklist: {
                    tube_drain_auto: getVal('sec6_tube_auto'),
                    identify: getChk('chk_identify'),
                    abc: getChk('chk_abc'),
                    eq_emergency_bag: getChk('eq_emergency_bag'),
                    eq_infusion: getChk('eq_infusion'),
                    eq_ventilator: getChk('eq_ventilator'),
                    eq_ekg: getChk('eq_ekg'),
                    eq_records: getChk('eq_records')
                },
                // Section 4: Oxygen info
                oxygen: {
                    cylinder_size: getVal('o2_cylinder_size'),
                    pressure: getVal('o2_pressure'),
                    safe_residual: getVal('o2_safe_residual'),
                    flow_rate: getVal('o2_flow_rate'),
                    type: getVal('o2_type'),
                    estimated_time: document.getElementById('o2-time-result')?.innerText
                }
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
                    if (data.transfer_id) currentTransferId = data.transfer_id;
                    // showSavedBadge('ai-result', currentTransferId); // Not defined
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
            const breathEl = document.querySelector('input[name="breath"]:checked');
            const isO2 = breathEl ? breathEl.value === 'o2' : false;
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

            const breathEl = document.querySelector('input[name="breath"]:checked');
            const isO2 = breathEl ? breathEl.value === 'o2' : false;
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
                const minuteVolume = parseFloat(document.getElementById('vent_mv').value);
                const targetFiO2 = parseFloat(document.getElementById('vent_fio2').value);
                const ventTypeEl = document.querySelector('input[name="vent_type"]:checked');
                const ventType = ventTypeEl ? ventTypeEl.value : 'turbine';
                
                if (isNaN(minuteVolume) || isNaN(targetFiO2) || minuteVolume <= 0) {
                    resultBanner.style.display = 'none';
                    return;
                }
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
                
                // Update 6.4 Tube
                const s6Tube = document.getElementById('sec6_tube_auto');
                if (s6Tube) {
                    let tubeList = [];
                    if (document.getElementById('chk_tube_ng')?.checked) tubeList.push('NG/OG');
                    if (document.getElementById('chk_tube_peg')?.checked) tubeList.push('PEG');
                    
                    const icdVal = document.getElementById('val_tube_icd')?.value;
                    if (document.getElementById('chk_tube_icd')?.checked) tubeList.push('ICD' + (icdVal ? ' (' + icdVal + ')' : ''));
                    
                    const redivacVal = document.getElementById('val_tube_redivac')?.value;
                    if (document.getElementById('chk_tube_redivac')?.checked) tubeList.push('Redivac' + (redivacVal ? ' (' + redivacVal + ')' : ''));
                    
                    const pcnVal = document.getElementById('val_tube_pcn')?.value;
                    if (document.getElementById('chk_tube_pcn')?.checked) tubeList.push('PCN' + (pcnVal ? ' (' + pcnVal + ')' : ''));
                    
                    if (document.getElementById('chk_tube_foley')?.checked) tubeList.push('Foley');
                    
                    const otherVal = document.getElementById('val_tube_other')?.value;
                    if (document.getElementById('chk_tube_other')?.checked && otherVal) tubeList.push(otherVal);
                    
                    s6Tube.value = tubeList.length > 0 ? tubeList.join(', ') : 'ไม่มี';
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
        
        // Listeners for Tube/Drain elements
        const tubeSyncIds = ['chk_tube_ng', 'chk_tube_peg', 'chk_tube_icd', 'val_tube_icd', 'chk_tube_redivac', 'val_tube_redivac', 'chk_tube_pcn', 'val_tube_pcn', 'chk_tube_foley', 'chk_tube_other', 'val_tube_other'];
        tubeSyncIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', updateSection6Sync);
                if (el.tagName.toLowerCase() === 'input' && el.type === 'text') {
                    el.addEventListener('input', updateSection6Sync);
                }
            }
        });

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
            const tubeStr = document.getElementById('sec6_tube_auto').value;
            if (tubeStr && tubeStr !== 'ไม่มี') {
                tubes.push(tubeStr);
            }

            let formData = {
                form_mode: 'handoff',
                transfer_id: currentTransferId,
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
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
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
                destScoreBanner.style.backgroundColor = '#f3f4f6';
                destScoreBanner.style.color = 'var(--text-main)';
            } else if (score >= 1 && score <= 4) {
                destScoreBanner.classList.add('score-low');
            } else if (score >= 5 && score <= 6) {
                destScoreBanner.classList.add('score-medium');
            } else if (score >= 7) {
                destScoreBanner.classList.add('score-high');
            }
        }

        async function saveTransferData() {
            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value : null;
            const getChk = (id) => document.getElementById(id) ? (document.getElementById(id).checked ? 1 : 0) : 0;
            const getRadio = (name) => {
                const el = document.querySelector(`input[name="${name}"]:checked`);
                return el ? el.value : null;
            };
            const getText = (selector) => {
                const el = document.querySelector(selector);
                return el ? el.value : null;
            };

            const hnVal = getText('input[placeholder="ระบุ HN"]');
            if (!hnVal) {
                alert('กรุณาระบุ HN ผู้ป่วยก่อนบันทึกข้อมูล');
                return false;
            }

            const formData = {
                hn: hnVal,
                patient_name: getText('input[placeholder="ระบุชื่อผู้ป่วย"]'),
                ward: getText('input[placeholder="ระบุหน่วยงานต้นทาง"]'),
                destination: getText('input[placeholder="ระบุหน่วยงานปลายทาง"]'),
                age_group: getRadio('age_group') || 'adult',
                
                rr: getVal('rr'),
                spo2: getVal('spo2'),
                airo2: getVal('airo2') || 'room',
                consciousness: getVal('consciousness') || 'alert',
                sbp: getVal('sbp'),
                pulse: getVal('pulse'),
                temp: getVal('temp'),
                copd: getChk('copd'),
                hr_peds: getVal('hr_peds'),
                resp_effort: getVal('resp_effort') || 'normal',
                news_score: parseInt(document.getElementById('score-value')?.innerText) || 0,
                pews_score: parseInt(document.getElementById('score-value')?.innerText) || 0,

                comm: getRadio('comm') || 'yes',
                breath: getRadio('breath') || 'no_o2',
                o2_type: getVal('o2_type'),
                vitals_stable: getRadio('vitals') === 'stable' ? 1 : 0,
                vital_monitor: getVal('vital_monitor'),
                sedation: getRadio('sedation') || 'no',
                ctas_level: getVal('ctas'),
                risk_suicide: getChk('risk_suicide'),
                risk_mdrs: getChk('risk_mdrs'),
                risk_child: getChk('risk_child'),
                risk_other: getChk('risk_other_check') ? getVal('risk_other_text') : null,
                severity_level: typeof currentSeverityLevel !== 'undefined' ? currentSeverityLevel : 1,

                tube_ng: getChk('chk_tube_ng'),
                tube_peg: getChk('chk_tube_peg'),
                tube_icd: getChk('chk_tube_icd'),
                tube_icd_side: getVal('val_tube_icd'),
                tube_redivac: getChk('chk_tube_redivac'),
                tube_redivac_location: getVal('val_tube_redivac'),
                tube_pcn: getChk('chk_tube_pcn'),
                tube_pcn_location: getVal('val_tube_pcn'),
                tube_foley: getChk('chk_tube_foley'),
                tube_other: getChk('chk_tube_other'),
                tube_other_detail: getVal('val_tube_other'),
                
                chk_identify: getChk('chk_identify'),
                chk_abc: getChk('chk_abc'),
                eq_emergency_bag: getChk('eq_emergency_bag'),
                eq_infusion: getChk('eq_infusion'),
                eq_ventilator: getChk('eq_ventilator'),
                eq_ekg: getChk('eq_ekg'),
                eq_records: getChk('eq_records'),
                
                pers_doctor: getChk('pers_doctor'),
                pers_doctor_name: getVal('val_name_doctor'),
                pers_rn: getChk('pers_rn'),
                pers_rn_name: getVal('val_name_rn'),
                pers_pn: getChk('pers_pn'),
                pers_pn_name: getVal('val_name_pn'),
                pers_aide: getChk('pers_aide'),
                pers_aide_name: getVal('val_name_aide'),

                o2_cylinder_size: getVal('o2_cylinder_size'),
                o2_pressure: getVal('o2_pressure'),
                o2_safe_residual: getVal('o2_safe_residual'),
                o2_flow_rate: getVal('o2_flow_rate'),
                o2_time_result: document.getElementById('o2-time-result')?.innerText,
                hfnc_flow: getVal('hfnc_flow'),
                hfnc_fio2: getVal('hfnc_fio2'),
                niv_total_flow: getVal('niv_total_flow'),
                niv_safety_margin: getChk('niv_safety_margin'),
                vent_mv: getVal('vent_mv'),
                vent_fio2: getVal('vent_fio2'),
                vent_type: getRadio('vent_type'),

                log_time_out: getVal('log_time_out'),
                log_sender: getVal('log_sender'),
                log_time_in: getVal('log_time_in'),
                log_receiver: getVal('log_receiver'),

                sec6_enabled: getChk('toggle_sec6'),
                sec6_consciousness: getVal('sec6_consciousness'),
                sec6_breathing: getVal('sec6_breathing'),
                iv_pls: getChk('iv_pls'),
                iv_aline: getChk('iv_aline'),
                iv_cline: getChk('iv_cline'),
                iv_dlc: getChk('iv_dlc'),
                iv_perm: getChk('iv_perm'),
                iv_other: getChk('iv_other_check') ? getVal('iv_other_text') : null,
                sec6_tube: getVal('sec6_tube_auto'),
                sec6_ostomy: getChk('sec6_ostomy'),
                sec6_traction: getChk('sec6_traction'),
                sec6_others: getVal('sec6_others'),
                rights_status: getRadio('rights_status'),
                rights_detail: getRadio('rights_status') === 'has_rights' ? getVal('rights_has_select') : getVal('rights_no_select'),
                billing_status: getRadio('billing_status') === 'other' ? getVal('billing_other_text') : getRadio('billing_status'),
                billing_other: getVal('billing_other_text'),
                sec6_leftover_meds: getVal('sec6_leftover_meds'),
                sec6_problems: getVal('sec6_problems')
            };

            const waypointsData = [];
            document.querySelectorAll('.waypoint-entry').forEach((wp, index) => {
                const wpId = wp.id.replace('wp-entry-', '');
                waypointsData.push({
                    location_name: getVal('wp_location_' + wpId) || `จุดแวะที่ ${index + 1}`,
                    time_departed: getVal('wp_time_out_' + wpId) || null,
                    rr: getVal('wp_rr_' + wpId),
                    spo2: getVal('wp_spo2_' + wpId),
                    airo2: getVal('wp_airo2_' + wpId) || 'room',
                    consciousness: getVal('wp_consciousness_' + wpId) || 'alert',
                    sbp: getVal('wp_sbp_' + wpId),
                    pulse: getVal('wp_pulse_' + wpId),
                    temp: getVal('wp_temp_' + wpId),
                    hr_peds: getVal('wp_hr_peds_' + wpId),
                    resp_effort: getVal('wp_resp_effort_' + wpId) || 'normal',
                    news_score: parseInt(document.getElementById('wp-score-value-' + wpId)?.innerText) || 0,
                    pews_score: parseInt(document.getElementById('wp-score-value-' + wpId)?.innerText) || 0,
                    note: getVal('wp_note_' + wpId)
                });
            });
            formData.waypoints = waypointsData;

            try {
                let url = './DB_transfer.php/transfers';
                let method = 'POST';

                if (currentTransferId) {
                    url += '/' + currentTransferId;
                    method = 'PUT';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                if (result.success) {
                    currentTransferId = result.data.id;
                    return true;
                } else {
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' + result.message);
                    return false;
                }
            } catch (err) {
                console.error(err);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล');
                return false;
            }
        }

        async function saveTransferDataAlert() {
            const success = await saveTransferData();
            if (success) {
                alert('บันทึกข้อมูลเข้าฐานข้อมูลสำเร็จ');
            }
        }

        async function lockSections1to6() {
            // Auto stamp current time to log_time_out
            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const timeOutInput = document.getElementById('log_time_out');
            if(timeOutInput) {
                timeOutInput.value = timeString;
            }

            const isSaved = await saveTransferData();
            if (!isSaved) return;

            try {
                const response = await fetch('DB_transfer.php/transfers/' + currentTransferId + '/lock', { method: 'POST' });
                const result = await response.json();
                if (!result.success) {
                    alert('บันทึกข้อมูลสำเร็จ แต่ไม่สามารถล็อคข้อมูลได้: ' + result.message);
                    return;
                }
            } catch (err) {
                console.error('Error locking:', err);
                alert('เกิดข้อผิดพลาดในการล็อคข้อมูล');
                return;
            }

            const cards = document.querySelectorAll('.card');
            // The DOM order is Section 1, 2, 3, 4, 5, waypoint, 6, 7
            // Indices 0 to 4 correspond to sections 1, 2, 3, 4, 5
            for(let i = 0; i <= 4; i++) {
                if(cards[i]) {
                    cards[i].classList.add('locked');
                    const elements = cards[i].querySelectorAll('input, select, textarea, button');
                    elements.forEach(el => {
                        if (el.getAttribute('data-keep-enabled') === 'true') {
                            el.disabled = false;
                            el.style.setProperty('pointer-events', 'auto', 'important');
                        } else {
                            el.disabled = true;
                        }
                    });
                    cards[i].style.opacity = '0.85';
                }
            }
            alert('ส่งข้อมูลเข้า Database และล็อคข้อมูลส่วนที่ 1-5 เรียบร้อยแล้ว');
        }

        async function completeTransfer() {
            const isSaved = await saveTransferData();
            if (!isSaved) return;

            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value : null;
            const getChk = (id) => document.getElementById(id) ? (document.getElementById(id).checked ? 1 : 0) : 0;

            const completeData = {
                dest_time_in: getVal('dest_time_in'),
                dest_receiver: getVal('dest_receiver'),
                dest_rr: getVal('dest_rr'),
                dest_spo2: getVal('dest_spo2'),
                dest_airo2: getVal('dest_airo2') || 'room',
                dest_consciousness: getVal('dest_consciousness') || 'alert',
                dest_sbp: getVal('dest_sbp'),
                dest_pulse: getVal('dest_pulse'),
                dest_temp: getVal('dest_temp'),
                dest_copd: getChk('dest_copd'),
                dest_hr_peds: getVal('dest_hr_peds'),
                dest_resp_effort: getVal('dest_resp_effort') || 'normal',
                dest_news_score: parseInt(document.getElementById('dest-score-value')?.innerText) || 0,
                dest_pews_score: parseInt(document.getElementById('dest-score-value')?.innerText) || 0
            };

            try {
                const response = await fetch('DB_transfer.php/transfers/' + currentTransferId + '/complete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(completeData)
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('transfer patient complete (บันทึกข้อมูลเสร็จสิ้นเรียบร้อยแล้ว)');
                } else {
                    alert('เกิดข้อผิดพลาดในการบันทึกตอนปลายทาง: ' + result.message);
                }
            } catch (err) {
                console.error(err);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล');
            }
        }

        // -------------------------------------------------------------
        // Waypoint Assessment Logic
        // -------------------------------------------------------------
        let waypointCount = 0;

        function addWaypoint() {
            waypointCount++;
            const wpNum = waypointCount;
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
            const scoreType = isChild ? 'PEWS' : 'NEWS';

            const wpHTML = `
            <div class="waypoint-entry" id="wp-entry-${wpNum}" style="border: 2px solid #fcd34d; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.25rem; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); position: relative; animation: fadeIn 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: #92400e; font-size: 1.05rem; display: flex; align-items: center; gap: 0.5rem;">
                        📍 จุดแวะที่ ${wpNum}
                    </h3>
                    <button type="button" onclick="removeWaypoint(${wpNum})" style="background: #fecaca; border: 1px solid #fca5a5; color: #991b1b; border-radius: 8px; padding: 0.3rem 0.8rem; font-size: 0.8rem; cursor: pointer; font-weight: 600; transition: var(--transition);" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fecaca'">
                        ✕ ลบจุดแวะ
                    </button>
                </div>

                <div class="grid-1" style="margin-bottom: 1rem;">
                    <div class="form-group">
                        <label>ชื่อหน่วยงาน / สถานที่แวะ</label>
                        <input type="text" id="wp_location_${wpNum}" placeholder="เช่น X-Ray, CT Scan, ER" style="border-color: #fcd34d;" oninput="syncWaypointsToLog()">
                    </div>
                </div>
                <div class="grid-2" style="margin-bottom: 1rem;">
                    <div class="form-group">
                        <label>เวลาถึงจุดแวะ (Time In)</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="time" id="wp_time_in_${wpNum}" style="border-color: #fcd34d; flex: 1;" oninput="syncWaypointsToLog()">
                            <button type="button" onclick="stampWaypointTime('wp_time_in_${wpNum}')" class="evaluate-btn" style="width: auto; background: linear-gradient(135deg, #f59e0b, #d97706); padding: 0 1rem; font-size: 0.85rem;">Stamp</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>เวลาออกจากจุดแวะ (Time Out)</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="time" id="wp_time_out_${wpNum}" style="border-color: #fcd34d; flex: 1;" oninput="syncWaypointsToLog()">
                            <button type="button" onclick="stampWaypointTime('wp_time_out_${wpNum}')" class="evaluate-btn" style="width: auto; background: linear-gradient(135deg, #f59e0b, #d97706); padding: 0 1rem; font-size: 0.85rem;">Stamp</button>
                        </div>
                    </div>
                </div>

                <hr style="border-color: #fcd34d; margin: 1rem 0;">
                <h3 style="margin-bottom: 0.75rem; color: #92400e; font-size: 0.95rem;">ประเมิน ${scoreType} ขาออก</h3>

                <div class="grid-2">
                    <div class="form-group">
                        <label>RR (ครั้ง/นาที)</label>
                        <input type="number" id="wp_rr_${wpNum}" class="wp-calc-${wpNum}" placeholder="0" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                    <div class="form-group">
                        <label>SpO2 (%)</label>
                        <input type="number" id="wp_spo2_${wpNum}" class="wp-calc-${wpNum}" placeholder="0" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Air/O2</label>
                        <select id="wp_airo2_${wpNum}" class="wp-calc-${wpNum}" onchange="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                            <option value="room">Room Air</option>
                            <option value="oxygen">Oxygen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Consciousness</label>
                        <select id="wp_consciousness_${wpNum}" class="wp-calc-${wpNum}" onchange="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                            <option value="alert">Alert</option>
                            <option value="voice">Voice / Somnolent</option>
                            <option value="pain">Pain / Irritable</option>
                            <option value="unresponsive">Unresponsive / Lethargic</option>
                        </select>
                    </div>
                </div>

                ${!isChild ? `
                <div class="grid-2">
                    <div class="form-group">
                        <label>SBP (mmHg)</label>
                        <input type="number" id="wp_sbp_${wpNum}" class="wp-calc-${wpNum}" placeholder="0" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                    <div class="form-group">
                        <label>Pulse (ครั้ง/นาที)</label>
                        <input type="number" id="wp_pulse_${wpNum}" class="wp-calc-${wpNum}" placeholder="0" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Temp (°C)</label>
                        <input type="number" id="wp_temp_${wpNum}" class="wp-calc-${wpNum}" placeholder="0.0" step="0.1" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                </div>
                ` : `
                <div class="grid-2">
                    <div class="form-group">
                        <label>Heart Rate (ครั้ง/นาที)</label>
                        <input type="number" id="wp_hr_peds_${wpNum}" class="wp-calc-${wpNum}" placeholder="0" oninput="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                    </div>
                    <div class="form-group">
                        <label>Respiratory Effort</label>
                        <select id="wp_resp_effort_${wpNum}" class="wp-calc-${wpNum}" onchange="calculateWaypointScore(${wpNum})" style="border-color: #fcd34d;">
                            <option value="normal">Normal</option>
                            <option value="mild">Mild (Tachypnea)</option>
                            <option value="moderate">Moderate (Retractions)</option>
                            <option value="severe">Severe (Grunting/Apnea)</option>
                        </select>
                    </div>
                </div>
                `}

                <div class="form-group" style="margin-top: 0.75rem;">
                    <label>หมายเหตุ (ถ้ามี)</label>
                    <input type="text" id="wp_note_${wpNum}" placeholder="บันทึกเพิ่มเติม เช่น อาการเปลี่ยนแปลง" style="border-color: #fcd34d;">
                </div>

                <div class="score-banner" id="wp-score-banner-${wpNum}" style="margin-top: 1rem;">
                    <div style="font-size:2.2rem;font-weight:800;line-height:1;min-width:2.5rem;text-align:center;" id="wp-score-value-${wpNum}">0</div>
                    <div style="display:flex;flex-direction:column;gap:.2rem;">
                        <span style="font-size:.7rem;font-weight:600;opacity:.75;letter-spacing:.06em;text-transform:uppercase;" id="wp-score-label-${wpNum}">${scoreType} Score</span>
                        <span style="font-size:.85rem;font-weight:700;" id="wp-score-risk-${wpNum}">ปกติ</span>
                    </div>
                </div>
            </div>
            `;

            document.getElementById('waypoint-list').insertAdjacentHTML('beforeend', wpHTML);
            // Scroll to the new waypoint
            setTimeout(() => {
                const newWp = document.getElementById('wp-entry-' + wpNum);
                if(newWp) {
                    newWp.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        }

        function removeWaypoint(wpNum) {
            const entry = document.getElementById('wp-entry-' + wpNum);
            if(entry) {
                entry.style.animation = 'fadeOut 0.2s ease';
                setTimeout(() => {
                    entry.remove();
                    syncWaypointsToLog();
                }, 200);
            }
        }

        function stampWaypointTime(inputId) {
            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const input = document.getElementById(inputId);
            if(input) {
                input.value = timeString;
                syncWaypointsToLog();
            }
        }

        function syncWaypointsToLog() {
            const container = document.getElementById('log-waypoints-container');
            if(!container) return;
            
            const waypoints = document.querySelectorAll('.waypoint-entry');
            if(waypoints.length === 0) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            waypoints.forEach((wp, index) => {
                const wpId = wp.id.replace('wp-entry-', '');
                const location = document.getElementById('wp_location_' + wpId)?.value || `จุดแวะที่ ${index + 1}`;
                const timeIn = document.getElementById('wp_time_in_' + wpId)?.value || '-';
                const timeOut = document.getElementById('wp_time_out_' + wpId)?.value || '-';
                
                html += `
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; background: var(--surface); padding: 0.75rem 1rem; border-radius: var(--radius); border: 1px dashed var(--med-200);">
                    <div style="font-weight: 600; color: var(--med-800);">📍 ${location}</div>
                    <div style="font-size: 0.9rem; color: var(--text-muted);">เวลาถึง: <span style="color: var(--text); font-weight: 500;">${timeIn}</span></div>
                    <div style="font-size: 0.9rem; color: var(--text-muted);">เวลาออก: <span style="color: var(--text); font-weight: 500;">${timeOut}</span></div>
                </div>`;
            });
            container.innerHTML = html;
        }

        function calculateWaypointScore(wpNum) {
            const ageGroupEl = document.querySelector('input[name="age_group"]:checked');
            const isChild = ageGroupEl ? ageGroupEl.value === 'child' : false;
            let score = 0;
            const gv = (id) => { const el = document.getElementById(id); return el ? parseFloat(el.value) : NaN; };
            const sv = (id) => { const el = document.getElementById(id); return el ? el.value : ''; };

            const rr = gv('wp_rr_' + wpNum);
            const spo2 = gv('wp_spo2_' + wpNum);
            const airo2 = sv('wp_airo2_' + wpNum);
            const consciousness = sv('wp_consciousness_' + wpNum);

            if(isChild) {
                // PEWS
                const hr = gv('wp_hr_peds_' + wpNum);
                const respEffort = sv('wp_resp_effort_' + wpNum);

                if (respEffort === 'mild') score += 1;
                else if (respEffort === 'moderate') score += 2;
                else if (respEffort === 'severe') score += 3;

                if (!isNaN(rr)) {
                    if (rr > 60 || rr < 10) score += 3;
                    else if (rr > 50) score += 2;
                    else if (rr > 40) score += 1;
                }
                if (!isNaN(hr)) {
                    if (hr > 180 || hr < 60) score += 3;
                    else if (hr > 160) score += 2;
                    else if (hr > 140) score += 1;
                }
                if (!isNaN(spo2)) {
                    if (spo2 < 90) score += 3;
                    else if (spo2 <= 93) score += 2;
                    else if (spo2 <= 95) score += 1;
                }
                if (airo2 === 'oxygen') score += 2;
                if (consciousness === 'unresponsive') score += 3;
                else if (consciousness === 'pain') score += 2;
                else if (consciousness === 'voice') score += 1;
            } else {
                // NEWS
                const sbp = gv('wp_sbp_' + wpNum);
                const pulse = gv('wp_pulse_' + wpNum);
                const temp = gv('wp_temp_' + wpNum);

                if (!isNaN(rr)) {
                    if (rr <= 8 || rr >= 25) score += 3;
                    else if (rr >= 21 && rr <= 24) score += 2;
                    else if (rr >= 9 && rr <= 11) score += 1;
                }
                if (!isNaN(spo2)) {
                    const isCOPD = document.getElementById('copd')?.checked || false;
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
                if (airo2 === 'oxygen') score += 2;
                if (!isNaN(sbp)) {
                    if (sbp <= 90 || sbp >= 220) score += 3;
                    else if (sbp >= 91 && sbp <= 100) score += 2;
                    else if (sbp >= 101 && sbp <= 110) score += 1;
                }
                if (!isNaN(pulse)) {
                    if (pulse <= 40 || pulse >= 131) score += 3;
                    else if (pulse >= 111 && pulse <= 130) score += 2;
                    else if ((pulse >= 41 && pulse <= 50) || (pulse >= 91 && pulse <= 110)) score += 1;
                }
                if (consciousness !== 'alert') score += 3;
                if (!isNaN(temp)) {
                    if (temp <= 35.0) score += 3;
                    else if (temp >= 39.1) score += 2;
                    else if ((temp >= 35.1 && temp <= 36.0) || (temp >= 38.1 && temp <= 39.0)) score += 1;
                }
            }

            // Update UI
            const scoreEl = document.getElementById('wp-score-value-' + wpNum);
            const bannerEl = document.getElementById('wp-score-banner-' + wpNum);
            const riskEl = document.getElementById('wp-score-risk-' + wpNum);
            if(scoreEl) scoreEl.innerText = score;
            if(riskEl) riskEl.innerText = getRiskLabel(score, isChild);

            if(bannerEl) {
                bannerEl.className = 'score-banner';
                if (score >= 7) bannerEl.classList.add('score-high');
                else if (score >= 5 || (isChild && score >= 4)) bannerEl.classList.add('score-medium');
                else if (score > 0) bannerEl.classList.add('score-low');
            }
        }

        // Initialize display state
        toggleAssessmentType();
        toggleO2Options();
        toggleVitalOptions();

        setupSearchableSelect('ward', 'wardDropdown', 'wardContainer');
        setupSearchableSelect('destination', 'destinationDropdown', 'destinationContainer');
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
            else if (o2TypeVal === 'ventilator') flowText = 'Vent MV ' + txt('vent_mv') + ' L/min';
            else flowText = txt('o2_flow_rate') + (txt('o2_flow_rate') ? ' L/min' : '');
        }

        // Severity result
        const severityEl = document.querySelector('#severity-result h3');
        const severityText = severityEl ? severityEl.innerText : '-';

        // Personnel
        let personnel = [];
        if (chk('pers_doctor')) personnel.push('แพทย์' + (txt('val_name_doctor') ? ` (${txt('val_name_doctor')})` : ''));
        if (chk('pers_rn'))     personnel.push('พยาบาล' + (txt('val_name_rn') ? ` (${txt('val_name_rn')})` : ''));
        if (chk('pers_pn'))     personnel.push('ผู้ช่วยพยาบาล' + (txt('val_name_pn') ? ` (${txt('val_name_pn')})` : ''));
        if (chk('pers_aide'))   personnel.push('ผู้ช่วยเหลือคนไข้' + (txt('val_name_aide') ? ` (${txt('val_name_aide')})` : ''));
        // Checklist
        const chklist = [];
        
        let tubeList = [];
        if (chk('chk_tube_ng')) tubeList.push('NG/OG');
        if (chk('chk_tube_peg')) tubeList.push('PEG');
        if (chk('chk_tube_icd')) tubeList.push('ICD' + (txt('val_tube_icd') ? ' (' + txt('val_tube_icd') + ')' : ''));
        if (chk('chk_tube_redivac')) tubeList.push('Redivac' + (txt('val_tube_redivac') ? ' (' + txt('val_tube_redivac') + ')' : ''));
        if (chk('chk_tube_pcn')) tubeList.push('PCN' + (txt('val_tube_pcn') ? ' (' + txt('val_tube_pcn') + ')' : ''));
        if (chk('chk_tube_foley')) tubeList.push('Foley');
        if (chk('chk_tube_other') && txt('val_tube_other')) tubeList.push(txt('val_tube_other'));
        if (tubeList.length > 0) chklist.push('✓ Tube/Drain: ' + tubeList.join(', '));

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
        if (chk('chk_tube_ng'))    tubes.push('NG/OG');
        if (chk('chk_tube_peg'))   tubes.push('PEG');
        if (chk('chk_tube_icd'))   tubes.push('ICD' + (txt('val_tube_icd') ? ' (' + txt('val_tube_icd') + ')' : ''));
        if (chk('chk_tube_redivac')) tubes.push('Redivac' + (txt('val_tube_redivac') ? ' (' + txt('val_tube_redivac') + ')' : ''));
        if (chk('chk_tube_pcn'))   tubes.push('PCN' + (txt('val_tube_pcn') ? ' (' + txt('val_tube_pcn') + ')' : ''));
        if (chk('chk_tube_foley')) tubes.push('Foley');
        if (chk('chk_tube_other') && txt('val_tube_other')) tubes.push(txt('val_tube_other'));

        // Billing / Rights
        const billStatus = radioLabel('billing_status');
        const rightsStatus = radio('rights_status');
        let rightsText = '-';
        if (rightsStatus === 'has_rights') rightsText = selectText('rights_has_select');
        else if (rightsStatus === 'no_rights') rightsText = selectText('rights_no_select');

        // Waypoints
        const waypointsData = [];
        document.querySelectorAll('.waypoint-entry').forEach((wp, index) => {
            const wpId = wp.id.replace('wp-entry-', '');
            const loc = txt('wp_location_' + wpId) || `จุดแวะที่ ${index + 1}`;
            const tIn = txt('wp_time_in_' + wpId) || '-';
            const tOut = txt('wp_time_out_' + wpId) || '-';
            const wScore = document.getElementById('wp-score-value-' + wpId)?.innerText || '-';
            waypointsData.push({ loc, tIn, tOut, wScore });
        });

        const now = new Date();
        const dateStr = now.toLocaleDateString('th-TH', {day:'2-digit', month:'2-digit', year:'numeric'});
        const timeStr = now.toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit'});

        // Score color
        const scoreNum = parseInt(scoreLive) || 0;
        const scoreColor = scoreNum >= 7 ? '#b91c1c' : scoreNum >= 5 ? '#b45309' : scoreNum > 0 ? '#047857' : '#1f2937';
        const scoreBg   = scoreNum >= 7 ? '#fee2e2' : scoreNum >= 5 ? '#fef3c7' : scoreNum > 0 ? '#d1fae5' : '#eff6ff';

        // Destination score color (Bug #8: separate from source)
        const destScoreNum = parseInt(destScoreLive) || 0;
        const destScoreColor = destScoreNum >= 7 ? '#b91c1c' : destScoreNum >= 5 ? '#b45309' : destScoreNum > 0 ? '#047857' : '#1f2937';
        const destScoreBg   = destScoreNum >= 7 ? '#fee2e2' : destScoreNum >= 5 ? '#fef3c7' : destScoreNum > 0 ? '#d1fae5' : '#eff6ff';

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

  <h1>📋 Safety Transfer Summary — ใบเคลื่อนย้ายผู้ป่วย</h1>
  <div class="meta"><span>วันที่/เวลา: ${dateStr} ${timeStr}</span><span>เวลาออก: ${txt('log_time_out') || '-'} → เวลาถึง: ${txt('log_time_in') || '-'}</span></div>

  <!-- Row 1: Patient info + Score -->
  <div class="section">
    <div class="sec-title">ส่วนที่ 1 — ข้อมูลผู้ป่วย & ${scoreType} Score</div>
    <div class="grid g4">
      <div class="box"><span class="lbl">HN</span><span class="val">${txt('hn') || (() => { const el = document.querySelector('input[placeholder="ระบุ HN"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">ชื่อ</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุชื่อผู้ป่วย"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">Ward</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุหน่วยงานต้นทาง"]'); return el ? el.value || '-' : '-'; })()}</span></div>
      <div class="box"><span class="lbl">ปลายทาง</span><span class="val">${(() => { const el = document.querySelector('input[placeholder="ระบุหน่วยงานปลายทาง"]'); return el ? el.value || '-' : '-'; })()}</span></div>
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
    <div class="sec-title">ส่วนที่ 5 — Handoff Information (ย้ายหอผู้ป่วย)</div>
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
      <div class="sec-title">ส่วนที่ 6 — ประเมินซ้ำปลายทาง</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px;">
        <div class="box"><span class="lbl">RR</span><span class="val">${txt('dest_rr')}</span></div>
        <div class="box"><span class="lbl">SpO2</span><span class="val">${txt('dest_spo2')}</span></div>
        <div class="box"><span class="lbl">SBP/Pulse</span><span class="val">${txt('dest_sbp')}/${txt('dest_pulse')}</span></div>
        <div class="box"><span class="lbl">Temp</span><span class="val">${txt('dest_temp')}</span></div>
        <div class="box"><span class="lbl">Consciousness</span><span class="val">${selectText('dest_consciousness')}</span></div>
        <div class="score-box" style="background:${destScoreBg}; border:1px solid ${destScoreColor}40; padding:3px;">
          <span class="lbl" style="color:${destScoreColor}">${scoreType} ปลายทาง</span>
          <span class="badge" style="background:${destScoreColor}; color:white;">${destScoreLive}</span>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="sec-title">ส่วนที่ 7 — Transfer Log & จุดแวะ</div>
      <div class="grid" style="grid-template-columns:1fr 1fr;gap:3px; margin-bottom: 3px;">
        <div class="box"><span class="lbl">เวลาส่งออก</span><span class="val">${txt('log_time_out')}</span></div>
        <div class="box"><span class="lbl">ผู้ส่ง</span><span class="val">${txt('log_sender')}</span></div>
        <div class="box"><span class="lbl">เวลาถึงปลายทาง</span><span class="val">${txt('log_time_in')}</span></div>
        <div class="box"><span class="lbl">ผู้รับ</span><span class="val">${txt('log_receiver')}</span></div>
      </div>
      ${waypointsData.length > 0 ? 
        `<div style="font-size: 7.5pt; color: #1e40af; font-weight: 600; margin-top: 4px; margin-bottom: 2px;">📍 ข้อมูลจุดแวะ (Waypoints)</div>
         <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 3px;">
         ${waypointsData.map(wp => `
            <div class="box" style="border-style: dashed; border-color: #d97706; background: #fffbeb;">
                <span class="lbl" style="color: #92400e;">${wp.loc}</span>
                <span class="val" style="font-size: 7.5pt;">In: <strong>${wp.tIn}</strong> | Out: <strong>${wp.tOut}</strong> | Score: <strong>${wp.wScore}</strong></span>
            </div>
         `).join('')}
         </div>` 
      : ''}
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

    <!-- Urgent OR Modal -->
    <div id="urgentModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🚨 Flow การรับผู้ป่วย Case ผ่าตัดด่วนที่สุดนอกอาคาร</h2>
                <button type="button" class="modal-close" onclick="closeUrgentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>(โรคปอด/ ตึกสงฆ์/ ฟื้นฟู/ จิตเวช)</strong></p>
                <ol>
                    <li>ห้องผ่าตัดโทรแจ้งหอผู้ป่วยให้ส่ง Case ผ่าตัดด่วนที่สุดมาห้องผ่าตัด</li>
                    <li>หอผู้ป่วยโทรแจ้งหน่วยเปล (เบอร์โทร 35692) ให้ครบถ้วนว่ามี Case ผ่าตัดด่วนที่สุดส่งเข้าห้องผ่าตัด (โทรแจ้งก่อนแล้วลงระบบ Day work)</li>
                    <li>หน่วยเปลโทรประสานหน่วยยานพาหนะ (เบอร์โทร 35235/ 36235) เพื่อไปรับผู้ป่วย</li>
                    <li>เจ้าหน้าที่หน่วยยานพาหนะนำรถมารับเจ้าหน้าที่หน่วยเปลที่ ER เก่า</li>
                    <li>เมื่อถึงอาคารหอผู้ป่วย เจ้าหน้าที่หน่วยเปลนำเปลขึ้นไปรับผู้ป่วยที่หอผู้ป่วย</li>
                    <li>เจ้าหน้าที่หอผู้ป่วยประเมินตามแนวทาง Safety transfer และนำส่งผู้ป่วยร่วมกับเจ้าหน้าที่หน่วยเปลและยานพาหนะจนถึงห้องผ่าตัด</li>
                </ol>
                <hr>
                <p><strong>หมายเหตุ**</strong></p>
                <p style="text-indent: 2rem;">
                    1. <strong>Case ผ่าตัดด่วนที่สุด</strong> หมายถึง Case ที่มีความเร่งด่วนในการผ่าตัดและควรได้รับการผ่าตัด Immediate ภายในระยะเวลา 30 นาที เนื่องจากภาวะวิกฤติที่มีความล้มเหลวของระบบการทำงานร่างกายอย่างรุนแรง มีความเสี่ยงต่อการเสียชีวิตหรือสูญเสียอวัยวะอย่างทันที เช่น หลอดเลือดแดงใหญ่ในช่องท้องแตก (Ruptured Abdominal Aorta Aneurysm), อุบัติเหตุในช่องท้อง/ทรวงอกที่เสียเลือดมาก (Major abdominal/thoracic trauma with hemodynamic compromise), บาดเจ็บศีรษะ (Fast track NEURO), กระดูกหักร่วมกับเส้นประสาทหรือหลอดเลือดเสียหาย (Fracture with major neurovascular deficit), ภาวะความดันในกล้ามเนื้อสูงมาก (Compartment syndrome), โรคหรือความผิดปกติที่อุดกั้นทางเดินหายใจ (Airway pathology with impending airway obstruction), การผ่าตัดคลอดฉุกเฉินที่ต้องทำทันทีเนื่องจากภาวะคุกคามต่อชีวิตแม่หรือทารก เช่น ทารกมีสัญญาณชีพไม่ดี (Fetal non-reassuring), ภาวะสายสะดือลงมาอยู่ต่ำกว่าส่วนนำของทารกในครรภ์ (Prolapse cord), รกลอกก่อนกำหนด (Placental abruption) เป็นต้น แบ่งตาม Emergency Surgery Priority Categories หรือ ประเภทความเร่งด่วนของการผ่าตัดฉุกเฉิน (Department of Health. Queensland. Emergency Surgery Access Guideline, 2017, NCEPOD, The NCEPOD Classification of intervention, 2019)
                </p>
                <p style="text-indent: 2rem;">
                    2. กรณีที่หอผู้ป่วยไม่สามารถส่งผู้ป่วยมายังห้องผ่าตัดได้ด้วยตนเอง เนื่องจากมีภาระงานเร่งด่วนหรือเหตุสุดวิสัย ให้โทรแจ้งเจ้าหน้าที่ห้องผ่าตัด (เบอร์โทร 35776/ 35777) เพื่อประสานการรับผู้ป่วยโดยต้องมีการสื่อสารที่ชัดเจน
                </p>
            </div>
        </div>
    </div>

    <script>
        function openUrgentModal() {
            const modal = document.getElementById('urgentModal');
            modal.style.display = 'flex';
            // Slight delay to allow display:flex to apply before adding class for transition
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        function closeUrgentModal() {
            const modal = document.getElementById('urgentModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300); // match transition duration
        }

        // Close when clicking outside
        document.getElementById('urgentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUrgentModal();
            }
        });
    </script>
</body>
</html>