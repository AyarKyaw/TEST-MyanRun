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
        // Capture the ID from the URL (?dinner_id=3)
        $dinner = \App\Models\Dinner::findOrFail($request->dinner_id);

        return view('dinner.register', [
            'dinner' => $dinner,
            'selected_type' => $request->selected_type,
            'selected_price' => $request->selected_price
        ]);
    }

    public function checkoutPage(Request $request)
    {
        // Capture the ID again for the checkout view
        $dinner = \App\Models\Dinner::findOrFail($request->dinner_id);
        
        return view('dinner.checkout', compact('dinner'));
    }
    
    /**
     * Final Step: Save to Database and show confirmation
     */
    public function process(Request $request)
    {
        // 1. Save or Update the person only
        $registration = DinnerRegister::updateOrCreate(
            ['email' => $request->email], 
            [
                'user_id'     => Auth::check() ? Auth::id() : null,
                'first_name'  => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name'   => $request->last_name,
                'phone'       => $request->phone,
            ]
        );

        // 2. Redirect to confirmation using ONLY registration data
        // We pass dinner_id, type, and price in the URL so the next page knows what to bill
        return redirect()->route('dinner.confirmation', [
            'id' => $registration->id,
            'dinner_id' => $request->dinner_id,
            'type' => $request->type,
            'price' => $request->price
        ]);
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

    public function confirmation($id)
    {
        // Use 'with' to eager load the tickets relationship
        $registration = \App\Models\DinnerRegister::with('tickets')->findOrFail($id);

        return view('dinner.confirmation', compact('registration'));
    }

    // List tickets for Admin
    public function adminIndex() {
        $tickets = \App\Models\DinnerTicket::with('registration')->latest()->get();
        return view('dashboard.dinner.tickets', compact('tickets'));
    }

    // Approve a ticket
    public function adminApprove($id) {
        $ticket = \App\Models\DinnerTicket::findOrFail($id);
        $ticket->update(['status' => 'confirmed']);
        
        return back()->with('success', 'Ticket approved successfully!');
    }

    public function uploadPayment(Request $request, $id) // $id is now DinnerRegister ID
    {
        $request->validate([
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dinner_id' => 'required'
        ]);

        if ($request->hasFile('payment_slip')) {
            // 1. Save the Image
            $imageName = 'slip_' . time() . '.' . $request->payment_slip->extension();
            $request->payment_slip->move(public_path('uploads/payments'), $imageName);
            
            // 2. NOW create the ticket record for the first time
            $ticket = \App\Models\DinnerTicket::create([
                'dinner_register_id' => $id,
                'dinner_id' => $request->dinner_id,
                'ticket_no' => 'DIN-' . strtoupper(bin2hex(random_bytes(3))),
                'type'      => $request->type ?? 'Standard',
                'price'     => (int)str_replace(',', '', $request->price ?? 50000),
                'status'    => 'pending',
                'payment_slip' => $imageName
            ]);
        }

        return back()->with('success', 'Payment slip uploaded! Your ticket has been created and is awaiting verification.');
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

    public function showDinnerTickets($id)
    {
        // 1. Find the dinner or fail
        $dinner = \App\Models\Dinner::findOrFail($id);

        // 2. Get tickets linked to this dinner 
        // Assuming your relationship is defined in the Dinner model
        $tickets = \App\Models\DinnerTicket::where('dinner_id', $id)
                    ->with('registration') // Load Guest Name/Email info
                    ->latest()
                    ->get();

        // 3. Return the tickets.blade.php you provided
        return view('dashboard.dinner.tickets', compact('dinner', 'tickets'));
    }
}