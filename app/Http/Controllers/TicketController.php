<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
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
        $admin = Auth::guard('admin')->user();
        $event = \App\Models\Event::where('name', $eventName)->firstOrFail();
        $isSupporter = ($admin->role === 'supporter');

        // 1. Determine status
        $status = $request->query('status', 'pending');
        if ($isSupporter) {
            $status = 'approved';
        }

        $query = \App\Models\Ticket::where('event', $eventName)->with(['athlete.user']);

        // 2. Apply status filter correctly
        // If 'all', don't filter by status. Otherwise, apply the specific status.
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 3. Filter by Search (You were missing the $search variable definition)
        $search = $request->query('search');
        if (!empty($search)) {
            $request->merge(['page' => 1]);
            $searchTerm = trim($search);

            $query->where(function($q) use ($searchTerm) {
                $q->where('bib_number', 'LIKE', "%{$searchTerm}%")
                ->orWhere('bib_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('id', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('athlete.user', function($userQuery) use ($searchTerm) {
                    $userQuery->where(\DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"), 'LIKE', "%{$searchTerm}%")
                            ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere(\DB::raw("CONCAT_WS(' ', first_name, last_name)"), 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // 4. Stats
        $baseQuery = \App\Models\Ticket::where('event', $eventName)->whereNotNull('bib_number');
        
        $counts = [
            'approved' => (clone $baseQuery)->where('status', 'approved')->distinct()->count('bib_number'),
        ];

        if (!$isSupporter) {
            $counts['all'] = (clone $baseQuery)->distinct()->count('bib_number');
            $counts['pending'] = (clone $baseQuery)->where('status', 'pending')->distinct()->count('bib_number');
            $counts['rejected'] = (clone $baseQuery)->where('status', 'rejected')->distinct()->count('bib_number');
        }

        $eventTickets = \App\Models\Ticket::where('event', $eventName);
        // 1. Total Approved
        $totalApproved = (clone $eventTickets)->where('status', 'approved')->count();

        // 2. Total Printed (is_printed should be 1 or true)
        $totalPrinted = (clone $eventTickets)->where('status', 'approved')->where('is_printed', 1)->count();

        // 3. To-Print (Approved but not yet printed, is_printed should be 0 or false)
        $toPrint = (clone $eventTickets)->where('status', 'approved')->where('is_printed', 0)->count();
        $totalTickets = \App\Models\Ticket::where('event', $eventName)->count();
        $eventLimit = $event->total_max_slots ?? 0;

        return view('dashboard.ticket-sales.ticket', compact(
            'customers', 'counts', 'status', 'eventName', 'event', 'totalApproved', 'totalPrinted', 'toPrint', 'totalTickets', 'eventLimit'
        ));
    }

  public function index()
{
    $admin = Auth::guard('admin')->user();
    $query = Event::query();

    /**
     * ROLE ACCESS LOGIC
     * Super Admin & Finance Admin: Can see ALL events.
     * Event Admin: Only assigned events.
     */
    if ($admin->role === 'event_admin') {
        $query->whereHas('admins', function($q) use ($admin) {
            $q->where('admin_id', $admin->id);
        });
    }

    // Fetch Events
    $events = $query->withSum(['registrations as approved_revenue' => function($q) {
                $q->where('status', 'approved');
            }], 'price')
            ->withCount(['registrations as approved_ticket_count' => function($q) {
                $q->where('status', 'approved');
            }])
            ->orderBy('date', 'desc')
            ->get();

    // Group by status (assuming is_active 1 for live, 0 for past)
    $nowEvents = $events->where('is_active', 1);
    $pastEvents = $events->where('is_active', 0);

    // Calculate Grand Total for Live Events
    $grandTotalRevenue = $nowEvents->sum('approved_revenue');

    return view('dashboard.ticket-sales.index', compact('nowEvents', 'pastEvents', 'grandTotalRevenue'));
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
        $admin = Auth::guard('admin')->user();
        $agent = Auth::guard('agent')->user();
        $eventId = $request->get('event'); 
        
        $category = $request->get('category', 'all');
        $status = $request->get('status', 'all'); 
        // Capture print_status
        $printStatus = $request->get('print_status', 'all'); 
        
        $event = \App\Models\Event::findOrFail($eventId);

        // --- Role Based Security (unchanged) ---
        $isAuthorizedAdmin = Auth::guard('admin')->check() && 
                (Auth::guard('admin')->user()->role !== 'event_admin' || $event->admins->contains(Auth::guard('admin')->id()));
        $isAuthorizedAgent = Auth::guard('agent')->check();

        if (!$isAuthorizedAdmin && !$isAuthorizedAgent) {
            abort(403, 'You do not have permission to export data for this event.');
        }

        $eventPrefix = str_replace(' ', '_', $event->name);
        // Update filename to reflect print status
        $fileName = $eventPrefix . '_' . $status . '_' . $category . '_' . $printStatus . '_' . date('d-m-Y') . '.xlsx';
        
        // Pass $printStatus as the 3rd argument
        return Excel::download(new TicketExport($category, $status, $printStatus, $eventId), $fileName);
    }

    public function initiatePayment($id)
    {
        // 1. Session Safety Check
        $order = session('pending_registration');
        $method = session('payment_method');
    
        // 1. Updated Safety Check (Check for event_id)
        if (!$order || !isset($order['athlete_id']) || !isset($order['event_id'])) {
            return redirect()->route('athlete.register')
                ->with('error', 'Session expired or registration data is missing.');
        }
        $ticketType = \App\Models\EventTicketType::with('event')->find($order['ticket_type_id']);
        $event = $ticketType->event;
        $totalEventRegistrations = \App\Models\Ticket::whereHas('ticketType', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->where('status', '!=', 'rejected')
            ->count();


        // 2. Fetch Event using ID from session
        $eventModel = \App\Models\Event::find($order['event_id']);

        if (!$eventModel) {
            return redirect()->route('public.events')
                ->with('error', 'The event could not be found.');
        }

        $eventName = $eventModel->name; // Now we have the name for the Ticket record
        $totalLimit = $eventModel->total_max_slots;

        // 3. ENFORCE LIMIT
        $soldCount = \App\Models\Ticket::where('event', $eventName)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->distinct('bib_number')
            ->count('bib_number');

        if (!is_null($totalLimit) && $soldCount >= $totalLimit) {
            return redirect()->route('public.events')
                ->with('error', "Sorry, this event has reached its maximum limit of {$totalLimit} participants.");
        }

        // 3. Duplicate Registration Check
        $exists = \App\Models\Ticket::where('athlete_id', $order['athlete_id'])
            ->where('event', $eventName)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->route('public.events')
                ->with('error', 'You are already registered for this event.');
        }

        // 4. Athlete Data & BIB Generation
        $athlete = \App\Models\Athlete::find($order['athlete_id']);
        if (!$athlete) {
            return redirect()->route('athlete.register')->with('error', 'Athlete record not found.');
        }

        $gender = $athlete->gender ?? 'male';
        $generatedBib = $this->generateBib(
            $order['event_id'],
            $order['ticket_type_id'],
            $athlete->gender
        );

        $isEarlyBird = false;
        $discountAmount = 0;

        if ($event->early_bird_limit > 0 && $totalEventRegistrations < $event->early_bird_limit) {
            $isEarlyBird = true;
            $discountAmount = $ticketType->early_bird_discount ?? 0;
        }

        $finalAmount = (int)str_replace(',', '', $order['price']) - $discountAmount;

        // 5. Create the Ticket (Database Entry)
        $ticket = \App\Models\Ticket::create([
            'athlete_id'       => $order['athlete_id'], 
            'event_id'         => $order['event_id'], 
            'ticket_type_id'   => $order['ticket_type_id'],
            'bib_name'         => $order['bib_name'],
            'bib_number'       => $generatedBib, 
            'category'         => $order['category'],
            'price'            => $finalAmount,
            'event'            => $eventName, 
            't_shirt_size'     => $order['t_shirt_size'] ?? 'M',
            'experience_level' => $order['exp_level'] ?? 'Beginner',
            'transaction_id'   => null, 
            'payment_method'   => $method,
            'status'           => 'pending', 
        ]);

        // 6. KBZPay Pre-create Preparation
        $price = (string) $ticket->price;
        $timestamp = (string) time();
        $nonce     = \Illuminate\Support\Str::random(32);
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

        // Sort and Sign for KBZ Security
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

        // 7. Request to KBZPay API
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
                    'total_amount'   => $finalAmount,
                    'trans_currency' => 'MMK'
                ]
            ]
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withBody(
                json_encode($payload, JSON_UNESCAPED_SLASHES),
                'application/json'
            )->post(env('KBZ_PAY_URL'));

            $result = $response->json();

            if (isset($result['Response']['result']) && $result['Response']['result'] === 'SUCCESS') {
                $qrString = $result['Response']['qrCode'];
                return view('payment.kbz_qr', compact('qrString', 'ticket'));
            }

            return back()->with('error', 
                'KBZ Error: ' . ($result['Response']['msg'] ?? 'Unknown error')
            );

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('KBZ connection error: ' . $e->getMessage());
            return back()->with('error', 'Connection Error: Unable to reach payment gateway.');
        }
    }

    private function generateBib($eventId, $ticketTypeId, $gender = 'male')
    {
        $ticketType = \App\Models\EventTicketType::find($ticketTypeId);

        if (!$ticketType) {
            return 'UNK000';
        }

        $basePrefix = strtoupper($ticketType->prefix); 
        $start = $ticketType->start_number ?? 1;
        
        // 1. Gender Prefix First Logic (No Hyphens)
        // Result: "M10K" or "F10K"
        $genderCode = (strtolower($gender) === 'female') ? 'F' : 'M';
        $fullPrefix = $ticketType->has_gender_bib ? $genderCode . $basePrefix : $basePrefix;

        // 2. Get existing BIBs starting with this prefix
        $usedBibs = \App\Models\Ticket::where('event_id', $eventId)
            ->where('ticket_type_id', $ticketTypeId)
            ->where('bib_number', 'LIKE', $fullPrefix . '%')
            ->where('status', '!=', 'rejected')
            ->pluck('bib_number')
            ->toArray();

        $usedNumbers = [];
        foreach ($usedBibs as $bib) {
            // Strip the prefix string (e.g., 'M10K001' becomes '001')
            $numericPart = str_replace($fullPrefix, '', $bib);
            
            if (is_numeric($numericPart)) {
                $usedNumbers[] = (int) $numericPart;
            }
        }

        // 3. Find the first available gap starting from your start_number
        $number = $start;
        while (in_array($number, $usedNumbers)) {
            $number++;
        }

        // Returns format: M10K001 or F10K001
        return $fullPrefix . str_pad($number, 3, '0', STR_PAD_LEFT);
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

    public function initiatePayment_s(Request $request, $id) 
    {
        $order = session('pending_registration');
    
        if (!$order) {
            return redirect()->route('athlete.register')->with('error', 'Session expired.');
        }

        // 1. Fetch Ticket Type, Athlete, and the associated Event
        $ticketType = \App\Models\EventTicketType::with('event')->find($order['ticket_type_id']);
        $athlete = \App\Models\Athlete::find($order['athlete_id']);

        if (!$ticketType || !$athlete || !$ticketType->event) {
            return back()->with('error', 'Invalid registration data.');
        }

        $event = $ticketType->event;

        // 2. Determine the Base Price based on nationality
        $basePrice = (strtolower($athlete->nationality) === 'myanmar') 
            ? $ticketType->national_price 
            : $ticketType->foreign_price;

        // 3. GLOBAL LOGIC: Count all registrations for the WHOLE EVENT
        $totalEventRegistrations = \App\Models\Ticket::whereHas('ticketType', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->where('status', '!=', 'rejected')
            ->count();

        // 4. Calculate Final Amount using Event Limit + Ticket Type Discount
        $isEarlyBird = false;
        $discountAmount = 0;

        if ($event->early_bird_limit > 0 && $totalEventRegistrations < $event->early_bird_limit) {
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

        $athlete = \App\Models\Athlete::find($order['athlete_id']);
        $ticketType = \App\Models\EventTicketType::with('event')->find($order['ticket_type_id']);

        if (!$athlete || !$ticketType || !$ticketType->event) {
            return redirect()->route('athlete.register')->with('error', 'Invalid registration data.');
        }

        $event = $ticketType->event;

        // 3. SECURE NATIONALITY CHECK
        $isNational = (trim(strtolower($athlete->nationality)) === 'myanmar');

        // 4. Set Base Price
        $basePrice = $isNational ? (float)$ticketType->national_price : (float)$ticketType->foreign_price;

        // 5. GLOBAL EARLY BIRD CALCULATION
        $globalSoldCount = \App\Models\Ticket::whereHas('ticketType', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->where('status', '!=', 'rejected') 
            ->count();

        $discountAmount = 0;
        $isEarlyBirdActive = false;

        if ($event->early_bird_limit > 0 && $globalSoldCount < $event->early_bird_limit) {
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

    // 1. Update Ticket specific info
    $ticket->update([
        'bib_name'     => $request->bib_name,
        't_shirt_size' => $request->t_shirt_size,
    ]);

    // 2. Format NRC / ID Number
    $idNumber = '';
    if ($request->has(['nrc_state', 'nrc_district', 'nrc_type', 'nrc_number'])) {
        $idNumber = "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_type}){$request->nrc_number}";
    } else {
        $idNumber = $request->id_number;
    }

    // 3. Update Athlete info (including ITRA)
    $ticket->athlete->update([
        'id_number'    => $idNumber,
        // Using boolean() handles 'on'/1 as true and missing as false
        'has_itra'     => $request->boolean('has_itra'), 
        'itra_details' => $request->itra_details,
    ]);

    return redirect()->back()->with('success', 'Information Updated Successfully');
}

public function showPaymentMethod()
{
    // Make sure the user has a selected ticket in the session/database first
    if (!session()->has('pending_registration')) {
        return redirect()->route('checkout.review')->with('error', 'Please select a ticket first.');
    }
    return view('ticket.payment_method'); // Ensure this view exists
}

public function selectPaymentMethod(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:kbz,mmqr',
    ]);

    // 1. Save payment method to session
    session(['payment_method' => $request->payment_method]);

    // 2. We need the runner_id for your initiatePayment_s route
    $runnerId = Auth::user()->runner_id;

    // 3. Redirect to the initiation route as requested
    return redirect()->route('initiatePayment', ['id' => Auth::user()->runner_id]);
}

public function markPrinted($id) {
    $user = auth('admin')->user();

    if (!$user || !in_array($user->role, ['admin', 'printer', 'supporter'])) {
        return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
    }

    try {
        $ticket = \App\Models\Ticket::findOrFail($id);

        if ($ticket->is_printed) {
            return response()->json(['success' => false, 'message' => 'Already printed!'], 403);
        }
        
        // Update status AND set the current time
        $ticket->update([
            'is_printed' => true,
            'printed_at' => now() // Captures current date and time
        ]);
        
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        \Log::error("Print Error: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error saving print status'], 500);
    }
}

public function reprint(Request $request, $id) 
{
    $admin = auth('admin')->user();

    if (!\Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
        return response()->json(['success' => false, 'message' => 'Incorrect password'], 403);
    }
    
    if (!in_array($admin->role, ['super_admin', 'supporter'])) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    // Update the print time for the reprint as well
    $ticket = \App\Models\Ticket::findOrFail($id);
    $ticket->update(['printed_at' => now()]);

    return response()->json(['success' => true]);
}
}