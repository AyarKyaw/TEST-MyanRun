<?php

use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Schedule::call(function () {
    // 1. Temporarily target pending tickets older than 10 seconds for testing
    $expiredTicketsQuery = \App\Models\Ticket::where('status', 'pending')
        ->where('created_at', '<', Carbon::now()->subSeconds(10));

    $count = $expiredTicketsQuery->count();

    if ($count > 0) {
        // 2. Clear them out (use forceDelete() if your model uses SoftDeletes)
        $expiredTicketsQuery->delete(); 
        
        Log::info("TEST Cleanup: Purged {$count} pending tickets older than 10 seconds.");
    }
})->everyMinute();