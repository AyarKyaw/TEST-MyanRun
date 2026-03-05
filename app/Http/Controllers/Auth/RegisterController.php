<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function register(Request $request)
{   
    // 1. Validation Logic
    $request->validate([
        'first_name'  => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name'   => 'required|string|max:255',
        'email'       => 'required|string|email|max:255|unique:users',
        'phone'       => ['required', 'string', 'regex:/^[0-9]{7,9}$/'],
        'password'    => 'required|string|min:8|confirmed',
    ]);

    $lastUser = User::orderBy('runner_id', 'desc')->first();
    $nextNumber = $lastUser ? ((int)substr($lastUser->runner_id, 5) + 1) : 1;
    
    // Format it as RUN-001, RUN-002, etc.
    $runnerId = 'RUN-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    // 3. Create the User
    $user = User::create([
        'first_name'  => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name'   => $request->last_name,
        'email'       => $request->email,
        'phone'       => '+959' . ltrim($request->phone, '0'),
        'password'    => Hash::make($request->password),
        'runner_id'   => $runnerId, // Assign the dynamic ID here
    ]);


    Auth::login($user);

    return redirect('/')->with('success', 'Welcome to Myan Run!');
}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Welcome back, ' . Auth::user()->first_name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    
}