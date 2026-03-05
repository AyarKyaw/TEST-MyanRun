<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Define allowed admin emails
        $admins = [
            'your-email@example.com', 
            'admin@gmail.com'
        ];

        // 2. Check the 'admin' guard specifically
        // This ensures the person logged in via /admin/login is allowed
        if (Auth::guard('admin')->check() && in_array(Auth::guard('admin')->user()->email, $admins)) {
            return $next($request);
        }

        // 3. If not an admin, send them to the admin login page
        return redirect()->route('admin.login')->with('error', 'Please login with admin credentials.');
    }
}