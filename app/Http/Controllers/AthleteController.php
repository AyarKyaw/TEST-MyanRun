<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AthleteController extends Controller
{   
    public function showAthleteForm(Request $request)
    {   
        if (!session()->has('ticket_category') && !request()->has('type')) {
            return redirect()->route('ticket')
                            ->with('error', 'Please select a ticket first!');
        }

        $athlete = Athlete::where('runner_id', Auth::user()->runner_id)->first();
        $category = session('ticket_category');
        $price = session('ticket_price'); 
        $type = session('nat_type'); 
        $eventName = session('event_name', 'Official Race');

        // Initialize empty parts
        $nrcParts = [
            'state' => '',
            'district' => '',
            'naing' => 'နိုင်', // Default
            'number' => ''
        ];

        // IF ATHLETE EXISTS, PARSE THE NRC STRING
        // Example string: "12/DAKANA(နိုင်)123456"
        if ($athlete && $athlete->nat_type === 'national' && !empty($athlete->id_number)) {
            try {
                // 1. Get State (Everything before first '/')
                $parts = explode('/', $athlete->id_number);
                $nrcParts['state'] = $parts[0];

                // 2. Get District (Everything between '/' and '(' )
                $subParts = explode('(', $parts[1]);
                $nrcParts['district'] = $subParts[0];

                // 3. Get Number (Everything after ')' )
                $numberParts = explode(')', $subParts[1]);
                $nrcParts['naing'] = $numberParts[0]; // Usually 'နိုင်'
                $nrcParts['number'] = $numberParts[1];
            } catch (\Exception $e) {
                // If parsing fails due to old data format, just leave it blank
            }
        }

        return view('register-athlete', [
            'athlete' => $athlete,
            'nrcParts' => $nrcParts,
            'category' => $category,
            'price' => $price,
            'type' => $type,
            'eventName' => $eventName
        ]);
    }

    public function handleSelection(Request $request) 
    {
        // MATCH THESE TO YOUR HTML 'NAME' ATTRIBUTES
        session([
            'ticket_category' => $request->selected_category, // Changed
            'ticket_price'    => $request->selected_price,    // Changed
            'nat_type'        => $request->nationality,       // Changed
            'event_name'      => $request->event_name ?? 'Official Race',
        ]);

        return redirect()->route('athlete.register');
    }

    public function dashboard()
    {
        // Fetch all records from the Athletes table instead of Users
        $customers = \App\Models\Athlete::all(); 
        $totalCount = \App\Models\Athlete::count();

        return view('dashboard.register.register-level-2', compact('customers', 'totalCount'));
    }

    public function submit(Request $request)
    {   
        // 1. Validation
        $request->validate([
            'face_image' => 'required_without_all:existing_face,face_base64|nullable|image|mimes:jpeg,png,jpg|max:2048', // Required for new registration
            'nat_type' => 'required|in:national,foreigner',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|date_format:d/m/Y',
            'nrc_state' => 'nullable|required_if:nat_type,national',
            'nrc_district' => 'nullable|required_if:nat_type,national',
            'nrc_number' => 'nullable|required_if:nat_type,national|digits:6',
            'passport_id' => 'nullable|required_if:nat_type,foreigner',
            'gender' => 'required',
            'father_name' => 'required|string',
        ]);

        // 2. DOB Conversion: d/m/Y -> Y-m-d (Correct way for DB)
        $formattedDob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');

        // 3. ID Number Formatting
        if ($request->nat_type === 'national') {
            $paddedNrc = str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT);
            $idNumber = "{$request->nrc_state}/{$request->nrc_district}(နိုင်){$paddedNrc}";
        } else {
            $idNumber = strtoupper($request->passport_id);
        }

        // 4. File Handling
        $path = null;

        if ($request->hasFile('face_image')) {
            // Option A: New File Upload
            $path = $request->file('face_image')->store('athletes', 'public');
        } elseif ($request->filled('face_base64')) { 
            // Option B: AI Camera Snapshot
            $fileName = 'face_' . time() . '.png';
            $imageData = str_replace(['data:image/png;base64,', 'data:image/jpeg;base64,', ' '], ['', '', '+'], $request->face_base64);
            Storage::disk('public')->put('athletes/' . $fileName, base64_decode($imageData));
            $path = 'athletes/' . $fileName;
        } elseif ($request->filled('existing_face')) {
            // Option C: Use the existing path already in the database
            $path = $request->existing_face;
        }

        // 5. Create Athlete (Note: Changed your 'exists' check to ensure logic flow)
        // If the athlete record doesn't exist, create it. 
        // If you want to UPDATE instead of CREATE if they exist, use updateOrCreate().
        Athlete::updateOrCreate(
            ['runner_id' => Auth::user()->runner_id],
            [
                'face_image_path' => $path,
                'nat_type' => $request->nat_type,
                'id_number' => $idNumber,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'dob' => $formattedDob,
                'nationality' => $request->nationality,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone_2' => $request->phone_2,
                'social_account' => $request->social_account,
            ]
        );

        // 6. Create Ticket
        session([
            'checkout_data' => [
                'runner_id' => Auth::user()->runner_id,
                'category'  => session('ticket_category'),
                'price'     => session('ticket_price'),
                'event'     => session('event_name') ?? 'Official Race 2026',
                'status'    => 'pending'
            ]
        ]);

        // session()->forget(['ticket_category', 'ticket_price']);

        // Redirect to root with success
        return redirect()->route('checkout.review');
    }
}