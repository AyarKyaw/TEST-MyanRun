<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Laranex\LaravelMyanmarPayments\LaravelMyanmarPayments;

class TicketController extends Controller
{
    public function showTicket()
    {
        return view('ticket.ticket'); 
    }

    public function dashboard()
    {
        // Fetch all records from the Tickets table
        $customers = \App\Models\Ticket::with('athlete')->orderBy('created_at', 'desc')->get();
        $totalCount = \App\Models\Ticket::count();

        return view('dashboard.ticket-sales.ticket', compact('customers', 'totalCount'));
    }

    public function approve($id)
{
    $ticket = \App\Models\Ticket::findOrFail($id);
    $ticket->status = 'approved';
    $ticket->save();

    return back()->with('success', 'Ticket approved successfully!');
}

public function reject($id)
{
    $ticket = \App\Models\Ticket::findOrFail($id);
    $ticket->status = 'rejected';
    $ticket->save();

    return back()->with('error', 'Ticket has been rejected.');
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
    if (!$orderData) return redirect()->route('athlete.register')->with('error', 'Session expired.');

    $price = (string) preg_replace('/[^0-9]/', '', $orderData['price']);
    $nonce = Str::random(32);
    $timestamp = (string)time();
    $merchOrderId = $id . '_' . $timestamp;

    // 1. Step 1: Flatten ALL non-empty values into one set (M)
    // ONLY use the fields shown in your documentation's example stringA
    $m = [
        'appid'          => env('KBZ_PAY_APP_ID'),
        'merch_code'     => env('KBZ_PAY_MERCHANT_CODE'),
        'merch_order_id' => $merchOrderId,
        'method'         => 'kbz.payment.precreate',
        'nonce_str'      => $nonce,
        'notify_url'     => env('KBZ_PAY_NOTIFY_URL'),
        'timestamp'      => $timestamp,
        'total_amount'   => $price,
        'trade_type'     => 'PAY_BY_QRCODE',
        'trans_currency' => 'MMK',
        'version'        => '1.0',
    ];

    // 2. Sort non-empty values in ascending alphabetical order
    ksort($m);

    // 3. Join into string A (key1=value1&key2=value2)
    $queries = [];
    foreach ($m as $key => $value) {
        if ($value !== "" && $value !== null) {
            $queries[] = $key . "=" . $value;
        }
    }
    $stringA = implode('&', $queries);

    // 4. Step 2: Add "&key=" and perform SHA256
    $stringToSign = $stringA . "&key=" . env('KBZ_PAY_APP_KEY');
    $sign = strtoupper(hash('sha256', $stringToSign));

    // 5. Construct the Final JSON (The "Transferred Parameters")
    $payload = [
        'Request' => [
            'timestamp'   => $timestamp,
            'notify_url'  => env('KBZ_PAY_NOTIFY_URL'),
            'nonce_str'   => $nonce,
            'sign_type'   => 'SHA256',
            'method'      => 'kbz.payment.precreate',
            'sign'        => $sign,
            'version'     => '1.0',
            'biz_content' => [
                'merch_order_id' => $merchOrderId,
                'merch_code'     => env('KBZ_PAY_MERCHANT_CODE'),
                'appid'          => env('KBZ_PAY_APP_ID'),
                'trade_type'     => 'PAY_BY_QRCODE',
                'total_amount'   => $price,
                'trans_currency' => 'MMK'
            ]
        ]
    ];

    try {
        // Use HTTP as per your XLSX previously
        $url = "http://api-uat.kbzpay.com/payment/gateway/uat/precreate";
        
        $response = Http::withoutVerifying()
            ->withBody(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 'application/json')
            ->post($url);

        $result = $response->json();

        if (isset($result['Response']['result']) && $result['Response']['result'] === 'SUCCESS') {
            $qrString = $result['Response']['qr_code'];
            return view('payment.kbz_qr', compact('qrString'));
        }

        return back()->with('error', 'KBZ Error: ' . ($result['Response']['msg'] ?? 'Auth Failed'));

    } catch (\Exception $e) {
        return back()->with('error', 'Connection Error: ' . $e->getMessage());
    }
}

private function generateKbzSignature($params) {
    // 1. Flatten the structure for signing (KBZ standard for Precreate)
    $biz = $params['biz_content'];
    
    $all = [
        'appid'           => $params['appid'],
        'merch_code'      => $params['merch_code'],
        'merch_order_id'  => $biz['merch_order_id'],
        'method'          => $params['method'],
        'nonce_str'       => $params['nonce_str'],
        'notify_url'      => $params['notify_url'],
        'timestamp'       => $params['timestamp'],
        'title'           => $biz['title'],
        'total_amount'    => $biz['total_amount'],
        'trade_type'      => $biz['trade_type'],
        'trans_currency'  => $biz['trans_currency'],
        'version'         => $params['version'],
    ];

    // 2. Sort alphabetically
    ksort($all);

    // 3. Build string: key1=value1&key2=value2
    $queries = [];
    foreach ($all as $k => $v) {
        $queries[] = $k . "=" . $v;
    }

    $stringA = implode('&', $queries);
    $stringSignTemp = $stringA . "&key=" . env('KBZ_PAY_APP_KEY');
    
    Log::debug("[KBZ_FINAL_STRING]: " . $stringSignTemp);
    
    return strtoupper(hash('sha256', $stringSignTemp));
}

    public function kbzCallback(Request $request)
    {
        $payload = $request->input('Request');
        
        if (!$payload) {
            return response()->json(['Response' => ['return_code' => 'FAIL', 'return_msg' => 'No Request Data']]);
        }

        Log::info('KBZ Callback received:', $payload);

        if (($payload['result'] ?? null) === 'SUCCESS') {
            // 3. EXTRACT THE ID: If we sent "31_1773974866", this gets "31"
            $fullOrderId = $payload['merch_order_id'];
            $parts = explode('_', $fullOrderId);
            $realTicketId = $parts[0]; 

            $ticket = Ticket::find($realTicketId); 

            if ($ticket && $ticket->status !== 'confirmed') {
                $ticket->update([
                    'status' => 'confirmed',
                    'transaction_id' => $payload['kbz_ref_no'] ?? null 
                ]);
                Log::info("Ticket #{$realTicketId} confirmed via KBZ Callback.");
            }
        }

        return response()->json([
            'Response' => [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            ]
        ]);
    }

    public function initiatePayment_s(Request $request)
    {
        $order = session('pending_registration');
        if (!$order) {
            return redirect()->route('athlete.register')->with('error', 'Session expired.');
        }

        // Prepare data for the QR page
        $paymentData = [
            'amount' => $request->total_amount, // Pass this from a hidden input or recalculate
            'transaction_id' => 'MR-' . strtoupper(uniqid()),
            'account_name' => 'Runderful Myanmar Co., Ltd',
            'kbz_pay_qr' => asset('images/payments/kbzpay-qr.png'), // Path to your QR image
        ];

        session(['payment_data' => $paymentData]);

        return view('ticket.qr', compact('order', 'paymentData'));
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
        $order = session('pending_registration');
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

        return view('ticket.checkout', compact('order', 'subtotal', 'serviceFee', 'total', 'fullName'));
    }
}