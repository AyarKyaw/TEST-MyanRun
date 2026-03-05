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
        
        // Get the status from the URL (e.g., ?status=pending)
        $status = $request->get('status');

        $tickets = \App\Models\Ticket::where('runner_id', $user->runner_id)
                    ->when($status, function ($query, $status) {
                        return $query->where('status', $status);
                    })
                    ->latest()
                    ->paginate(3)
                    ->withQueryString(); // Keeps the filters when you click "Next Page"

        $fullName = trim("{$user->first_name} {$user->mid_name} {$user->last_name}");

        $athlete = \App\Models\Athlete::where('runner_id', $user->runner_id)->first();

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