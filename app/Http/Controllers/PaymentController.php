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
            return redirect()->route('athlete.register')
                ->with('error', 'Session expired. Please try again.');
        }

        // 3. Prevent duplicate registration
        $exists = Ticket::where('athlete_id', $order['athlete_id'])
            ->where('event_id', $order['event_id'])
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->route('public.events')
                ->with('error', 'You already registered for this event.');
        }

        // 4. Get athlete
        $athlete = \App\Models\Athlete::find($order['athlete_id']);
        if (!$athlete) {
            return back()->with('error', 'Athlete not found.');
        }

        // 5. Generate Bib safely
        $gender = $athlete->gender ?? 'male';
        $category = $order['category'] ?? $request->category;
        $generatedBib = $this->generateBib(
            $order['event_id'],
            $order['ticket_type_id'],
            $athlete->gender
        );
        
        // 6. Upload Slip
        $saveDir = public_path('uploads/payments');

        if (!\File::exists($saveDir)) {
            \File::makeDirectory($saveDir, 0755, true);
        }

        $imageName = 'slip_' . time() . '_' . uniqid() . '.' . $request->payment_slip->extension();

        $request->payment_slip->move($saveDir, $imageName);
        $event = \App\Models\Event::find($order['event_id']);

        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        // 7. Clean amount
        $amount = (float) preg_replace('/[^0-9.]/', '', $request->amount);
        // 8. Create Ticket
        $ticket = Ticket::create([
            'athlete_id'       => $order['athlete_id'],
            'bib_name'         => $request->bib_name,
            'bib_number'       => $generatedBib,
            'category'         => $category,
            'ticket_type_id'   => $order['ticket_type_id'],
            'price'            => $amount,
            'event_id'         => $order['event_id'],
            'event'            => $event->name, 
            't_shirt_size'     => $order['t_shirt_size'] ?? 'M',
            'experience_level' => $order['exp_level'] ?? 'Beginner',
            'transaction_id'   => $imageName, // slip image
            'status'           => 'pending',
        ]);

        // 9. Clear session
        session()->forget('pending_registration');

        // 10. Redirect
        return redirect()->route('user.dashboard')
            ->with('success', 'Registration submitted! We will verify your payment slip soon.');
    }

    private function generateBib($eventId, $ticketTypeId, $gender = 'male')
    {
        $ticketType = \App\Models\EventTicketType::find($ticketTypeId);

        if (!$ticketType) {
            return 'UNK-000';
        }

        // Optional gender prefix
        $genderPrefix = (strtolower($gender) === 'female') ? 'F' : 'M';

        $prefix = strtoupper($ticketType->prefix); // RUN / SPN
        $start  = $ticketType->start_number ?? 1;

        $fullPrefix = $prefix; // e.g. MRUN / FSPN

        // ✅ Only check this event + ticket type
        $usedBibs = \App\Models\Ticket::where('event_id', $eventId)
            ->where('ticket_type_id', $ticketTypeId)
            ->where('status', '!=', 'rejected')
            ->pluck('bib_number')
            ->toArray();

        $usedNumbers = [];

        foreach ($usedBibs as $bib) {
            if (strpos($bib, $fullPrefix . '-') === 0) {
                $usedNumbers[] = (int) substr($bib, strlen($fullPrefix) + 1);
            }
        }

        // ✅ Start from DB value
        $number = $start;

        while (in_array($number, $usedNumbers)) {
            $number++;
        }

        return $fullPrefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}