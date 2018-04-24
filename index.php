<?php

$DESTINATION_URL = 'http://prover.io/kyc/migration-form.php';
$REDIRECT_URL_START = 'http://prover.io';

$data = $_POST;
foreach ($_FILES as $key => $files) {
    foreach ($files['tmp_name'] as $i => $tmp_name) {
        if (is_file($tmp_name)) {
            $new_file = tempnam('/tmp', 'chlp_');
            if (move_uploaded_file($tmp_name, $new_file)) {
                $curlFile = curl_file_create($new_file, $files['type'][$i], $files['name'][$i]);
                $data["{$key}[$i]"] = $curlFile;
            }
        }
    }
}

//$data['additional'] = 'var';

$ch = curl_init($DESTINATION_URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

$REDIRECT_URL_END = @json_decode($response);

header("Location: $REDIRECT_URL_START/$REDIRECT_URL_END");