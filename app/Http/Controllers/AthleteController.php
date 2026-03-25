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

        return view('ticket.register-athlete', [
            'athlete' => $athlete,
            'nrcParts' => $nrcParts,
            'category' => $category,
            'bibNumber' => $bibNumber,
            'price' => session('ticket_price'),
            'type' => session('nat_type'),
            'eventName' => session('event_name', 'Official Race')
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

        $idNumber = ($request->nat_type === 'national') 
            ? "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_naing})" . str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT)
            : strtoupper($request->passport_id);

        $path = $request->existing_face;
        if ($request->hasFile('face_image')) {
            $path = $request->file('face_image')->store('athletes', 'public');
        }

        $finalBib = $this->generateBib($request->gender, session('ticket_category'));

        $athlete = Athlete::updateOrCreate(
            ['runner_id' => Auth::user()->runner_id],
            [
                'face_image_path' => $path,
                'nat_type' => $request->nat_type,
                'id_number' => $idNumber,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'dob' => $formattedDob,
                'gender' => $request->gender,
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

        // SYNCED: Saving to 'pending_registration' to match showConsent
        session([
            'pending_registration' => [
                'athlete_id'   => $athlete->id,
                'bib_name'     => $request->bib_name,
                'bib_number'   => $finalBib,
                't_shirt_size' => $request->t_shirt_size,
                'category'     => session('ticket_category'),
                'price'        => session('ticket_price'),
                'event'        => session('event_name', 'Official Race'),
                'exp_level'    => $request->exp,
                'status'       => 'pending'
            ]
        ]);

        return redirect()->route('athlete.consent');
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