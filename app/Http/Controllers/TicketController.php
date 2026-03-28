<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Laranex\LaravelMyanmarPayments\LaravelMyanmarPayments;
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    public function showTicket(Request $request)
    {
        // 1. Get the event name from the URL (?event=Cherry+Trail+Run+2026)
        $eventName = $request->query('event');

        // 2. Security Check: Only logged-in users
        if (auth()->check()) {
            
            // 3. Check if they have an active ticket for THIS specific event
            $hasActiveTicket = auth()->user()->tickets()
                ->where('event', $eventName)
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->exists();

            // 4. If they have one, kick them out!
            if ($hasActiveTicket) {
                return redirect()->route('public.events') // Change to your events list route name
                    ->with('error', "You already have a registration (Pending/Accepted) for $eventName.");
            }
        }

        // 5. If they are clear, show the registration form
        return view('ticket.ticket', compact('eventName'));
    }

    public function dashboard(Request $request)
    {
        $search = $request->query('search');
        // Default to 'pending' if no tab is selected
        $status = $request->query('status', 'pending'); 

        $query = \App\Models\Ticket::with(['athlete.user'])
            ->where('status', $status) // Filter by tab status in DB
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('bib_number', 'LIKE', "%{$search}%")
                ->orWhere('bib_name', 'LIKE', "%{$search}%")
                ->orWhereHas('athlete.user', function($userQuery) use ($search) {
                    $userQuery->where('first_name', 'LIKE', "%{$search}%")
                                ->orWhere('last_name', 'LIKE', "%{$search}%");
                });
            });
        }

        $customers = $query->paginate(10)->withQueryString();
        
        // Get counts for the tab badges
        $counts = [
            'pending'  => \App\Models\Ticket::where('status', 'pending')->count(),
            'approved' => \App\Models\Ticket::where('status', 'approved')->count(),
            'rejected' => \App\Models\Ticket::where('status', 'rejected')->count(),
        ];

        return view('dashboard.ticket-sales.ticket', compact('customers', 'counts'));
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

    public function downloadPNG($id) 
{
    // 1. Get Ticket Data
    $data = $this->getTicketData($id);
    $ticket = $data['ticket']; 

    // 2. Fetch Athlete and User
    $athlete = \App\Models\Athlete::find($ticket->athlete_id);
    $user = $athlete ? \App\Models\User::where('runner_id', $athlete->runner_id)->first() : null;

    if (!$athlete || !$user) {
        return "ERROR: Personal information (Athlete or User) not found.";
    }

    // 3. Map variables
    $id_doc      = $athlete->id_number ?? 'N/A';
    $fullName    = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");
    $bibName     = $ticket->bib_name ?? 'N/A';
    $bibNumber   = $ticket->bib_number ?? '0000';
    $category    = $ticket->category ?? 'N/A';
    $nationality = $athlete->nationality ?? 'N/A';
    $dob         = $athlete->dob ?? 'N/A';
    $gender      = $athlete->gender ?? 'N/A';
    $division    = $athlete->state ?? 'N/A';
    $email       = $user->email ?? 'N/A';
    $viber       = $athlete->viber ?? 'N/A';
    $phone       = $user->phone ?? 'N/A';
    $contact     = $athlete->contact ?? 'N/A';
    $tSize       = $ticket->t_shirt_size ?? 'N/A';
    $blood       = $athlete->blood_type ?? 'N/A';
    $exp         = $ticket->experience_level ?? 'N/A';
    $medical     = $athlete->medical_details ?? 'None';
    $itra        = $athlete->itra_details ?? 'None';

    $qrContent = "ID: $id_doc\nName: $fullName\nBIB Name: $bibName\nBIB: $bibNumber\nCategory: $category\nNationality: $nationality\nDOB: $dob\nGender: $gender\nDivision: $division\nEmail: $email\nViber: $viber\nPhone: $phone\nContact: $contact\nSize: $tSize\nBlood: $blood\nExp: $exp\nMedical: $medical\nITRA: $itra";

    // 4. Paths
    if (str_contains($category, '36')) {
        $templatePath = public_path('images/ticket2_1.jpg'); 
    } elseif (str_contains($category, '16')) {
        $templatePath = public_path('images/ticket2.jpg'); 
    } else {
        $templatePath = public_path('images/ticket.jpg'); 
    }
    
    $fontPath = public_path('assets/fonts/arial.ttf');
    $logoPath = public_path('images/myan_logo.jpg');

    if (!file_exists($logoPath)) {
        return "Error: Image not found at " . $logoPath;
    } // Your Logo Path

    if (!\Illuminate\Support\Facades\File::exists($templatePath)) return "ERROR: Template image not found.";

    $image = @\imagecreatefromjpeg($templatePath);
    if (!$image) return "ERROR: GD Library not enabled.";

    $white = \imagecolorallocate($image, 255, 255, 255);
    
    if (\Illuminate\Support\Facades\File::exists($fontPath)) {
        // --- DRAW TEXT ON TICKET ---
        \imagettftext($image, 20, 0, 980, 45, $white, $fontPath, str_pad($ticket->id, 5, '0', STR_PAD_LEFT));
        \imagettftext($image, 22, 0, 980, 90, $white, $fontPath, strtoupper($bibName));
        \imagettftext($image, 22, 0, 1020, 155, $white, $fontPath, $bibNumber);

        // --- ADD QR CODE WITH LOGO ---
        $qrSize = 150;
        // Tip: Use ecc=H (High error correction) so the QR stays scannable even with a logo in the middle
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$qrSize}x{$qrSize}&ecc=H&data=" . urlencode($qrContent);
        
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
        $qrCodeRaw = @file_get_contents($qrUrl, false, $ctx);
        
        if ($qrCodeRaw) {
            $qrCodeImage = \imagecreatefromstring($qrCodeRaw);
            
            if ($qrCodeImage && \Illuminate\Support\Facades\File::exists($logoPath)) {
                $logo = @\imagecreatefromjpeg($logoPath);
                if ($logo) {
                    $qrWidth = \imagesx($qrCodeImage);
                    $qrHeight = \imagesy($qrCodeImage);
                    $logoWidth = \imagesx($logo);
                    $logoHeight = \imagesy($logo);

                    // Scale logo to be ~22% of the QR code width
                    $logoTargetWidth = $qrWidth * 0.22;
                    $logoTargetHeight = $logoHeight * ($logoTargetWidth / $logoWidth);

                    // Find Center
                    $dstX = ($qrWidth - $logoTargetWidth) / 2;
                    $dstY = ($qrHeight - $logoTargetHeight) / 2;

                    // OPTIONAL: Draw a small white background behind the logo for better scanning
                    \imagefilledrectangle($qrCodeImage, $dstX - 2, $dstY - 2, $dstX + $logoTargetWidth + 2, $dstY + $logoTargetHeight + 2, $white);

                    // Merge Logo
                    \imagecopyresampled($qrCodeImage, $logo, $dstX, $dstY, 0, 0, $logoTargetWidth, $logoTargetHeight, $logoWidth, $logoHeight);
                    
                    \imagedestroy($logo);
                }
            }

            if ($qrCodeImage) {
                \imagecopy($image, $qrCodeImage, 950, 200, 0, 0, $qrSize, $qrSize);
                \imagedestroy($qrCodeImage);
            }
        }
    }

    return response()->streamDownload(function () use ($image) {
        \imagepng($image);
        \imagedestroy($image);
    }, "MyanRun_{$bibNumber}.png", [
        'Content-Type' => 'image/png',
    ]);
}
    public function previewPDF($id) 
    {
        $data = $this->getTicketData($id); // Now it has the logo!
        $pdf = Pdf::loadView('pdf', $data);

        return $pdf->stream('ticket-' . $id . '.pdf');
    }

    public function exportExcel(Request $request) 
    {
        $category = $request->get('category', 'all');
        // Get the status from the URL (e.g., ?status=approved), default to 'all'
        $status = $request->get('status', 'all'); 
        
        $fileName = 'Tickets_' . $status . '_' . $category . '_' . date('d-m-Y') . '.xlsx';
        
        // Pass both category AND status to the Export class
        return Excel::download(new TicketExport($category, $status), $fileName);
    }

    public function initiatePayment($id)
    {
        $order = session('pending_registration');
        if (!$order) {
            return redirect()->route('athlete.register')
                ->with('error', 'Session expired.');
        }

        $athlete = \App\Models\Athlete::find($order['athlete_id']);
        $gender = $athlete ? $athlete->gender : 'male';
        $generatedBib = $this->generateBib($gender, $order['category']);
    
        $ticket = Ticket::create([
            'athlete_id'       => $order['athlete_id'], 
            'bib_name'         => $order['bib_name'],
            'bib_number'       => $generatedBib, 
            'category'         => $order['category'] ?? $request->category,
            'price'            => (int)str_replace(',', '', $order['price']),
            'event'            => $order['event'], 
            't_shirt_size'     => $order['t_shirt_size'] ?? 'M',
            'experience_level' => $order['exp_level'] ?? 'Beginner', // Added this as it's in your DB
            'transaction_id'   => null, // The image name goes here!
            'status'           => 'pending', 
        ]);

        // ✅ Clean price
        $price = (string) $ticket->price;
        $timestamp = (string) time();
        $nonce     = Str::random(32);
        $orderId   = $ticket->id . '_' . $timestamp;

        // ✅ STEP 1: Build SIGNATURE DATA (VERY IMPORTANT)
        $signParams = [
            'appid'          => env('KBZ_PAY_APP_ID'),
            'merch_code'     => env('KBZ_PAY_MERCHANT_CODE'),
            'merch_order_id' => $orderId,
            'method'         => 'kbz.payment.precreate',
            'nonce_str'      => $nonce,
            'notify_url'     => env('KBZ_PAY_NOTIFY_URL'),
            'timestamp'      => $timestamp,
            'total_amount'   => $price,
            'trade_type'     => 'PAY_BY_QRCODE',
            'trans_currency' => 'MMK',
            'version'        => '1.0',
        ];

        // ✅ STEP 2: SORT
        ksort($signParams);

        // ✅ STEP 3: BUILD STRING A
        $stringA = '';
        foreach ($signParams as $key => $value) {
            if ($value !== "" && $value !== null) {
                $stringA .= $key . "=" . $value . "&";
            }
        }
        $stringA = rtrim($stringA, "&");

        // ✅ STEP 4: SIGN
        $stringToSign = $stringA . "&key=" . env('KBZ_PAY_APP_KEY');
        $sign = strtoupper(hash('sha256', $stringToSign));

        // 🔍 DEBUG (remove later)
        Log::info('KBZ STRING TO SIGN: ' . $stringToSign);

        // ✅ STEP 5: FINAL PAYLOAD
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
                    'merch_order_id' => $orderId,
                    'merch_code'     => env('KBZ_PAY_MERCHANT_CODE'),
                    'appid'          => env('KBZ_PAY_APP_ID'),
                    'trade_type'     => 'PAY_BY_QRCODE',
                    'total_amount'   => $price,
                    'trans_currency' => 'MMK'
                ]
            ]
        ];

        try {
            // ✅ STEP 6: SEND REQUEST (JSON BODY — VERY IMPORTANT)
            $response = Http::withBody(
                json_encode($payload, JSON_UNESCAPED_SLASHES),
                'application/json'
            )->post(env('KBZ_PAY_URL'));

            $result = $response->json();

            Log::info('KBZ RESPONSE:', $result);

            // ✅ SUCCESS
            if (isset($result['Response']['result']) 
                && $result['Response']['result'] === 'SUCCESS') {

                $qrString = $result['Response']['qrCode'];

                return view('payment.kbz_qr', compact('qrString', 'ticket'));
            }

            // ❌ FAIL
            return back()->with('error', 
                'KBZ Error: ' . ($result['Response']['msg'] ?? 'Unknown error')
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Connection Error: ' . $e->getMessage());
        }
    }

    private function generateBib($gender, $category)
    {
        $prefix = (strtolower($gender) === 'female') ? 'F' : 'M';
        
        preg_match('/\d+/', $category, $matches);
        $distance = $matches[0] ?? '00';
        $searchPattern = $prefix . $distance;

        // Get ALL used bib numbers (including rejected) for this category
        $usedBibs = Ticket::where('bib_number', 'LIKE', $searchPattern . '%')
            ->pluck('bib_number')
            ->toArray();

        // Extract numbers only (last 4 digits)
        $usedNumbers = [];
        foreach ($usedBibs as $bib) {
            $usedNumbers[] = (int) substr($bib, -4);
        }

        // Start from 11
        $number = 11;

        // Find first missing number
        while (in_array($number, $usedNumbers)) {
            $number++;
        }

        return $searchPattern . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
public function getNewBib(Request $request)
{
    $gender = $request->query('gender');
    $category = $request->query('category');

    // Use the private function we built earlier
    $newBib = $this->generateBib($gender, $category);

    return response()->json(['bib_number' => $newBib]);
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
            return response()->json([
                'Response' => ['return_code' => 'FAIL', 'return_msg' => 'No Request Data']
            ]);
        }

        Log::info('KBZ Callback received:', $payload);

        // ✅ Check trade_status instead of result
        if (($payload['trade_status'] ?? null) === 'PAY_SUCCESS') {
            $fullOrderId = $payload['merch_order_id'];
            $parts = explode('_', $fullOrderId);
            $realTicketId = $parts[0]; 

            $ticket = Ticket::find($realTicketId); 

            if ($ticket && $ticket->status !== 'approved') {
                $ticket->update([
                    'status' => 'approved',
                    'transaction_id' => $payload['mm_order_id'] ?? null 
                ]);
                Log::info("Ticket #{$realTicketId} confirmed via KBZ Callback.");
            }
        }

        return response()->json([
            'Response' => ['return_code' => 'SUCCESS', 'return_msg' => 'OK']
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
            'paid' => $ticket->status === 'approved',
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
        $serviceFee = 0.00; 
        $total = $subtotal + $serviceFee;
        $fullName = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");

        return view('ticket.checkout', compact('order', 'subtotal', 'serviceFee', 'total', 'fullName'));
    }

    public function updateId(Request $request)
    {
        $ticket = Ticket::with('athlete')->find($request->id);
        
        if (!$ticket || !$ticket->athlete) {
            return redirect()->back()->with('error', 'Athlete not found');
        }

        $idNumber = '';

        // Check if it's an NRC (has state, district, type, number) or a Passport
        if ($request->has(['nrc_state', 'nrc_district', 'nrc_type', 'nrc_number'])) {
            $idNumber = "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_type}){$request->nrc_number}";
        } else {
            // Fallback for Passport/Other
            $idNumber = $request->id_number;
        }

        $ticket->athlete->update([
            'id_number' => $idNumber
        ]);

        return redirect()->back()->with('success', 'ID Updated Successfully');
    }
}