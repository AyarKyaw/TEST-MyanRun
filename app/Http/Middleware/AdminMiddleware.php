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
        // 1. Check if the user is logged in via the 'admin' guard
        if (Auth::guard('admin')->check()) {
            
            $user = Auth::guard('admin')->user();

            // 2. Allow access if they are Super Admin, Event Admin, OR Finance Admin
            // Added the finance_admin check here
            if ($user->role === 'super_admin' || 
                $user->role === 'event_admin' || 
                $user->role === 'finance_admin' ||
                $user->role === 'supporter') {
                return $next($request);
            }
        }

        // 3. If they aren't logged in OR don't have a valid role, kick them out
        return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
    }
}