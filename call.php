<?php
$secretKey = "ECC4E54DBA738857B84A7EBC6B5DC7187B8DA68750E88AB53AAA41F548D6F2D9";

$responsePayload = [
    "merchantID" => "JT01",
    "invoiceNo" => "RUN-1769764463", // Use the Invoice ID from your previous test
    "amount" => 1000.00,
    "currencyCode" => "SGD",
    "respCode" => "0000",           // 0000 = Success
    "respDesc" => "Success",
    "transactionDateTime" => date("YmdHis"),
    "status" => "A"                // A = Authorized (Paid)
];

function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

$header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
$payload = base64UrlEncode(json_encode($responsePayload));
$signature = base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secretKey, true));

echo $header . "." . $payload . "." . $signature;