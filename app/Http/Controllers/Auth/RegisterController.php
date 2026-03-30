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
    
    public function verifyUser(Request $request)
{
    $email = trim($request->email);
    $nameInput = preg_replace('/\s+/', ' ', trim($request->name)); 
    
    // CLEAN THE INPUT: 
    // This regex looks for +959, 959, or 09 at the start and strips it
    $phoneInput = trim($request->phone);
    $cleanInput = preg_replace('/^(\+959|959|09)/', '', $phoneInput);
    
    // Just in case they typed 9xxxx (without the 0)
    $cleanInput = ltrim($cleanInput, '0'); 

    // Find user by Email
    $user = User::where('email', $email)->first();

    if ($user) {
        // CLEAN THE DATABASE PHONE:
        // Since your register function saves it as +959 + numbers
        $cleanDB = preg_replace('/^(\+959|959|09)/', '', $user->phone);
        $cleanDB = ltrim($cleanDB, '0');

        

        // Compare the "Core" numbers (e.g., 450001234)
        if ($cleanInput === $cleanDB) {
            
            // Compare Names (Ensure we use middle_name to match your RegisterController)
            $dbName = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");
            $dbName = preg_replace('/\s+/', ' ', $dbName);
            
            if (strtolower($dbName) === strtolower($nameInput)) {
                session(['reset_user_id' => $user->id]);
                return redirect()->route('password.reset_view');
            }
        }
    }

    return back()->with('error', 'The details provided do not match our records.');
}

    public function showResetForm()
    {
        // Security: If they haven't verified their 3 details, kick them back
        if (!session()->has('reset_user_id')) {
            return redirect()->route('login')->with('error', 'Please verify your details first.');
        }

        return view('password.reset_view');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed', // 'confirmed' looks for password_confirmation field
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Update password and clear the temporary session
        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Password updated successfully! Please login.');
    }
}