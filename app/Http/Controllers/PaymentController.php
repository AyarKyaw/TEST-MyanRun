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
        'amount'       => 'required|numeric',
        'bib_name'     => 'required|string|max:255',
        'category'     => 'required|string|max:255',
    ]);

    // 2. Get session
    $order = session('pending_registration');
    if (!$order) {
        return redirect()->route('athlete.register')->with('error', 'Session expired.');
    }

    // 3. Get athlete (Captain)
    $athlete = \App\Models\Athlete::find($order['athlete_id']);
    if (!$athlete) {
        return back()->with('error', 'Athlete not found.');
    }

    // --- NEW: Fetch Ticket Type Model for Early Bird Logic ---
    $ticketTypeModel = \App\Models\EventTicketType::find($order['ticket_type_id']);
    
    // Count existing registrations to see if EB limit is reached
    $currentRegistrationsCount = \App\Models\Ticket::where('ticket_type_id', $order['ticket_type_id'])
        ->where('status', '!=', 'rejected')
        ->count();

    // 4. Generate ONE Bib for the team
    $generatedBib = $this->generateBib(
        $order['event_id'],
        $order['ticket_type_id'],
        $athlete->gender
    );
    
    // 5. Upload Slip
    $saveDir = public_path('uploads/payments');
    if (!\File::exists($saveDir)) \File::makeDirectory($saveDir, 0755, true);

    $imageName = 'slip_' . time() . '_' . uniqid() . '.' . $request->payment_slip->extension();
    $request->payment_slip->move($saveDir, $imageName);
    
    $event = \App\Models\Event::find($order['event_id']);
    
    // Use the amount from request as base
    $amount = (float) preg_replace('/[^0-9.]/', '', $request->amount);

    // Apply Early Bird Discount if applicable
    if ($ticketTypeModel && $ticketTypeModel->early_bird_limit > 0 && $currentRegistrationsCount < $ticketTypeModel->early_bird_limit) {
        $amount = $amount - ($ticketTypeModel->early_bird_discount ?? 0);
    }

    // --- 6. Create Ticket for CAPTAIN ---
    $captainTicket = Ticket::create([
        'athlete_id'       => $order['athlete_id'],
        'bib_name'         => $request->bib_name, 
        'bib_number'       => $generatedBib,
        'category'         => $order['category'],
        'ticket_type_id'   => $order['ticket_type_id'],
        'price'            => $amount, 
        'event_id'         => $order['event_id'],
        'event'            => $event->name, 
        't_shirt_size'     => $order['t_shirt_size'] ?? 'M',
        'experience_level' => $order['exp_level'] ?? 'Beginner',
        'transaction_id'   => $imageName,
        'status'           => 'pending',
    ]);

    // --- 7. Create Ticket for FRIEND (If Relay) ---
    // Note: Renamed variable to $registrationType to avoid conflict with $ticketTypeModel
    $registrationType = strtolower(session('ticket_type'));
    $friendUserId = session('friend_user_id');
    $friendReg = session('friend_registration');

    if ($registrationType === 'relay' && $friendUserId) {
        $friendUser = \App\Models\User::find($friendUserId);

        if ($friendUser) {
            $friendAthlete = \App\Models\Athlete::where('runner_id', $friendUser->runner_id)->first();
            
            if ($friendAthlete) {
                Ticket::create([
                    'athlete_id'       => $friendAthlete->id,
                    'bib_name'         => $friendReg['bib_name'] ?? ($friendAthlete->first_name . ' ' . $friendAthlete->last_name),
                    'bib_number'       => $generatedBib, 
                    'category'         => $order['category'],
                    'ticket_type_id'   => $order['ticket_type_id'],
                    'price'            => $amount, // Usually 0 because Captain paid the full discounted/regular amount
                    'event_id'         => $order['event_id'],
                    'event'            => $event->name, 
                    't_shirt_size'     => $friendReg['t_shirt_size'] ?? 'M', 
                    'experience_level' => $order['exp_level'] ?? 'Beginner',
                    'transaction_id'   => $imageName, 
                    'status'           => 'pending',
                ]);
                
                \Log::info("Friend ticket created for: " . $friendUser->runner_id);
            }
        }
    }

    // 8. Clear all registration sessions
    session()->forget(['pending_registration', 'friend_user_id', 'ticket_type', 'friend_registration']);

    return redirect()->route('user.dashboard')
        ->with('success', 'Registration submitted! Price recorded: ' . number_format($amount) . ' MMK');
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
}