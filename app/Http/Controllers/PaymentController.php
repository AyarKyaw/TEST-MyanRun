<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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

    public function verifyPayment(Request $request)
    {
        // 1. Validation
        $request->validate([
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'amount'       => 'required',
            'bib_name'     => 'required',
            'category'     => 'required',
        ]);

        // 2. Retrieve session data
        $order = session('pending_registration');

        if (!$order) {
            return redirect()->route('athlete.register')->with('error', 'Session expired. Please try again.');
        }

        // 3. Handle File Upload
        $saveDir = public_path('uploads/payments');
        if (!\File::exists($saveDir)) {
            \File::makeDirectory($saveDir, 0777, true);
        }

        $imageName = 'slip_race_' . time() . '.' . $request->payment_slip->extension();
        $request->payment_slip->move($saveDir, $imageName);

        // 4. Create the Ticket (REMOVED payment_slip to match your DB)
        Ticket::create([
            'athlete_id'       => $order['athlete_id'], 
            'bib_name'         => $request->bib_name,
            'bib_number'       => $order['bib_number'], 
            'category'         => $order['category'] ?? $request->category,
            'price'            => (int)str_replace(',', '', $request->amount),
            'event'            => $order['event'], 
            't_shirt_size'     => $order['t_shirt_size'] ?? 'M',
            'experience_level' => $order['exp_level'] ?? 'Beginner', // Added this as it's in your DB
            'transaction_id'   => $imageName, // The image name goes here!
            'status'           => 'pending', 
        ]);

        // 5. Clear session
        session()->forget('pending_registration');

        // 6. Redirect
        return redirect()->route('user.dashboard')->with('success', 'Registration submitted! We will verify your payment slip soon.');
    }
}