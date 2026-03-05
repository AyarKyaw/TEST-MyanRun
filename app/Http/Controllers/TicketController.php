<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function showTicket()
    {
        return view('ticket'); 
    }

    public function dashboard()
    {
        // Fetch all records from the Tickets table
        $customers = \App\Models\Ticket::all(); 
        $totalCount = \App\Models\Ticket::count();

        return view('dashboard.ticket-sales.ticket', compact('customers', 'totalCount'));
    }

    // Helper method to avoid repeating code
    private function getTicketData($id) {
        $ticket = Ticket::findOrFail($id);
        $user = auth()->user();

        // 1. Process Logo
        $logoPath = public_path('images/MyanRun_Orange_RM2.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoBase64 = 'data:image/png;base64,' . $logoData;
        }

        // 2. Process QR Code
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-{$ticket->id}";
        $qrData = base64_encode(file_get_contents($qrUrl));
        $qrBase64 = 'data:image/png;base64,' . $qrData;

        return [
            'ticket'   => $ticket,
            'user'     => $user,
            'logo'     => $logoBase64,
            'qrCode'   => $qrBase64,
        ];
    }

    public function downloadPDF($id) {
        $data = $this->getTicketData($id);
        $pdf = Pdf::loadView('pdf', $data);
        return $pdf->download("MyanRun_Ticket_{$id}.pdf");
    }

    public function previewPDF($id) 
    {
        $data = $this->getTicketData($id); // Now it has the logo!
        $pdf = Pdf::loadView('pdf', $data);

        return $pdf->stream('ticket-' . $id . '.pdf');
    }

    public function initiatePayment($id)
    {
        $orderData = session('checkout_data');

        if (!$orderData) {
            return redirect()->route('athlete.register')->with('error', 'Session expired.');
        }

        // 1. Create the Ticket as 'pending'
        $rawPrice = $orderData['price'] ?? 0;
        $price = (int) preg_replace('/[^0-9]/', '', $rawPrice);

        $ticket = Ticket::create([
            'runner_id' => $id,
            'event'     => $orderData['event'] ?? 'Official Race 2026',
            'category'  => $orderData['category'],
            'price'     => $price,
            'status'    => 'pending',
        ]);

        // --- FOR LOCAL TESTING ONLY: WE SKIP THE REAL API CALL ---
        /* $kbzData = [
            'merch_code'     => env('KBZ_MERCH_CODE'),
            'appid'          => env('KBZ_APP_ID'),
            'merch_order_id' => (string)$ticket->id,
            'total_amount'   => (string)$orderData['price'],
            'trade_type'     => 'PAY_BY_QRCODE',
            'title'          => 'MyanRun Registration',
            'nonce_str'      => Str::random(32),
            'method'         => 'precreate',
            'notify_url'     => url('/payment/kbz/callback'),
        ];

        $kbzData['sign'] = $this->generateKbzSignature($kbzData);

        // This line is what caused the error because env('KBZ_API_URL') is null
        $response = \Illuminate\Support\Facades\Http::post(env('KBZ_API_URL'), [
            'Request' => $kbzData
        ]);
        $result = $response->json();
        */

        // --- MANUALLY CREATE A SUCCESS RESULT FOR TESTING ---
        $result = [
            'Response' => [
                'return_code' => 'SUCCESS', 
                'qr_code' => 'TEST_PAYMENT_DATA_FOR_' . $ticket->id
            ]
        ];

        if (isset($result['Response']['return_code']) && $result['Response']['return_code'] === 'SUCCESS') {
            session()->forget('checkout_data');
            $qrString = $result['Response']['qr_code'];
            $ticket->update(['qr_code_str' => $qrString]);
            return view('payment.kbz_qr', compact('qrString', 'ticket'));
        }

        return back()->with('error', 'KBZPay Initialization Failed.');
    }
    /**
     * Signature helper for KBZPay
     */
    private function generateKbzSignature($params) {
        ksort($params);
        $stringA = "";
        foreach ($params as $key => $value) {
            if ($value != "" && !is_array($value)) {
                $stringA .= $key . "=" . $value . "&";
            }
        }
        $stringSignTemp = $stringA . "key=" . env('KBZ_APP_KEY');
        return strtoupper(hash('sha256', $stringSignTemp));
    }

    public function kbzCallback(Request $request)
    {
        $payload = $request->all();
        Log::info('KBZ Callback received:', $payload);

        // 1. Check if the payload has the 'Request' wrapper from KBZ
        if (isset($payload['Request'])) {
            $data = $payload['Request'];
            
            // Use 'merch_order_id' which you pass when creating the QR
            $orderId = $data['merch_order_id'] ?? null;

            if ($data['result'] === 'SUCCESS' && $orderId) {
                
                // 2. Find the ticket. Note: Make sure 'id' matches what you sent to KBZ
                $ticket = Ticket::find($orderId);

                if ($ticket && $ticket->status !== 'confirmed') {
                    $ticket->update([
                        'status' => 'confirmed',
                        // Store the KBZ transaction reference for your records
                        'transaction_id' => $data['kbz_ref_no'] ?? null 
                    ]);
                    Log::info("Ticket #{$orderId} successfully paid and confirmed.");
                }
            } else {
                Log::warning("KBZ payment failed for order {$orderId}");
            }
        }

        // 3. You MUST return this exact JSON so KBZ stops retrying
        return response()->json([
            'Response' => [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            ]
        ]);
    }

    /**
     * New Method: Use this for your Static Site (myanrun.com) 
     * to check if payment is done.
     */
    public function checkStatus($id)
    {
        $ticket = Ticket::find($id);
        
        if (!$ticket) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'paid' => $ticket->status === 'confirmed',
            'status' => $ticket->status
        ]);
    }

    public function showReviewPage()
    {
        $order = session('checkout_data');
        $user = auth()->user();
        if (!$order) {
            return redirect()->route('athlete.register')->with('error', 'Session expired.');
        }

        // 1. Clean the price (removes $ and commas)
        $rawPrice = $order['price'] ?? 0;
        $subtotal = (float) preg_replace('/[^0-9.]/', '', $rawPrice);

        // 2. Perform the math
        $serviceFee = 5.00; 
        $total = $subtotal + $serviceFee;
        $fullName = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");

        return view('checkout', compact('order', 'subtotal', 'serviceFee', 'total', 'fullName'));
    }
}