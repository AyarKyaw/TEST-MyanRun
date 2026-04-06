<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    // List all admins
    public function index()
{
    // Split admins into two collections
    $superAdmins = Admin::where('role', 'super_admin')->latest()->get();
    $eventAdmins = Admin::where('role', 'event_admin')->latest()->get();

    return view('admin.admins.index', compact('superAdmins', 'eventAdmins'));
}

public function destroy($id)
{
    $admin = Admin::findOrFail($id);

    // Prevent self-deletion
    if (auth()->guard('admin')->id() == $admin->id) {
        return back()->with('error', 'You cannot delete yourself!');
    }

    $admin->delete();
    return back()->with('success', 'Admin removed successfully.');
}

    // Show create form
    public function create()
    {
        return view('admin.admins.create');
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/dashboard/admins')->with('success', 'New Admin Created!');
    }

    // Show the edit form
public function edit($id)
{
    $admin = Admin::findOrFail($id);
    return view('admin.admins.edit', compact('admin'));
}

// Update the database
public function update(Request $request, $id)
{
    $admin = Admin::findOrFail($id);

    $request->validate([
        'email' => 'required|email|unique:admins,email,' . $admin->id,
        'role'  => 'required',
    ]);

    $admin->email = $request->email;
    $admin->role  = $request->role;

    // Only update password if a new one is provided
    if ($request->filled('password')) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return redirect('/dashboard/admins')->with('success', 'Admin updated successfully!');
}
}