<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PaymentController extends Controller
{
    public function handleKbzCallback(Request $request)
    {
        // 1. Get the raw data from KBZ
        $payload = $request->json()->all();
        $data = $payload['Request']; // KBZ usually wraps data in a 'Request' object
        
        $receivedSign = $data['sign'];
        unset($data['sign']); // Remove sign before re-calculating to verify

        // 2. Verify Signature
        if ($this->verifySignature($data, $receivedSign)) {
            
            // 3. Check if payment was successful
            if ($data['result'] === 'SUCCESS') {
                $order = Order::where('order_id', $data['merch_order_id'])->first();
                
                if ($order && $order->status !== 'paid') {
                    $order->update(['status' => 'paid']);
                    Log::info("Payment Successful for Order: " . $data['merch_order_id']);
                }
            }

            // 4. MUST return this exact JSON so KBZ stops calling you
            return response()->json(['Response' => ['return_code' => 'SUCCESS']]);
        }

        Log::error("Invalid KBZ Signature detected!");
        return response()->json(['Response' => ['return_code' => 'FAIL']]);
    }

    private function verifySignature($params, $receivedSign)
    {
        ksort($params); // Sort A-Z
        $stringA = "";
        foreach ($params as $key => $value) {
            if ($value != "" && !is_array($value)) {
                $stringA .= $key . "=" . $value . "&";
            }
        }
        $stringSignTemp = $stringA . "key=" . env('KBZ_APP_KEY');
        $expectedSign = strtoupper(hash('sha256', $stringSignTemp));

        return hash_equals($expectedSign, $receivedSign);
    }
}