<?php
// 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// ------------------------------------------------------------------
// 
$API_KEY = "AIzaSyAX_NNFxSNNW9E9RgYpTtRjqhLHg97JXVQ"; 
// ------------------------------------------------------------------

// 
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if (!$data) {
    echo json_encode(["error" => "ไม่มีข้อมูลส่งมาประมวลผล"]);
    exit;
}

// 
$mode = isset($data['form_mode']) ? $data['form_mode'] : '';
$promptText = "";

if ($mode === 'safety_check') {
    $promptText = "คุณคือผู้เชี่ยวชาญด้านการเคลื่อนย้ายผู้ป่วยวิกฤต โรงพยาบาลมหาราชนครเชียงใหม่ จงวิเคราะห์ข้อมูล JSON ต่อไปนี้ร่วมกับไฟล์เอกสารแนวปฏิบัติที่แนบมา โดยมีเงื่อนไขสำคัญดังนี้:
[กฎการประเมินร่วมระหว่างคู่มือและความรู้ของ AI]
1. ให้ใช้เอกสารแนวปฏิบัติโรงพยาบาลเป็นเกณฑ์พื้นฐาน
2. ให้คุณใช้ 'วิจารณญาณทางการแพทย์ของคุณเอง (AI Clinical Judgment) วิเคราะห์ข้อความในช่อง risk_other (ความเสี่ยงอื่นๆ) อย่างละเอียดเป็นอันดับแรกสุด 
3. หากช่อง risk_other ระบุภาวะที่เสี่ยงต่อชีวิตหรือระบบสัญญาณชีพไม่คงที่ (เช่น เคส CRTD/AICD ช็อกกระตุก, มีภาวะ Arrhythmia, หรือข้อความเตือนภัยวิกฤตอื่นๆ) แม้ว่าระบบหน้าเว็บจะคำนวณความรุนแรงได้เพียง ระดับ 2 แต่ให้คุณ 'แย้งระบบ' และ 'ยกระดับความรุนแรงทางการแพทย์เป็นระดับวิกฤตสูง (ระดับ 3 หรือ 4) ทันที' ตามความเหมาะสมในเชิงคลินิก
[รูปแบบและโครงสร้างหัวข้อคำตอบ (ใช้สั้น กระชับ เป็น Bullet points ไม่เกริ่นนำ)]
- ⚠️ ข้อควรระวังพิเศษและการปรับระดับความรุนแรง (หากมีข้อจำกัดวิกฤตจาก risk_other ให้ขึ้นเตือนตรงนี้เป็นอันดับแรก พร้อมเหตุผลทางแพทย์ว่าทำไมต้องระวังเป็นพิเศษ หรือทำไมต้องยกระดับความรุนแรงเกินกว่าที่ระบบคำนวณ)
- 📊 สรุปสัญญาณชีพ (NEWS/PEWS) และระดับความรุนแรง (Severity evel)
- 🧑‍⚕️ ความเหมาะสมของทีมบุคลากรที่ร่วมเดินทาง (หากมีการยกระดับความรุนแรง ให้แนะนำทีมใหม่ที่เหมาะสมทันที เช่น ต้องมีแพทย์ร่วมทาง)
- 🩺 ความปลอดภัยของท่อและสายระบาย (Tubes/Drains)
- 💨 ความเพียงพอของออกซิเจน (แจ้งเตือนหาก O2 เหลือต่ำกว่า 30 นาที หรือ 45 นาทีในเคส Ventilator)

ข้อมูล JSON ที่ต้องประเมิน: \n" . json_encode($data, JSON_UNESCAPED_UNICODE);
} else if ($mode === 'handoff') {
    $promptText = "คุณคือพยาบาลวิชาชีพผู้เชี่ยวชาญ กรุณาวิเคราะห์ข้อมูลการส่งเวร (Handoff Information) ต่อไปนี้ 
    และทำการสรุปข้อมูลการส่งเวรตามหลักการ ISBAR (Identify, Situation, Background, Assessment, Recommendation) 
    โดยเน้นประเด็นสำคัญที่ปลายทางต้องเฝ้าระวัง ให้กระชับ อ่านง่าย และจัดรูปแบบโดยแบ่งเป็นหัวข้อ ISBAR อย่างชัดเจน: \n" . json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    $promptText = "กรุณาวิเคราะห์ข้อมูลผู้ป่วยต่อไปนี้: \n" . json_encode($data, JSON_UNESCAPED_UNICODE);
}

// ใช้ Gemini
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key=" . $API_KEY;

$parts = [
    ["text" => $promptText]
];

// ถ้าเป็นโหมด safety_check ให้แนบไฟล์ PDF แนวปฏิบัติไปด้วย
if ($mode === 'safety_check') {
    $pdfPath = __DIR__ . "/แนวปฏิบัติการเคลื่อนย้ายผู้ป่วยภายในโรงพยาบาลมหาราชนครเชียงใหม่.pdf";
    if (file_exists($pdfPath)) {
        $pdfData = base64_encode(file_get_contents($pdfPath));
        $parts[] = [
            "inlineData" => [
                "mimeType" => "application/pdf",
                "data" => $pdfData
            ]
        ];
    }
}

$payload = [
    "contents" => [
        [
            "parts" => $parts
        ]
    ]
];

//
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

// 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch); is deprecated in PHP 8.0+ as CurlHandle objects are destroyed automatically
unset($ch);

// 
if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "ไม่สามารถอ่านคำตอบจาก AI ได้";
    echo json_encode(["result" => $aiText]);
} else {
    // 
    echo json_encode(["error" => "ไม่สามารถเชื่อมต่อ AI ได้ (รหัสข้อผิดพลาด: $httpCode) - $response"]);
}
?>