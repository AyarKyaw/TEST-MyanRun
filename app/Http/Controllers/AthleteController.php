<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\Ticket;
use App\Models\User;
use App\Models\EventTicketType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AthleteController extends Controller
{   
    public function showAthleteForm(Request $request)
    {   
        if (!session()->has('ticket_category')) {
            return redirect()->route('ticket')->with('error', 'Please select a ticket first!');
        }

        // Use runner_id as the lookup key as per your database structure
        $athlete = Athlete::where('runner_id', Auth::user()->runner_id)->first();
        $category = session('ticket_category');
        $gender = old('gender', $athlete->gender ?? 'male');

        // Generate BIB based on the Tickets table
        $bibNumber = $this->generateBib($gender, $category);

        $nrcParts = ['state' => '', 'district' => '', 'naing' => 'နိုင်', 'number' => ''];
        if ($athlete && $athlete->nat_type === 'national' && !empty($athlete->id_number)) {
            try {
                $parts = explode('/', $athlete->id_number);
                $nrcParts['state'] = $parts[0];
                $subParts = explode('(', $parts[1]);
                $nrcParts['district'] = $subParts[0];
                $numberParts = explode(')', $subParts[1]);
                $nrcParts['naing'] = $numberParts[0];
                $nrcParts['number'] = $numberParts[1];
            } catch (\Exception $e) { }
        }

        $friendUser = null;

        if (session()->has('friend_user_id')) {
            $friendUser = \App\Models\User::find(session('friend_user_id'));
        }

        return view('ticket.register-athlete', [
            'athlete' => $athlete,
            'nrcParts' => $nrcParts,
            'category' => $category,
            'bibNumber' => $bibNumber,
            'price' => session('ticket_price'),
            'type' => session('nat_type'),
            'eventName' => session('event_name', 'Official Race'),
            'friendUser' => $friendUser,
        ]);
    }

    private function generateBib($gender, $category)
{
    $prefix = (strtolower($gender) === 'female') ? 'F' : 'M';
    
    preg_match('/\d+/', $category, $matches);
    $distance = $matches[0] ?? '00';
    $searchPattern = $prefix . $distance; // e.g., "F16"

    // Find the highest existing BIB for this specific prefix + distance
    $lastBib = Ticket::where('bib_number', 'LIKE', $searchPattern . '%')
        ->where('status', '!=', 'rejected')
        ->orderBy('bib_number', 'desc')
        ->first();

    if ($lastBib) {
        // Extract the last 4 digits from the string (e.g., F160011 -> 0011)
        $lastNumber = (int) substr($lastBib->bib_number, -4);
        $newNumberInt = $lastNumber + 1;
    } else {
        $newNumberInt = 11; // Starting point
    }

    return $searchPattern . str_pad($newNumberInt, 4, '0', STR_PAD_LEFT);
}

    public function handleSelection(Request $request) 
{   
    $ticketType = EventTicketType::find($request->ticket_type_id);

    if (!$ticketType) {
        return back()->with('error', 'Ticket type not found.');
    }

    session([
        'ticket_type_id' => $ticketType->id,
        'ticket_type'    => $ticketType->type, // 'relay' or 'solo'
        'ticket_price'   => $request->price,
        'nat_type'       => $request->nationality,
        'event_id'       => $request->event_id,
        // ADD THIS LINE BELOW:
        'ticket_category' => $ticketType->name, // Ensure this field exists (e.g., "10KM", "21KM")
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
    $request->validate([
        'face_image' => 'required_without:existing_face|nullable|image|max:2048',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'bib_name' => 'required|string|max:20', 
        'contact' => 'required|digits_between:9,11',
        'dob' => 'required|date_format:d/m/Y',
        'has_itra' => 'required|in:yes,no',
        'itra_details' => 'required_if:has_itra,yes',
        'gender' => 'required',
        't_shirt_size' => 'required',
        'blood_type' => 'required',
        'nrc_number' => 'nullable|required_if:nat_type,national|digits:6',
    ]);

    $formattedDob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');

    // NRC Logic
    $idNumber = ($request->nat_type === 'national') 
        ? "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_naing})" . str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT)
        : strtoupper($request->passport_id);

    // Image Logic
    $path = $request->existing_face;
    if ($request->hasFile('face_image')) {
        $path = $request->file('face_image')->store('athletes', 'public');
    }

    $finalBib = $this->generateBib($request->gender, session('ticket_category'));

    // Create/Update the Captain (Primary User)
    $athlete = Athlete::updateOrCreate(
        ['runner_id' => Auth::user()->runner_id],
        [
            'face_image_path' => $path,
            'nat_type' => $request->nat_type,
            'id_number' => $idNumber,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'address' => $request->address,
            'dob' => $formattedDob,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'blood_type' => $request->blood_type,
            'contact' => $request->contact,
            'state' => $request->state,
            'viber' => $request->viber,
            'has_medical_condition' => $request->has_condition === 'yes',
            'medical_details' => $request->medical_conditions,
            'has_itra' => $request->has_itra === 'yes',
            'itra_details' => $request->itra_details,
        ]
    );

    // Save registration to session
    session([
        'pending_registration' => [
            'athlete_id'   => $athlete->id,
            'bib_name'     => $request->bib_name,
            'bib_number'   => $finalBib,
            't_shirt_size' => $request->t_shirt_size,
            'category'     => session('ticket_category'),
            'ticket_type_id' => session('ticket_type_id'), 
            'price'        => session('ticket_price'),
            'event_id'     => session('event_id'), 
            'exp_level'    => $request->exp,
            'status'       => 'pending'
        ]
    ]);

    if (session('ticket_type') === 'relay') {
    // Check if the user is attempting to link an existing account
    if ($request->filled('friend_runner_id')) {
        
        // If they provided an ID but forgot the password, catch it here
        if (!$request->filled('friend_password')) {
            return back()->withErrors(['friend_password' => 'Password is required to link this Runner ID.'])->withInput();
        }

        $friend = User::where('runner_id', $request->friend_runner_id)->first();

        // 1. Check if user exists AND password matches
        if (!$friend || !\Hash::check($request->friend_password, $friend->password)) {
            return back()
                ->withErrors(['friend_password' => 'Partner Runner ID or Password does not match our records.'])
                ->withInput();
        }

        // 2. Prevent self-pairing
        if ($friend->id === Auth::id()) {
            return back()->withErrors(['friend_runner_id' => 'You cannot be your own partner.'])->withInput();
        }

        // 3. Success: Link the existing user
        session(['friend_user_id' => $friend->id]);
        
    } else {
        // No Runner ID provided: Clear any old session data to ensure a "Fresh" friend registration
        session()->forget('friend_user_id');
    }

    // Move to the friend registration page (will be blank if no ID was linked)
    return redirect()->route('friend.register'); 
}

    // Default: Go to consent
    return redirect()->route('athlete.consent');
}
    public function showFriendRegisterForm(Request $request)
{
    if (!session()->has('pending_registration')) {
        return redirect()->route('athlete.register')->with('error', 'Please register yourself first.');
    }

    $friendAthlete = null;
    $friendUser = null;
    $nrcParts = ['state' => '', 'district' => '', 'naing' => 'နိုင်', 'number' => ''];

    // If the session already has a friend ID (from a previous step or login)
    if (session()->has('friend_user_id')) {
        $friendUser = User::find(session('friend_user_id'));
        
        if ($friendUser) {
            // Pull existing profile if it exists
            $friendAthlete = Athlete::where('runner_id', $friendUser->runner_id)->first();
            
            // If they have an NRC, split it for the dropdowns
            if ($friendAthlete && $friendAthlete->nat_type === 'national' && !empty($friendAthlete->id_number)) {
                try {
                    $parts = explode('/', $friendAthlete->id_number);
                    $nrcParts['state'] = $parts[0];
                    $subParts = explode('(', $parts[1]);
                    $nrcParts['district'] = $subParts[0];
                    $numberParts = explode(')', $subParts[1]);
                    $nrcParts['naing'] = $numberParts[0] ?? 'နိုင်';
                    $nrcParts['number'] = $numberParts[1] ?? '';
                } catch (\Exception $e) { }
            }
        }
    }

    return view('ticket.friend_register', [
        'friendAthlete' => $friendAthlete,
        'friendUser' => $friendUser, 
        'nrcParts' => $nrcParts,
        'category' => session('ticket_category'),
        'type' => session('nat_type', 'national'), 
    ]);
}

    public function submitFriend(Request $request)
{
    // 1. Validation (Same as Captain + Email/Phone)
    $request->validate([
        'face_image' => 'required_without:existing_face|nullable|image|max:2048',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required_without:existing_user|email', // Required if new user
        'phone_1' => [
            'required_without:existing_user_id',
            'nullable',
            // Allows optional +, then digits. Total length 9 to 15 characters.
            'regex:/^\+?[0-9]{9,15}$/' 
        ],
        'bib_name' => 'required|string|max:20', 
        'contact' => 'required|digits_between:9,11',
        'dob' => 'required|date_format:d/m/Y',
        'gender' => 'required',
        't_shirt_size' => 'required',
        'blood_type' => 'required',
        'nrc_number' => 'nullable|required_if:nat_type,national|digits:6',
    ]);

    // 2. Handle User Creation or Retrieval
    if (session()->has('friend_user_id')) {
        // Use the existing user verified in the previous step
        $friendUser = User::find(session('friend_user_id'));
        $newPartnerRunnerId = $friendUser->runner_id;
    } else {
        // Brand New User: Generate Runner ID
        $lastUser = User::orderBy('runner_id', 'desc')->first();
        $nextNumber = $lastUser ? ((int)substr($lastUser->runner_id, 5) + 1) : 1;
        $newPartnerRunnerId = 'RUN-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Create the User
        $friendUser = User::create([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'phone'       => '+959' . ltrim($request->phone_1, '0'),
            'password'    => Hash::make($request->phone_1), // Temp password is their phone
            'runner_id'   => $newPartnerRunnerId,
        ]);

        session(['friend_user_id' => $friendUser->id]);
    }

    // 3. Logic for NRC / Image / Date (Same as Captain)
    $formattedDob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
    
    $idNumber = ($request->nat_type === 'national') 
        ? "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_naing})" . str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT)
        : strtoupper($request->passport_id);

    $path = $request->existing_face;
    if ($request->hasFile('face_image')) {
        $path = $request->file('face_image')->store('athletes', 'public');
    }

    // 4. Create/Update Athlete Profile
    $friendAthlete = Athlete::updateOrCreate(
        ['runner_id' => $newPartnerRunnerId],
        [
            'face_image_path' => $path,
            'nat_type' => $request->nat_type,
            'id_number' => $idNumber,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'address' => $request->address,
            'dob' => $formattedDob,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'blood_type' => $request->blood_type,
            'contact' => $request->contact,
            'state' => $request->state,
            'viber' => $request->viber,
            'has_medical_condition' => $request->has_condition === 'yes',
            'medical_details' => $request->medical_conditions,
            'has_itra' => $request->has_itra === 'yes',
            'itra_details' => $request->itra_details,
        ]
    );

    // 5. Store Friend Registration in Session
    // This allows the summary/checkout page to see both athletes
    session([
        'friend_registration' => [
            'athlete_id'   => $friendAthlete->id,
            'bib_name'     => $request->bib_name,
            'bib_number'   => $this->generateBib($request->gender, session('ticket_category')),
            't_shirt_size' => $request->t_shirt_size,
        ]
    ]);

    return redirect()->route('athlete.consent');
}
    public function verifyFriend(Request $request)
    {
        $request->validate([
            'friend_runner_id' => 'required',
            'friend_password' => 'required'
        ]);

        $user = User::where('runner_id', $request->friend_runner_id)->first();

        if ($user && Hash::check($request->friend_password, $user->password)) {
            session(['friend_user_id' => $user->id]);
            
            // DEBUG: If this shows the ID, the session is working locally. 
            // The problem is the redirect/middleware.
            dd(session('friend_user_id')); 
        }

        // Set the session
        session(['friend_user_id' => $user->id]);
        
        // Force save to ensure it persists before redirect
        session()->save(); 

        // REDIRECT TO FRIEND FORM, NOT CAPTAIN FORM
        return redirect()->route('friend.register'); 
    }

    public function showConsent()
    {
        $data = session('pending_registration');

        if (!$data) {
            return redirect()->route('athlete.register')->with('error', 'Session expired. Please fill the form again.');
        }

        return view('ticket.consent', compact('data'));
    }
}