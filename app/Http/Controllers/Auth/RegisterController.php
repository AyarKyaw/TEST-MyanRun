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
        $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'phone'       => ['required', 'string', 'regex:/^[0-9]{7,9}$/'],
            'password'    => 'required|string|min:8|confirmed',
        ]);

        // 1. Get the last runner ID using a more reliable sort
        // We sort by length first, then the string, to ensure RUN-1000 comes after RUN-999
        $lastUser = User::where('runner_id', 'LIKE', 'RUN-%')
            ->orderByRaw('LENGTH(runner_id) DESC')
            ->orderBy('runner_id', 'desc')
            ->first();

        if ($lastUser) {
            // 2. Use explode or preg_replace to get JUST the number part safely
            $lastNumber = (int) str_replace('RUN-', '', $lastUser->runner_id);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Or 1, depending on where you want to start
        }
        // 1. Get the absolute maximum number from the runner_id column
        $maxRunnerId = User::where('runner_id', 'LIKE', 'RUN-%')
            ->selectRaw("MAX(CAST(REPLACE(runner_id, 'RUN-', '') AS UNSIGNED)) as max_id")
            ->first()
            ->max_id;

        // 2. Increment that number
        $nextNumber = $maxRunnerId ? ($maxRunnerId + 1) : 1000;

        // 3. Format it with your 7-digit padding
        $runnerId = 'RUN-' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        $user = User::create([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'phone'       => '+959' . ltrim($request->phone, '0'),
            'password'    => Hash::make($request->password),
            'runner_id'   => $runnerId, 
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