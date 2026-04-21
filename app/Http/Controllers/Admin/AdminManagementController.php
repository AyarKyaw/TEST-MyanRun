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
        $financeAdmins = Admin::where('role', 'finance_admin')->latest()->get(); // Added Finance Admins
        $eventAdmins = Admin::where('role', 'event_admin')->latest()->get();
        $agents = Agent::latest()->get();

        return view('admin.admins.index', compact('superAdmins', 'financeAdmins', 'eventAdmins', 'agents'));
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
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        if ($request->role === 'agent') {
            if (Agent::where('email', $request->email)->exists()) {
                return back()->withErrors(['email' => 'This email is already registered as an agent.']);
            }

            Agent::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $msg = 'New Support Agent Created!';
        } else {
            if (Admin::where('email', $request->email)->exists()) {
                return back()->withErrors(['email' => 'This email is already registered as an admin.']);
            }

            Admin::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role, // Stores 'finance_admin', 'super_admin', or 'event_admin'
            ]);
            
            // Custom message based on role
            $roleNames = [
                'finance_admin' => 'Finance Admin',
                'super_admin' => 'Super Admin',
                'event_admin' => 'Event Admin'
            ];
            $msg = 'New ' . ($roleNames[$request->role] ?? 'Admin') . ' Created!';
        }

        return redirect('/dashboard/admins')->with('success', $msg);
    }

    // Show the edit form
    public function edit(Request $request, $id)
    {
        if ($request->query('type') === 'agent') {
            $admin = Agent::findOrFail($id);
            $type = 'agent';
        } else {
            $admin = Admin::findOrFail($id);
            // Pass the specific role type to the view for dynamic headers
            $type = $admin->role === 'finance_admin' ? 'finance' : 'admin';
        }
        
        return view('admin.admins.edit', compact('admin', 'type'));
    }

    // Update the database
    public function update(Request $request, $id)
    {
        $type = $request->input('type');

        if ($type === 'agent') {
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
        return back()->with('success', 'Account removed successfully.');
    }
}