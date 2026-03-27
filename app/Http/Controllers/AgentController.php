<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    /**
     * Show the Agent Login Form
     */
    public function showLogin()
    {
        return view('agent.login');
    }

    /**
     * Handle Agent Login Request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('agent')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('agent.tickets');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our agent records.',
        ]);
    }

    /**
     * Agent Dashboard / Ticket List (READ ONLY)
     */
    public function dashboard(Request $request)
    {
        // 1. Start the query with relationships
        $query = \App\Models\Ticket::with(['athlete.user']);

        // 2. Handle Search (Name or BIB)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bib_number', 'LIKE', "%$search%")
                ->orWhere('bib_name', 'LIKE', "%$search%")
                ->orWhereHas('athlete', function($inner) use ($search) {
                    $inner->where('id_number', 'LIKE', "%$search%")
                            ->orWhere('first_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%");
                });
            });
        }

        // 3. Handle Status Filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 4. Get Counts for the tabs (optional but keeps UI consistent)
        $counts = [
            'all'      => \App\Models\Ticket::count(),
            'pending'  => \App\Models\Ticket::where('status', 'pending')->count(),
            'approved' => \App\Models\Ticket::where('status', 'approved')->count(),
            'rejected' => \App\Models\Ticket::where('status', 'rejected')->count(),
        ];

        // 5. Paginate and name the variable $customers to match your blade file
        $customers = $query->latest()->paginate(50)->withQueryString();

        return view('agent.tickets', compact('customers', 'counts'));
    }

    /**
     * View Single Ticket Details
     */
    public function viewTicket($id)
    {
        $ticket = Ticket::with(['athlete.user'])->findOrFail($id);
        return view('agent.ticket-details', compact('ticket'));
    }

    /**
     * Agent Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login');
    }
}