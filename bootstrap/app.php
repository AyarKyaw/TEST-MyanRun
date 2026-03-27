<?php

// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Add this import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Your existing KBZPay fix
        $middleware->validateCsrfTokens(except: [
            '/test-kbz-package',
            'payment/kbz/callback', 
        ]);

        // 2. Middleware Alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // 3. FIX: Redirect unauthenticated Agents to /agent/login
        $middleware->redirectGuestsTo(function (Request $request) {
            // If the user is trying to access an agent route
            if ($request->is('agent') || $request->is('agent/*')) {
                return route('agent.login'); 
            }
            
            // Default for Admins/Others
            return route('login'); 
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();