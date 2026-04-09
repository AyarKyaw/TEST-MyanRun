<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
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
        $agentId = Auth::guard('agent')->id();

        // Filter events: only those where the current agent is assigned in the pivot table
        $events = Event::whereHas('agents', function($q) use ($agentId) {
            $q->where('agent_id', $agentId);
        })
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('is_active');

        $nowEvents = $events->get(1, collect());  // Status 1 = Live
        $pastEvents = $events->get(0, collect()); // Status 0 = Past

        return view('agent.tickets', compact('nowEvents', 'pastEvents'));
    }
    /**
     * View Single Ticket Details
     */
    public function viewTicket(Request $request, $id)
{
    // 1. Get the authenticated Agent
    $agent = Auth::guard('agent')->user();

    // 1. Find the event AND verify assignment in one query
    $event = \App\Models\Event::whereHas('agents', function($q) use ($agent) {
        $q->where('agent_id', $agent->id);
    })->find($id);

    // 2. If not found or not assigned, block access
    if (!$event) {
        return redirect()->route('agent.tickets')
                         ->with('error', 'Unauthorized. You are not assigned to this event.');
    }
    
    $eventName = $event->name;

    // --- Role/Access Security ---
    // If you need to check if this agent is allowed to see this specific event, 
    // you would put that check here. For now, we proceed with the event found.

    // 3. Setup Filters
    $search = $request->query('search');
    $status = $request->query('status', 'pending'); 

    // 4. Build the Query
    $query = \App\Models\Ticket::where('event', $eventName)->with(['athlete.user']);

    // Filter by status
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    // Filter by Search
    if (!empty($search)) {
        $request->merge(['page' => 1]); // Reset pagination on search
        $searchTerm = trim($search);

        $query->where(function($q) use ($searchTerm) {
            $q->where('bib_number', 'LIKE', "%{$searchTerm}%")
              ->orWhere('bib_name', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('athlete.user', function($userQuery) use ($searchTerm) {
                  $userQuery->where(\DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"), 'LIKE', "%{$searchTerm}%")
                            ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere(\DB::raw("CONCAT_WS(' ', first_name, last_name)"), 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    // 5. Execute Pagination (using $customers to match your view)
    $customers = $query->orderBy('created_at', 'desc')
                       ->paginate(10) 
                       ->withQueryString();

    $eventLimit = $event?->total_max_slots;

    // 6. Stats (Counting UNIQUE bib_numbers)
    $counts = [
        'all' => \App\Models\Ticket::where('event', $eventName)
            ->whereNotNull('bib_number')
            ->distinct()
            ->count('bib_number'),

        'pending' => \App\Models\Ticket::where('event', $eventName)
            ->where('status', 'pending')
            ->whereNotNull('bib_number')
            ->distinct()
            ->count('bib_number'),

        'approved' => \App\Models\Ticket::where('event', $eventName)
            ->where('status', 'approved')
            ->whereNotNull('bib_number')
            ->distinct()
            ->count('bib_number'),

        'rejected' => \App\Models\Ticket::where('event', $eventName)
            ->where('status', 'rejected')
            ->whereNotNull('bib_number')
            ->distinct()
            ->count('bib_number'),

        'max_slots' => $eventLimit,
    ];

    // 7. Return to the Agent view
    return view('agent.detail', compact(
        'customers',
        'counts',
        'status',
        'eventName',
        'eventLimit', 
        'event',
        'search'
    ));
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

    public function create()
    {
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('agent.create');
    }

    public function store(Request $request)
    {
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'password' => 'required|min:8|confirmed',
        ]);

        \App\Models\Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Agent created successfully.');
    }

    public function edit($id)
    {
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            abort(403);
        }
        
        $admin = \App\Models\Agent::findOrFail($id);
        $type = 'agent'; // Passed to your partial
        return view('agent.edit', compact('admin', 'type'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            abort(403);
        }

        $agent = \App\Models\Agent::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $agent->name = $request->name;
        $agent->email = $request->email;
        if ($request->filled('password')) {
            $agent->password = Hash::make($request->password);
        }
        $agent->save();

        return redirect()->route('admin.admins.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy($id)
    {
        if (Auth::guard('admin')->user()->role !== 'super_admin') {
            abort(403);
        }

        \App\Models\Agent::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Agent deleted.');
    }
}