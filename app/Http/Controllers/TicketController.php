<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
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
        $eventName = $request->query('event');

        if (!$eventName) {
            return redirect()->route('public.events')
                ->with('error', 'Event not found.');
        }

        $event = \App\Models\Event::where('name', $eventName)->first();

        if (!$event) {
            dd("Event not found", $eventName);
        }

        // --- Calculate Availability from Database ---
        $totalLimit = $event->total_max_slots; 

        // Fix 1: Use distinct bib_number for counting sold slots
        $soldTickets = Ticket::where('event', $eventName)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->distinct('bib_number')
            ->count('bib_number');

        $remaining = is_null($totalLimit) 
            ? null 
            : max(0, $totalLimit - $soldTickets);
        // ------------------------------

        if (auth()->check()) {

            $hasActiveTicket = auth()->user()->tickets()
                ->where('event_id', $event->id)
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->exists();

            if ($hasActiveTicket) {
                return redirect()->route('public.events')
                    ->with('error', "You already have a registration for {$event->name}.");
            }
        }

        return view('ticket.ticket', compact('event', 'remaining'));
    }

    public function dashboard($eventName, Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'pending'); 

        $query = \App\Models\Ticket::where('event', $eventName)->with(['athlete.user']);

        // 1. Filter by status
        $query->where('status', $status);

        // 2. Filter by Search
        if (!empty($search)) {
            $request->merge(['page' => 1]);
            $searchTerm = trim($search);

            $query->where(function($q) use ($searchTerm) {
                $q->where('bib_number', 'LIKE', "%{$searchTerm}%")
                ->orWhere('bib_name', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('athlete.user', function($userQuery) use ($searchTerm) {
                    $userQuery->where(\DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"), 'LIKE', "%{$searchTerm}%")
                                ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                                ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                ->orWhere(\DB::raw("CONCAT_WS(' ', first_name, last_name)"), 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // 3. Final Execution
        $customers = $query->orderBy('created_at', 'desc')
                           ->paginate(10) 
                           ->withQueryString();

        // Fetch event to get max slots
        $event = \App\Models\Event::where('name', $eventName)->first();

        // Define the variable so compact() can find it
        $eventLimit = $event?->total_max_slots;

        // ✅ Updated to count UNIQUE bib_numbers
        $counts = [
            'all' => \App\Models\Ticket::where('event', $eventName)
                ->whereNotNull('bib_number')
                ->distinct()
                ->count('bib_number'),

            'pending' => \App\Models\Ticket::where('event', $eventName)
                ->where('status', 'pending')
                ->whereNotNull('bib_number')
                ->distinct()
                ->count('bib_number'),

            'approved' => \App\Models\Ticket::where('event', $eventName)
                ->where('status', 'approved')
                ->whereNotNull('bib_number')
                ->distinct()
                ->count('bib_number'),

            'rejected' => \App\Models\Ticket::where('event', $eventName)
                ->where('status', 'rejected')
                ->whereNotNull('bib_number')
                ->distinct()
                ->count('bib_number'),

            'max_slots' => $eventLimit,
        ];

        // Now 'max_slots' is a defined variable and won't throw an ErrorException
        return view('dashboard.ticket-sales.ticket', compact(
            'customers',
            'counts',
            'status',
            'eventName',
            'eventLimit' // ✅ changed
        ));
    }

    public function index()
    {
        // Fetch events grouped by their 'status' (1 for live, 0 for past)
        $events = Event::orderBy('date', 'desc')->get()->groupBy('is_active');

        // Extract them into separate variables so the Blade @forelse works correctly
        $nowEvents = $events->get(1, collect());  // Status 1 = Live
        $pastEvents = $events->get(0, collect()); // Status 0 = Past

        return view('dashboard.ticket-sales.index', compact('nowEvents', 'pastEvents'));
    }

    public function approve($id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);

        $event = Event::where('name', $ticket->event)->first();
        $totalLimit = $event?->total_max_slots;

        // Fix 2: Use distinct bib_number for counting approved slots
        $approvedCount = Ticket::where('event', $ticket->event)
            ->where('status', 'approved')
            ->distinct('bib_number')
            ->count('bib_number');

        if (!is_null($totalLimit) && $approvedCount >= $totalLimit) {
            return back()->with('error', 'Event is already full.');
        }

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

    // 2. Fetch Related Models
    $athlete = \App\Models\Athlete::find($ticket->athlete_id);
    $user = $athlete ? \App\Models\User::where('runner_id', $athlete->runner_id)->first() : null;
    $ticketType = \App\Models\EventTicketType::find($ticket->ticket_type_id);

    if (!$athlete || !$user || !$ticketType) {
        return "ERROR: Required information not found.";
    }

    // 3. Map variables
    $id_doc      = $athlete->id_number ?? 'N/A';
    $fullName    = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");
    $bibName     = $ticket->bib_name ?? 'N/A';
    $bibNumber   = $ticket->bib_number ?? '0000';
    $category    = $ticketType->name ?? 'N/A';
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

    // 4. PATH LOGIC (Fixed for Windows and public/storage)
    $dbPath = ltrim($ticketType->ticket_png, '/'); 
    
    // If the path in DB already includes 'storage/', we don't want to double it
    $relativePath = str_starts_with($dbPath, 'storage') ? $dbPath : 'storage/' . $dbPath;
    
    // Normalize slashes for Windows
    $templatePath = public_path(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath));

    $fontPath = public_path('assets/fonts/arial.ttf');
    $logoPath = public_path('images/myan_logo.jpg');

    if (!\Illuminate\Support\Facades\File::exists($templatePath)) {
        return "ERROR: Template not found at " . $templatePath;
    }

    // 5. Create Image Resource (Check Extension)
    $extension = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
    if ($extension === 'png') {
        $image = @\imagecreatefrompng($templatePath);
        \imagealphablending($image, true);
        \imagesavealpha($image, true);
    } else {
        $image = @\imagecreatefromjpeg($templatePath);
    }

    if (!$image) return "ERROR: Failed to process image. Check GD library.";

    $white = \imagecolorallocate($image, 255, 255, 255);
    
    // 6. Drawing & QR Code
    if (\Illuminate\Support\Facades\File::exists($fontPath)) {
        \imagettftext($image, 20, 0, 980, 45, $white, $fontPath, str_pad($ticket->id, 5, '0', STR_PAD_LEFT));
        \imagettftext($image, 22, 0, 980, 90, $white, $fontPath, strtoupper($bibName));
        \imagettftext($image, 22, 0, 1020, 155, $white, $fontPath, $bibNumber);

        $qrSize = 150;
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$qrSize}x{$qrSize}&ecc=H&data=" . urlencode($qrContent);
        
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
        $qrCodeRaw = @file_get_contents($qrUrl, false, $ctx);
        
        if ($qrCodeRaw) {
            $qrCodeImage = \imagecreatefromstring($qrCodeRaw);
            if ($qrCodeImage) {
                if (\Illuminate\Support\Facades\File::exists($logoPath)) {
                    $logo = @\imagecreatefromjpeg($logoPath);
                    if ($logo) {
                        $qrWidth = \imagesx($qrCodeImage); $qrHeight = \imagesy($qrCodeImage);
                        $logoWidth = \imagesx($logo); $logoHeight = \imagesy($logo);
                        $logoTargetWidth = $qrWidth * 0.22;
                        $logoTargetHeight = $logoHeight * ($logoTargetWidth / $logoWidth);
                        $dstX = ($qrWidth - $logoTargetWidth) / 2;
                        $dstY = ($qrHeight - $logoTargetHeight) / 2;
                        \imagefilledrectangle($qrCodeImage, $dstX - 2, $dstY - 2, $dstX + $logoTargetWidth + 2, $dstY + $logoTargetHeight + 2, $white);
                        \imagecopyresampled($qrCodeImage, $logo, $dstX, $dstY, 0, 0, $logoTargetWidth, $logoTargetHeight, $logoWidth, $logoHeight);
                        \imagedestroy($logo);
                    }
                }
                \imagecopy($image, $qrCodeImage, 950, 200, 0, 0, $qrSize, $qrSize);
                \imagedestroy($qrCodeImage);
            }
        }
    }

    return response()->streamDownload(function () use ($image) {
        \imagepng($image);
        \imagedestroy($image);
    }, "MyanRun_{$bibNumber}.png", ['Content-Type' => 'image/png']);
}

    public function previewPDF($id) 
    {
        $data = $this->getTicketData($id);
        $pdf = Pdf::loadView('pdf', $data);

        return $pdf->stream('ticket-' . $id . '.pdf');
    }

    public function exportExcel(Request $request) 
    {
        $category = $request->get('category', 'all');
        $status = $request->get('status', 'all'); 
        
        $fileName = 'Tickets_' . $status . '_' . $category . '_' . date('d-m-Y') . '.xlsx';
        
        return Excel::download(new TicketExport($category, $status), $fileName);
    }

    public function initiatePayment($id)
    {
        $order = session('pending_registration');
        if (!$order) {
            return redirect()->route('athlete.register')
                ->with('error', 'Session expired.');
        }

        // --- ENFORCE LIMIT FROM DATABASE ---
        $eventModel = Event::where('name', $order['event'])->first();
        $totalLimit = $eventModel?->total_max_slots;

        // Fix 3: Use distinct bib_number for counting sold slots during payment initiation
        $soldCount = Ticket::where('event', $order['event'])
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->distinct('bib_number')
            ->count('bib_number');

        if (!is_null($totalLimit) && $soldCount >= $totalLimit) {
            return redirect()->route('public.events')->with('error', "Sorry, this event has reached its maximum limit of {$totalLimit} participants.");
        }
        // -------------------------

        $exists = Ticket::where('athlete_id', $order['athlete_id'])
            ->where('event', $order['event'])
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->route('public.events')
                ->with('error', 'You already registered for this event.');
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
            'experience_level' => $order['exp_level'] ?? 'Beginner',
            'transaction_id'   => null, 
            'status'           => 'pending', 
        ]);

        $price = (string) $ticket->price;
        $timestamp = (string) time();
        $nonce     = Str::random(32);
        $orderId   = $ticket->id . '_' . $timestamp;

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

        ksort($signParams);

        $stringA = '';
        foreach ($signParams as $key => $value) {
            if ($value !== "" && $value !== null) {
                $stringA .= $key . "=" . $value . "&";
            }
        }
        $stringA = rtrim($stringA, "&");

        $stringToSign = $stringA . "&key=" . env('KBZ_PAY_APP_KEY');
        $sign = strtoupper(hash('sha256', $stringToSign));

        Log::info('KBZ STRING TO SIGN: ' . $stringToSign);

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
            $response = Http::withBody(
                json_encode($payload, JSON_UNESCAPED_SLASHES),
                'application/json'
            )->post(env('KBZ_PAY_URL'));

            $result = $response->json();

            Log::info('KBZ RESPONSE:', $result);

            if (isset($result['Response']['result']) 
                && $result['Response']['result'] === 'SUCCESS') {

                $qrString = $result['Response']['qrCode'];

                return view('payment.kbz_qr', compact('qrString', 'ticket'));
            }

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

        $usedBibs = Ticket::where('bib_number', 'LIKE', $searchPattern . '%')
            ->pluck('bib_number')
            ->toArray();

        $usedNumbers = [];
        foreach ($usedBibs as $bib) {
            $usedNumbers[] = (int) substr($bib, -4);
        }

        $number = 11;

        while (in_array($number, $usedNumbers)) {
            $number++;
        }

        return $searchPattern . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getNewBib(Request $request)
    {
        $gender = $request->query('gender');
        $category = $request->query('category');

        $newBib = $this->generateBib($gender, $category);

        return response()->json(['bib_number' => $newBib]);
    }

    private function generateKbzSignature($params) {
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

        ksort($all);

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

        // 1. Fetch Ticket Type and Athlete (to check nationality)
        $ticketType = \App\Models\EventTicketType::find($order['ticket_type_id']);
        $athlete = \App\Models\Athlete::find($order['athlete_id']);

        if (!$ticketType || !$athlete) {
            return back()->with('error', 'Invalid registration data.');
        }

        // 2. Determine the Base Price based on nationality
        $basePrice = (strtolower($athlete->nationality) === 'myanmar') 
            ? $ticketType->national_price 
            : $ticketType->foreign_price;

        // 3. Count current registrations to check Early Bird limit
        $currentRegistrationsCount = \App\Models\Ticket::where('ticket_type_id', $order['ticket_type_id'])
            ->where('status', '!=', 'rejected')
            ->count();

        // 4. Calculate Final Amount
        $isEarlyBird = false;
        $discountAmount = 0;

        if ($ticketType->early_bird_limit > 0 && $currentRegistrationsCount < $ticketType->early_bird_limit) {
            $isEarlyBird = true;
            $discountAmount = $ticketType->early_bird_discount ?? 0;
        }

        $finalAmount = $basePrice - $discountAmount;

        // 5. Prepare Payment Data
        $paymentData = [
            'amount'          => $finalAmount,
            'original_amount' => $basePrice,
            'discount'        => $discountAmount,
            'is_early_bird'   => $isEarlyBird,
            'transaction_id'  => 'MR-' . strtoupper(uniqid()),
            'account_name'    => 'Runderful Myanmar Co., Ltd',
            'kbz_pay_qr'      => asset('images/payments/kbzpay-qr.png'),
        ];

        session(['payment_data' => $paymentData]);

        return view('ticket.qr', compact('order', 'paymentData'));
    }

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
    
    // 1. Safety Check
    if (!$order || !isset($order['athlete_id'])) {
        return redirect()->route('athlete.register')->with('error', 'Session expired.');
    }

    // 2. Fetch the Athlete and Ticket Type
    $athlete = \App\Models\Athlete::find($order['athlete_id']);
    $ticketType = \App\Models\EventTicketType::find($order['ticket_type_id']);

    if (!$athlete || !$ticketType) {
        return redirect()->route('athlete.register')->with('error', 'Invalid registration data.');
    }

    // 3. SECURE NATIONALITY CHECK
    // We check the 'nationality' column in the athletes table
    $isNational = (trim(strtolower($athlete->nationality)) === 'myanmar');

    // 4. Set Base Price
    $basePrice = $isNational ? (float)$ticketType->national_price : (float)$ticketType->foreign_price;

    // 5. Early Bird Calculation (Include 'pending' to be accurate)
    $soldCount = \App\Models\Ticket::where('ticket_type_id', $ticketType->id)
        ->where('status', '!=', 'rejected') 
        ->count();

    $discountAmount = 0;
    $isEarlyBirdActive = false;

    if ($ticketType->early_bird_limit > 0 && $soldCount < $ticketType->early_bird_limit) {
        $discountAmount = (float)$ticketType->early_bird_discount;
        $isEarlyBirdActive = true;
    }

    // 6. Final Totals
    $subtotal = $basePrice;
    $serviceFee = 0.00; 
    $total = ($subtotal - $discountAmount) + $serviceFee;
    
    $fullName = trim("{$athlete->first_name} {$athlete->last_name}");

    return view('ticket.checkout', compact(
        'order', 
        'subtotal', 
        'discountAmount', 
        'isEarlyBirdActive', 
        'serviceFee', 
        'total', 
        'fullName'
    ));
}

    public function updateId(Request $request)
    {
        $ticket = Ticket::with('athlete')->find($request->id);
        
        if (!$ticket || !$ticket->athlete) {
            return redirect()->back()->with('error', 'Athlete not found');
        }

        $ticket->update([
            'bib_name'     => $request->bib_name,
            't_shirt_size' => $request->t_shirt_size,
        ]);

        $idNumber = '';

        if ($request->has(['nrc_state', 'nrc_district', 'nrc_type', 'nrc_number'])) {
            $idNumber = "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_type}){$request->nrc_number}";
        } else {
            $idNumber = $request->id_number;
        }

        $ticket->athlete->update([
            'id_number' => $idNumber
        ]);

        return redirect()->back()->with('success', 'Information Updated Successfully');
    }
}