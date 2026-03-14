<?php

namespace App\Http\Controllers;

use App\Models\Dinner;
use App\Models\DinnerTicket;
use App\Models\DinnerRegister;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DinnerController extends Controller
{
    /**
     * PUBLIC: Display list of active dinners
     */
    public function index()
    {
        // Retrieve active dinners and sum up confirmed/pending public tickets
        $dinners = Dinner::where('is_active', 1)
            ->withSum([
                'tickets as public_seats_count' => function ($query) {
                    $query->whereIn('status', ['confirmed', 'pending'])
                        ->whereNull('sponsor_id');
                }
            ], 'quantity')
            ->orderBy('date', 'asc')
            ->get();

        return view('dinner.index', compact('dinners'));
    }

    /**
     * ADMIN: Main Dashboard for Dinner Ticket Management
     * This is the method that was missing!
     */
    public function dinnerTicketsIndex()
{
    $dinners = Dinner::withSum([
            'tickets as public_seats_count' => function ($query) {
                $query->whereIn('status', ['confirmed', 'pending'])
                      ->whereNull('sponsor_id');
            }
        ], 'quantity')
        ->withSum([
            'tickets as sponsor_seats_count' => function ($query) {
                $query->whereIn('status', ['confirmed', 'pending'])
                      ->whereNotNull('sponsor_id');
            }
        ], 'quantity')
        ->withSum(['tickets as total_balance' => function ($query) {
            $query->where('status', 'confirmed');
        }], 'price')
        ->withCount([
            'tickets as pending_count' => function ($query) {
                $query->where('status', 'pending');
            }
        ])
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('is_active'); 

    return view('dashboard.dinner.index_tickets', compact('dinners'));
}

    public function selectTickets($id)
    {
        // 1. Fetch the dinner
        $dinner = Dinner::findOrFail($id);

        // 2. Calculate how many public seats are already taken
        // We sum 'quantity' where sponsor_id is null AND status is not 'rejected'
        $bookedSeats = \App\Models\DinnerTicket::where('dinner_id', $id)
            ->whereNull('sponsor_id')
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('quantity');

        // 3. Determine remaining seats
        $remainingSeats = max(0, $dinner->public_capacity - $bookedSeats);

        return view('dinner.ticket', compact('dinner', 'remainingSeats'));
    }

    public function registerPage(Request $request)
    {
        $dinner = Dinner::findOrFail($request->dinner_id);

        // Calculate total seats taken by public (non-sponsor) users
        $totalSeatsTaken = DinnerTicket::where('dinner_id', $dinner->id)
                            ->whereIn('status', ['confirmed', 'pending'])
                            ->whereNull('sponsor_id') // Exclude sponsors
                            ->sum('quantity'); // Sum the quantity column

        if ($dinner->capacity > 0 && $totalSeatsTaken >= $dinner->capacity) {
            return redirect()->route('dinner.index')->with('error', 'Sorry, public seating is fully booked!');
        }

        return view('dinner.register', [
            'dinner'         => $dinner,
            'selected_type'  => $request->selected_type,
            'selected_price' => $request->selected_price,
            'quantity'       => $request->quantity ?? 1
        ]);
    }

    public function checkoutPage(Request $request)
    {
        $dinner = Dinner::findOrFail($request->dinner_id);
        return view('dinner.checkout', compact('dinner'));
    }
    
    public function process(Request $request)
    {
        $data = [
            'dinner_id'    => $request->dinner_id,
            'type'         => $request->type,
            'price'        => $request->total_price,
            'quantity'     => $request->quantity,
            'first_name'   => $request->first_name,
            'middle_name'  => $request->middle_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'viber'        => $request->viber,
            'applied_code' => $request->applied_code,
        ];

        return redirect()->route('dinner.confirmation', array_merge(['id' => 'new'], $data));
    }

    public function storeRegistration(Request $request) 
    {
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
        ]);

        $alreadyConfirmed = DinnerRegister::where('email', $request->guest_email)
            ->whereHas('tickets', function($q) {
                $q->where('status', 'confirmed');
            })->exists();

        if ($alreadyConfirmed) {
            return redirect()->back()->with('error', 'This email already has a confirmed ticket.');
        }

        return redirect()->route('dinner.checkout', $request->all());
    }

    public function confirmation(Request $request, $id)
    {
        if ($id === 'new') {
            $registration = new DinnerRegister();
            $registration->first_name = $request->first_name;
            $registration->last_name = $request->last_name;
            $registration->email = $request->email;
            $registration->phone = $request->phone;
            $registration->setRelation('tickets', collect());
            
            return view('dinner.confirmation', compact('registration'));
        }

        $registration = DinnerRegister::with('tickets')->findOrFail($id);
        return view('dinner.confirmation', compact('registration'));
    }

    public function adminApprove($id)
    {
        $ticket = DinnerTicket::with(['registration', 'dinner'])->findOrFail($id);

        if ($ticket->status !== 'pending') {
            return back()->with('error', 'This ticket is already ' . $ticket->status);
        }

        $templatePath = public_path('images/ticket1.jpg');
        $fontPath     = public_path('assets/fonts/arial.ttf');
        $saveDir      = public_path('uploads/tickets');

        if (!File::exists($saveDir)) {
            File::makeDirectory($saveDir, 0777, true);
        }

        $downloadUrls = [];

        for ($i = 1; $i <= $ticket->quantity; $i++) {
            $uniqueCode = 'DIN-' . strtoupper(Str::random(6));

            \App\Models\SponsorCode::create([
                'dinner_id'          => $ticket->dinner_id,
                'dinner_ticket_id'   => $ticket->id,
                'dinner_register_id' => $ticket->dinner_register_id,
                'used_by_name'       => $ticket->registration->first_name . ' ' . $ticket->registration->last_name,
                'code'               => $uniqueCode,
                'max_uses'           => 1,
                'used_count'         => 0,
                'status'             => 'available'
            ]);

            // SANITIZED FILENAME: Remove spaces and add loop index to prevent overwriting
            $safeName = Str::slug($ticket->registration->first_name . ' ' . $ticket->registration->last_name);
            $fileName = $safeName . '_' . $ticket->registration->phone . '_' . time() . '_' . $i . '.png';
            $filePath = $saveDir . '/' . $fileName;

            if (file_exists($templatePath)) {
                $image = @imagecreatefromjpeg($templatePath);
                if ($image) {
                    $white = imagecolorallocate($image, 255, 255, 255);
                    $fullName = strtoupper($ticket->registration->first_name . ' ' . $ticket->registration->last_name);
                    $phone = $ticket->registration->phone ?? 'N/A';
                    
                    if (file_exists($fontPath)) {
                        imagettftext($image, 18, 0, 950, 86, $white, $fontPath, $fullName);
                        imagettftext($image, 18, 0, 950, 150, $white, $fontPath, $phone);
                        imagettftext($image, 22, 0, 980, 45, $white, $fontPath, $uniqueCode);

                        $checkInUrl = "https://test-myanrun.itplus.net.mm/ticket/verify/" . $uniqueCode;
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($checkInUrl);
                        
                        $qrCodeImage = @imagecreatefrompng($qrUrl);
                        if ($qrCodeImage) {
                            imagecopy($image, $qrCodeImage, 960, 200, 0, 0, 150, 150);
                            imagedestroy($qrCodeImage);
                        }
                    }
                    
                    imagepng($image, $filePath);
                    imagedestroy($image);
                    
                    $downloadUrls[] = asset('uploads/tickets/' . $fileName);
                }
            }
        }

        // Mark the main ticket as confirmed
        $ticket->update(['status' => 'confirmed']);

        while (ob_get_level()) { ob_end_clean(); }

        return back()
            ->with('success', count($downloadUrls) . ' ticket(s) generated successfully.')
            ->with('download_urls', $downloadUrls);
    }

    public function uploadPayment(Request $request, $id) 
    {
        $request->validate([
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dinner_id'    => 'required',
            'email'        => 'required|email',
            'first_name'   => 'required',
            'last_name'    => 'required',
            'price'        => 'required',
            'quantity'     => 'required|integer|min:1',
        ]);

        $dinner = Dinner::findOrFail($request->dinner_id);
        
        $sponsorId = null;
        $isSponsor = false;

        if ($request->filled('applied_code')) {
            $codeRecord = \App\Models\SponsorCode::where('code', $request->applied_code)->first();
            if ($codeRecord && $codeRecord->used_count < $codeRecord->max_uses) {
                $sponsorId = $codeRecord->sponsor_id;
                $codeRecord->increment('used_count');
                $isSponsor = true; 
            }
        }

        // --- CAPACITY CHECK ---
        if (!$isSponsor) {
            $totalPublicSeatsTaken = DinnerTicket::where('dinner_id', $dinner->id)
                                        ->whereIn('status', ['confirmed', 'pending'])
                                        ->whereNull('sponsor_id')
                                        ->sum('quantity');

            // Using public_capacity column specifically
            if ($dinner->public_capacity > 0 && ($totalPublicSeatsTaken + $request->quantity) > $dinner->public_capacity) {
                return redirect()->route('dinner.index')
                    ->with('error', 'The last available public seats were just taken.');
            }
        } else {
            // Optional: Add check for sponsor_capacity here if needed
        }

        // Create registration and ticket (Same as your existing code)
        $registration = DinnerRegister::updateOrCreate(
            ['email' => $request->email],
            [
                'user_id'     => Auth::check() ? Auth::id() : null,
                'first_name'  => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name'   => $request->last_name,
                'phone'       => $request->phone,
                'viber'       => $request->viber,
            ]
        );

        $imageName = 'slip_' . time() . '.' . $request->payment_slip->extension();
        $request->payment_slip->move(public_path('uploads/payments'), $imageName);
        $rawPrice = (int)str_replace(',', '', $request->price);

        DinnerTicket::create([
            'dinner_register_id' => $registration->id,
            'dinner_id'          => $request->dinner_id,
            'sponsor_id'         => $sponsorId,
            'ticket_no'          => 'DIN-' . strtoupper(bin2hex(random_bytes(3))),
            'type'               => $request->type ?? 'Standard',
            'price'              => $rawPrice,
            'quantity'           => (int)$request->quantity,
            'status'             => 'pending',
            'payment_slip'       => $imageName
        ]);

        return redirect()->route('dinner.index')->with('success', 'Submitted for review.');
    }

    public function manageDinners($timeframe)
{
    $status = ($timeframe === 'now') ? 1 : 0;

    // Fetch dinners with calculated sums for the cards
    $dinners = Dinner::where('is_active', $status)
        ->withSum(['tickets as public_count' => function ($query) {
            $query->whereIn('status', ['confirmed', 'pending'])->whereNull('sponsor_id');
        }], 'quantity')
        ->withSum(['tickets as sponsor_count' => function ($query) {
            $query->whereIn('status', ['confirmed', 'pending'])->whereNotNull('sponsor_id');
        }], 'quantity')
        ->orderBy('date', 'desc')
        ->get();

    $sidebarEvents = Dinner::where('is_active', 1)->where('date', '>', now())->take(3)->get();
    $title = ($timeframe === 'now') ? 'Active Dinners' : 'Past Dinners';

    return view('dashboard.dinner.manage', compact('dinners', 'timeframe', 'sidebarEvents', 'title'));
}

    public function edit($id)
    {
        $dinner = Dinner::findOrFail($id);
        return view('dashboard.dinner.edit', compact('dinner'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'date' => 'required|date',
        'public_capacity' => 'nullable|integer|min:0',
        'sponsor_capacity' => 'nullable|integer|min:0',
    ]);

    $dinner = Dinner::findOrFail($id);

    // FORCE THE MATH ON THE SERVER
    $publicCap = (int)$request->input('public_capacity', 0);
    $sponsorCap = (int)$request->input('sponsor_capacity', 0);
    $totalCapacity = $publicCap + $sponsorCap; // This will be 4 in your case

    $dinner->update([
        'name'             => $request->name,
        'company'          => $request->company,
        'location'         => $request->location,
        'date'             => $request->date,
        'is_active'        => $request->is_active,
        'public_capacity'  => $publicCap,
        'sponsor_capacity' => $sponsorCap,
        'capacity'         => $totalCapacity, // Force the database to save 4
    ]);

    // Handle Image Logic
    if ($request->hasFile('image')) {
        if ($dinner->image_path) \Storage::disk('public')->delete($dinner->image_path);
        $dinner->image_path = $request->file('image')->store('dinners', 'public');
        $dinner->save();
    }

    if ($request->hasFile('info_image')) {
        if ($dinner->info_image) \Storage::disk('public')->delete($dinner->info_image);
        $dinner->info_image = $request->file('info_image')->store('dinners/info', 'public');
        $dinner->save();
    }

    return redirect()->route('admin.dinner.manage', 'now')->with('success', "Updated! Total capacity is now $totalCapacity.");
}

    public function create() { return view('dashboard.dinner.create'); }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required', 
        'date' => 'required', 
        'image' => 'required|image',
        'public_capacity' => 'required|integer|min:0',
        'sponsor_capacity' => 'required|integer|min:0',
    ]);
    
    $imagePath = $request->file('image')->store('dinners', 'public');

    // Manual Calculation
    $totalCapacity = (int)$request->public_capacity + (int)$request->sponsor_capacity;
    
    Dinner::create([
        'name'             => $request->name,
        'company'          => $request->company,
        'location'         => $request->location,
        // Using request date directly if it's from a standard date input
        'date'             => $request->date, 
        'image_path'       => $imagePath,
        'is_active'        => $request->is_active,
        'public_capacity'  => $request->public_capacity,
        'sponsor_capacity' => $request->sponsor_capacity,
        'capacity'         => $totalCapacity, 
    ]);

    return redirect()->route('admin.dinner.manage', 'now')->with('success', 'Dinner created successfully!');
}

    public function showDinnerTickets(Request $request, $id)
    {
        $dinner = Dinner::withSum(['tickets as public_seats_count' => function ($query) {
            $query->whereIn('status', ['confirmed', 'pending'])->whereNull('sponsor_id');
        }], 'quantity')
        ->withSum(['tickets as sponsor_seats_count' => function ($query) {
            $query->whereIn('status', ['confirmed', 'pending'])->whereNotNull('sponsor_id');
        }], 'quantity')
        ->findOrFail($id);

        $query = DinnerTicket::where('dinner_id', $id)
                    ->with(['registration', 'sponsor'])
                    ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // We keep pagination, but the view will handle the "visual grouping"
        $tickets = $query->paginate(15);

        return view('dashboard.dinner.tickets', compact('dinner', 'tickets'));
    }

    public function adminReject($id) 
    {
        DinnerTicket::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Rejected.');
    }

    public function toggleScanning($id)
    {
        $dinner = Dinner::findOrFail($id);
        $dinner->is_scanning_open = !$dinner->is_scanning_open;
        $dinner->save();

        $status = $dinner->is_scanning_open ? 'Opened' : 'Closed';
        return back()->with('success', "Scanning has been {$status} for this event.");
    }

    public function publicVerify($code)
{
    // 1. Try to find the code in SponsorCodes first (Public/Guest Tickets)
    $codeRecord = \App\Models\SponsorCode::where('code', $code)
                    ->with(['ticket.registration', 'ticket.dinner'])
                    ->first();

    if ($codeRecord) {
        // --- CASE: PUBLIC TICKET ---
        $ticket = $codeRecord->ticket;
        $dinner = $ticket->dinner;
        $guestName = $codeRecord->used_by_name ?? ($ticket->registration->first_name ?? 'Guest');
        
        // Use updated_at for the grace period check
        $lastAction = $codeRecord->updated_at;
        $isAlreadyUsed = ($codeRecord->status === 'used');
    } else {
        // 2. Fallback: Check DinnerTickets table (Sponsor Batch Tickets)
        $ticket = \App\Models\DinnerTicket::where('ticket_no', $code)
                    ->with(['dinner', 'sponsor'])
                    ->first();

        if (!$ticket) {
            return redirect()->route('dinner.index')
                ->with('error', "Invalid Ticket: Code {$code} not found.");
        }

        // --- CASE: SPONSOR BATCH TICKET ---
        $dinner = $ticket->dinner;
        $guestName = $ticket->sponsor->company ?? 'Sponsor Guest';
        
        // Use scanned_at for the grace period check
        $lastAction = $ticket->scanned_at;
        $isAlreadyUsed = ($ticket->scanned_at !== null);
    }

    // 3. Common Checks
    if (!$dinner->is_scanning_open) {
        return redirect()->route('dinner.index')
            ->with('error', "Scanning is CLOSED for this event.");
    }

    if ($ticket->status !== 'confirmed') {
        return redirect()->route('dinner.index')
            ->with('error', "Verification Failed: Payment status is {$ticket->status}.");
    }

    // 4. Double Scan Prevention (Your 3-second logic)
    if ($isAlreadyUsed && $lastAction && $lastAction->diffInSeconds(now()) > 3) {
        $scanTime = $lastAction->timezone('Asia/Yangon')->format('h:i A');
        return redirect()->route('dinner.index')
            ->with('error', "ALREADY USED: This ticket was scanned at {$scanTime}.");
    }

    // 5. Success: Mark as Scanned
    if ($codeRecord) {
        // Update public code record
        $codeRecord->update([
            'status' => 'used',
            'used_count' => 1
        ]);
    } else {
        // Update sponsor ticket record
        $ticket->update([
            'scanned_at' => now()
        ]);
    }

    return redirect()->route('dinner.index')
        ->with('success', "✅ Verified! Welcome, {$guestName}.");
}
}