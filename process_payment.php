<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["ResponseDescription" => "Invalid Request Method"]);
    exit();
}

// 1. Capture payload variables from our frontend Javascript 
$phone  = trim($_POST['phone']);
$amount = trim($_POST['amount']);

// 2. Official Safaricom Sandbox Credentials (Use these exact test credentials)
$consumerKey    = "c7ArX73kGA9w5GAV0uK89gG7hGgqD8g7"; 
$consumerSecret = "M6gqX73kGA9w5GAV";
$businessShortCode = "174379"; // Safaricom test Paybill number
$passkey        = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";

// 3. Generate Time and Security Password
$timestamp = date('YmdHis');
$password  = base64_encode($businessShortCode . $passkey . $timestamp);

// 4. Request the Secure OAuth Access Token from Daraja
$authUrl = "https://safaricom.co.ke";
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);

// --- LOOK AT YOUR FIRST cURL BLOCK (OAuth Request) ---
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $authUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials, "Content-Type: application/json"]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Add these two lines to your FIRST cURL block if they are missing:
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

$authResponse = curl_exec($curl);
$accessToken  = json_decode($authResponse)->access_token;
curl_close($curl);


if (!$accessToken) {
    echo json_encode(["ResponseCode" => "1", "ResponseDescription" => "OAuth Authentication Failed"]);
    exit();
}

// 5. Build the STK Push Payload Request Data
$stkUrl = "https://safaricom.co.ke";
$curl_post_data = [
    'BusinessShortCode' => $businessShortCode,
    'Password'          => $password,
    'Timestamp'         => $timestamp,
    'TransactionType'   => 'CustomerPayBillOnline',
    'Amount'            => $amount,
    'PartyA'            => $phone,
    'PartyB'            => $businessShortCode,
    'PhoneNumber'       => $phone,
    'CallBackURL'       => 'https://requestcatcher.com', // Standard sandbox placeholder
    'AccountReference'  => 'IronPulseGym',
    'TransactionDesc'   => 'Gym Inventory Purchase'
];

// 6. Transmit Request Payload to Safaricom
$stkCurl = curl_init();
curl_setopt($stkCurl, CURLOPT_URL, $stkUrl);
curl_setopt($stkCurl, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $accessToken, 
    "Content-Type: application/json"
]);
curl_setopt($stkCurl, CURLOPT_POST, true);
curl_setopt($stkCurl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
curl_setopt($stkCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($stkCurl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($stkCurl, CURLOPT_SSL_VERIFYHOST, false); // Added to bypass local SSL errors entirely

$stkResponse = curl_exec($stkCurl);
curl_close($stkCurl);

// 7. Pipe the exact status code back to your store layout view
echo $stkResponse;
?>



$stkResponse = curl_exec($stkCurl);
curl_close($stkCurl);

// 7. Pipe the exact status code back to your store layout view
echo $stkResponse;
?>
