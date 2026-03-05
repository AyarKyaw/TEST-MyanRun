<?php

// 1. Setup Credentials
$secretKey = "ECC4E54DBA738857B84A7EBC6B5DC7187B8DA68750E88AB53AAA41F548D6F2D9";
$merchantID = "JT01";

// 2. Setup Payload (Updated with current timestamp for Sandbox)
$payload = [
    "merchantID" => $merchantID,
    "invoiceNo" => "RUN-" . time(), // Unique ID using current time
    "description" => "item 1",
    "amount" => 1000.00,
    "currencyCode" => "SGD",
    "nonceStr" => bin2hex(random_bytes(8))
];

// 3. Generate JWT (Manual HS256)
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

$header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
$base64Header = base64UrlEncode($header);
$base64Payload = base64UrlEncode(json_encode($payload));

// Create Signature using the Secret Key
$signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secretKey, true);
$base64Signature = base64UrlEncode($signature);

$jwt = "$base64Header.$base64Payload.$base64Signature";

// 4. Send Request using PHP's built-in cURL (No Guzzle needed)
$ch = curl_init('https://sandbox-pgw.2c2p.com/payment/4.3/paymentToken');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["payload" => $jwt]));

$response = curl_exec($ch);
curl_close($ch);

// 5. Show Result
echo "--- NEW JWT GENERATED ---\n" . $jwt . "\n\n";
echo "--- SERVER RESPONSE ---\n" . $response . "\n";