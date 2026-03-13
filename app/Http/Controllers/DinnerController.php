<?php

namespace App\Http\Controllers;

use App\Models\DinnerRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DinnerController extends Controller
{
    public function index()
    {
        // Fetch only active dinners
        $dinners = \App\Models\Dinner::where('is_active', 1)
                    ->orderBy('date', 'asc')
                    ->get();

        return view('dinner.index', compact('dinners'));
    }

    public function selectTickets($id)
    {
        // Find the dinner or fail
        $dinner = \App\Models\Dinner::findOrFail($id);

        // Pass the dinner object to the ticket selection view
        return view('dinner.ticket', compact('dinner'));
    }

    public function registerPage(Request $request)
    {
        $dinner = \App\Models\Dinner::findOrFail($request->dinner_id);

        return view('dinner.register', [
            'dinner'         => $dinner,
            'selected_type'  => $request->selected_type,
            'selected_price' => $request->selected_price,
            'quantity'       => $request->quantity ?? 1 // Capture quantity
        ]);
    }

    public function checkoutPage(Request $request)
    {
        // Capture the ID again for the checkout view
        $dinner = \App\Models\Dinner::findOrFail($request->dinner_id);
        
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
            'viber'        => $request->viber, // Add this line
            'applied_code' => $request->applied_code,
        ];

        return redirect()->route('dinner.confirmation', array_merge(['id' => 'new'], $data));
    }

    // This is used if you submit the register form directly to save 
    public function storeRegistration(Request $request) 
    {
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
        ]);

        // Just check if they already have a CONFIRMED ticket before letting them proceed
        $alreadyConfirmed = DinnerRegister::where('email', $request->guest_email)
            ->whereHas('tickets', function($q) {
                $q->where('status', 'confirmed');
            })->exists();

        if ($alreadyConfirmed) {
            return redirect()->back()->with('error', 'This email already has a confirmed ticket.');
        }

        // Pass data to checkout page via URL parameters
        return redirect()->route('dinner.checkout', $request->all());
    }

    public function confirmation(Request $request, $id)
    {
        if ($id === 'new') {
            // Create a temporary object so the view doesn't crash
            $registration = new \App\Models\DinnerRegister();
            $registration->first_name = $request->first_name;
            $registration->last_name = $request->last_name;
            $registration->email = $request->email;
            $registration->phone = $request->phone;
            
            // Mock the tickets relationship as an empty collection
            $registration->setRelation('tickets', collect());
            
            return view('dinner.confirmation', compact('registration'));
        }

        // For existing records (post-upload view)
        $registration = \App\Models\DinnerRegister::with('tickets')->findOrFail($id);
        return view('dinner.confirmation', compact('registration'));
    }

    // List tickets for Admin
    public function adminIndex() {
        $tickets = \App\Models\DinnerTicket::with('registration')->latest()->get();
        return view('dashboard.dinner.tickets', compact('tickets'));
    }

    public function adminApprove($id) {
        $ticket = \App\Models\DinnerTicket::with('registration')->findOrFail($id);
        
        // Optional: Auto-confirm status
        $ticket->update(['status' => 'confirmed']);

        $templatePath = public_path('images/ticket1.jpg');
        $fontPath = public_path('assets/fonts/arial.ttf');
        $saveDir = public_path('uploads/tickets');

        if (!\Illuminate\Support\Facades\File::exists($saveDir)) {
            \Illuminate\Support\Facades\File::makeDirectory($saveDir, 0777, true);
        }

        $fullName = $ticket->registration->first_name . ' ' . $ticket->registration->last_name;
        $phone = $ticket->registration->phone ?? 'N/A';
        $fileName = $fullName . $phone . '.png';
        $filePath = $saveDir . '/' . $fileName;

        if (file_exists($templatePath)) {
            $image = @imagecreatefromjpeg($templatePath);
            
            if ($image) {
                $white = imagecolorallocate($image, 255, 255, 255);

                if (file_exists($fontPath)) {
                    $ticketNo = $ticket->ticket_no;

                    // 1. Add Text Data
                    imagettftext($image, 18, 0, 950, 86, $white, $fontPath, strtoupper($fullName));
                    imagettftext($image, 18, 0, 950, 150, $white, $fontPath, $phone);
                    
                    imagettftext($image, 18, 0, 980, 40, $white, $fontPath, $ticket->ticket_no);

                    // 2. GENERATE & MERGE QR CODE
                    // We use a public API to get a QR code of the ticket number

                    $checkInUrl = "https://test-myanrun.itplus.net.mm/ticket/verify/" . $ticket->ticket_no;
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($checkInUrl);
                    $qrCodeImage = @imagecreatefrompng($qrUrl);

                    if ($qrCodeImage) {
                        // Get dimensions of QR
                        $qrWidth = imagesx($qrCodeImage);
                        $qrHeight = imagesy($qrCodeImage);

                        // Destination: Adjust these coordinates to fit your ticket layout
                        // Example: Bottom right corner area
                        $dstX = 960; 
                        $dstY = 200; 

                        imagecopy($image, $qrCodeImage, $dstX, $dstY, 0, 0, $qrWidth, $qrHeight);
                        imagedestroy($qrCodeImage);
                    }
                }

                imagepng($image, $filePath);
                imagedestroy($image);
            }
        }

        if (file_exists($filePath)) {
            $downloadUrl = asset('uploads/tickets/' . $fileName);
            
            return back()
                ->with('success', 'Ticket approved and generated!')
                ->with('download_url', $downloadUrl);
        }
        
        return back()->with('success', 'Ticket approved, but file could not be generated.');
    }

    public function publicVerify($ticket_no)
    {
        $ticket = \App\Models\DinnerTicket::where('ticket_no', $ticket_no)->first();

        if (!$ticket) {
            return redirect()->route('dinner.index')->with('error', "Ticket not found.");
        }

        // NEW: Allow a 10-second grace period for double-scans
        if ($ticket->scanned_at && $ticket->scanned_at->diffInSeconds(now()) > 10) {
            return redirect()->route('dinner.index')
                ->with('error', "ALREADY USED: Scanned at " . $ticket->scanned_at->timezone('Asia/Yangon')->format('h:i A'));
        }

        // Only update if it's currently NULL
        if (!$ticket->scanned_at) {
            $ticket->scanned_at = now();
            $ticket->save();
        }

        $name = $ticket->registration->first_name ?? ($ticket->type === 'Sponsored' ? 'Sponsored Guest' : 'Guest');
        
        return redirect()->route('dinner.index')
            ->with('success', "Ticket {$ticket_no} Verified! Welcome, {$name}");
    }

    public function uploadPayment(Request $request, $id) 
    {
        // 1. Validate - Add viber to the validation rules
        $request->validate([
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dinner_id'    => 'required',
            'email'        => 'required|email',
            'first_name'   => 'required',
            'last_name'    => 'required',
            'price'        => 'required',
            'quantity'     => 'required',
            'viber'        => 'nullable|string|max:20', // Add this line
        ]);

        $sponsorId = null;

        // 2. Handle Quota-based Sponsor Code (Keep your existing logic)
        if ($request->filled('applied_code')) {
            $codeRecord = \App\Models\SponsorCode::where('code', $request->applied_code)->first();
            if ($codeRecord && $codeRecord->used_count < $codeRecord->max_uses) {
                $sponsorId = $codeRecord->sponsor_id;
                $codeRecord->increment('used_count');
            }
        }

        // 3. Create or Update the Guest Registration
        // Ensure 'viber' is included in the update array
        $registration = DinnerRegister::updateOrCreate(
            ['email' => $request->email],
            [
                'user_id'     => Auth::check() ? Auth::id() : null,
                'first_name'  => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name'   => $request->last_name,
                'phone'       => $request->phone,
                'viber'       => $request->viber, // Add this line to save Viber data
            ]
        );

        // 4. Handle the Payment Slip Image (Keep your existing logic)
        $imageName = 'slip_' . time() . '.' . $request->payment_slip->extension();
        $request->payment_slip->move(public_path('uploads/payments'), $imageName);
        
        // 5. Save the Ticket
        $rawPrice = (int)str_replace(',', '', $request->price);

        \App\Models\DinnerTicket::create([
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

        return redirect()->route('dinner.index')->with('success', 'Thank you! Your registration and payment have been submitted for review.');
    }

    public function manageDinners($timeframe)
    {
        // Map the URL word to the database boolean
        $status = ($timeframe === 'now') ? 1 : 0;

        // Fetch dinners filtered by is_active
        $dinners = \App\Models\Dinner::where('is_active', $status)
                    ->orderBy('date', 'desc')
                    ->get();

        // Fetch upcoming dinners for that sidebar 'Coming Soon' section
        $sidebarEvents = \App\Models\Dinner::where('is_active', 1)
                            ->where('date', '>', now())
                            ->take(3)
                            ->get();

        $title = ($timeframe === 'now') ? 'Active Dinners' : 'Past Dinners';

        return view('dashboard.dinner.manage', compact('dinners', 'timeframe', 'sidebarEvents', 'title'));
    }

    public function edit($id)
    {
        $dinner = \App\Models\Dinner::findOrFail($id);
        return view('dashboard.dinner.edit', compact('dinner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',      // Added image validation
            'info_image' => 'nullable|image|max:2048', // Added info_image validation
        ]);

        $dinner = \App\Models\Dinner::findOrFail($id);
        
        $data = $request->only(['name', 'company', 'location', 'date', 'is_active']);

        // Handle Main Card Image
        if ($request->hasFile('image')) {
            if ($dinner->image_path && \Storage::disk('public')->exists($dinner->image_path)) {
                \Storage::disk('public')->delete($dinner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('dinners', 'public');
        }

        // Handle Detailed Info Image (The Modal Image)
        if ($request->hasFile('info_image')) {
            // Delete old info image if it exists
            if ($dinner->info_image && \Storage::disk('public')->exists($dinner->info_image)) {
                \Storage::disk('public')->delete($dinner->info_image);
            }
            $data['info_image'] = $request->file('info_image')->store('dinners/info', 'public');
        }

        $dinner->update($data);

        return redirect()->route('admin.dinner.manage', 'now')->with('success', 'Dinner updated successfully!');
    }

    public function create()
    {
        return view('dashboard.dinner.create');
    }

    // 2. Save the data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company' => 'required',
            'location' => 'required', // Add this
            'date' => 'required',
            'image' => 'required|image',
        ]);

        $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $imagePath = $request->file('image')->store('dinners', 'public');

        \App\Models\Dinner::create([
            'name' => $request->name,
            'company' => $request->company,
            'location' => $request->location, // Add this
            'date' => $formattedDate,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.dinner.manage', $request->is_active ? 'now' : 'past')
                        ->with('success', 'Dinner saved successfully!');
    }

    public function dinnerTicketsIndex()
{
    $dinners = \App\Models\Dinner::withCount([
        'tickets as pending_count' => function ($query) {
            $query->where('status', 'pending');
        },
        'tickets as confirmed_count' => function ($query) {
            $query->where('status', 'confirmed');
        }
    ])
    ->orderBy('date', 'desc')
    ->get()
    ->groupBy('is_active'); // Groups by 1 (Active) and 0 (Past)

    return view('dashboard.dinner.index_tickets', compact('dinners'));
}

    public function showDinnerTickets(Request $request, $id)
    {
        $dinner = \App\Models\Dinner::findOrFail($id);

        $query = \App\Models\DinnerTicket::where('dinner_id', $id)
                    ->with('registration')
                    // Add this line to put the most recent scans at the very top
                    ->orderByRaw('scanned_at IS NULL ASC') 
                    ->orderBy('scanned_at', 'desc')
                    ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(15);

        return view('dashboard.dinner.tickets', compact('dinner', 'tickets'));
    }

    public function adminReject($id) 
    {
        $ticket = \App\Models\DinnerTicket::findOrFail($id);
        
        // Update status to rejected
        $ticket->update(['status' => 'rejected']);

        return back()->with('success', 'Ticket has been moved to the rejected list.');
    }
}