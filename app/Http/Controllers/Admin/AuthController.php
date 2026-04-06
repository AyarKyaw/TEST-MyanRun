<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('admin.login');
    }

    public function login(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        
        $admin = Auth::guard('admin')->user();

        // Redirect Super Admins to the Team Management page
        if ($admin->role === 'super_admin') {
            return redirect()->route('admin.admins.index');
        }

        // Redirect Event Admins to their dashboard
        return redirect()->route('dashboard.register-level-1');
    }

    // Use 'error' to match the @if(session('error')) in your Blade file
    return back()->with('error', 'Invalid Admin Credentials or Access Denied.');
}

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}