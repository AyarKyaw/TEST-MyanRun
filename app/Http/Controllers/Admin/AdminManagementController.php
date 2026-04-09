<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    // List all admins and agents
    public function index()
    {
        $superAdmins = Admin::where('role', 'super_admin')->latest()->get();
        $eventAdmins = Admin::where('role', 'event_admin')->latest()->get();
        $agents = Agent::latest()->get();

        return view('admin.admins.index', compact('superAdmins', 'eventAdmins', 'agents'));
    }

    // Show create form
    public function create()
    {
        return view('admin.admins.create');
    }

    // Store new admin OR agent
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Unique check handled manually below
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        if ($request->role === 'agent') {
            // Check uniqueness in agents table
            if (Agent::where('email', $request->email)->exists()) {
                return back()->withErrors(['email' => 'This email is already registered as an agent.']);
            }

            Agent::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $msg = 'New Support Agent Created!';
        } else {
            // Check uniqueness in admins table
            if (Admin::where('email', $request->email)->exists()) {
                return back()->withErrors(['email' => 'This email is already registered as an admin.']);
            }

            Admin::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            $msg = 'New Admin Created!';
        }

        return redirect('/dashboard/admins')->with('success', $msg);
    }

    // Show the edit form (Detects if it's an Agent or Admin via a query param)
    public function edit(Request $request, $id)
    {
        if ($request->query('type') === 'agent') {
            $admin = Agent::findOrFail($id);
            $type = 'agent';
        } else {
            $admin = Admin::findOrFail($id);
            $type = 'admin';
        }
        
        return view('admin.admins.edit', compact('admin', 'type'));
    }

    // Update the database
    public function update(Request $request, $id)
    {
        $role = $request->input('role');

        if ($role === 'agent') {
            $user = Agent::findOrFail($id);
            $request->validate(['email' => 'required|email|unique:agents,email,' . $id]);
        } else {
            $user = Admin::findOrFail($id);
            $request->validate([
                'email' => 'required|email|unique:admins,email,' . $id,
                'role'  => 'required',
            ]);
            $user->role = $request->role;
        }

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect('/dashboard/admins')->with('success', 'Account updated successfully!');
    }

    // Delete record
    public function destroy(Request $request, $id)
    {
        if ($request->query('type') === 'agent') {
            Agent::findOrFail($id)->delete();
            return back()->with('success', 'Agent removed successfully.');
        }

        $admin = Admin::findOrFail($id);
        if (auth()->guard('admin')->id() == $admin->id) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        $admin->delete();
        return back()->with('success', 'Admin removed successfully.');
    }
}