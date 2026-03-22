<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    
    // 1. Find the athlete record first using the runner_id
    $athlete = \App\Models\Athlete::where('runner_id', $user->runner_id)->first();

    // 2. Get the status from the URL for filtering
    $status = $request->get('status');

    // 3. Fetch tickets using the ATHLETE'S ID, not the user's ID
    $tickets = \App\Models\Ticket::query()
        ->when($athlete, function ($query) use ($athlete) {
            return $query->where('athlete_id', $athlete->id);
        })
        ->unless($athlete, function ($query) {
            return $query->whereRaw('1 = 0'); // Return nothing if no athlete record exists
        })
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->latest()
        ->paginate(3)
        ->withQueryString();

    $fullName = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");

    return view('user.dashboard', compact('user', 'tickets', 'fullName', 'athlete'));
}

    public function dashboard()
    {
        // Fetch all users and the total count
        $customers = User::all(); 
        $totalCount = User::count();

        return view('dashboard.register.register-level-1', compact('customers', 'totalCount'));
    }
}