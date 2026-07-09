<?php
$payload = json_encode([
    'hn' => 'full123',
    'patient_name' => 'John Doe',
    'ward' => 'ER',
    'destination' => 'OR',
    'age_group' => 'adult',
    'rr' => '20',
    'spo2' => '98',
    'airo2' => 'room',
    'consciousness' => 'alert',
    'sbp' => '120',
    'pulse' => '80',
    'temp' => '37.0',
    'copd' => 0,
    'hr_peds' => '',
    'resp_effort' => 'normal',
    'news_score' => 0,
    'pews_score' => 0,
    'comm' => 'yes',
    'breath' => 'no_o2',
    'vitals_stable' => 1,
    'sedation' => 'no',
    'severity_level' => 1,
    'waypoints' => [
        [
            'location_name' => 'X-Ray',
            'time_arrived' => '10:00',
            'time_departed' => '10:15',
            'rr' => '20',
            'spo2' => '98'
        ]
    ]
]);

$ch = curl_init('http://localhost/ETransfer/DB_transfer.php/transfers');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
curl_close($ch);
echo $response;
