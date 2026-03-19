<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\DinnerController;
use App\Http\Controllers\SponsorController;
use App\Models\SponsorCode;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('index'); });
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/race', function () { return view('race'); });
Route::get('/term', function () { return view('term'); });
Route::get('/pp', function () { return view('pp'); });
Route::get('/race_guide', function () { return view('race_guide'); });
Route::get('/blog', [StoryController::class, 'index'])->name('blog.index');
Route::get('/event', [EventController::class, 'showPublicEvents'])->name('public.events');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/score', function () {
    $pc = "000001";
    $rid = "104742";
    $token = "fe95a4e0d6c442129469f65e9c7a3ff9";
    $base = "https://rqs.racetigertiming.com/Dif";
    $headers = ['User-Agent' => 'Mozilla/5.0'];

    try {
        // 1. Get Event Config (Static, only one call needed)
        $configReq = Http::withoutVerifying()->withHeaders($headers)->post("$base/info?pc=$pc&rid=$rid&token=$token");
        $raceData = $configReq->json()['data'] ?? [];

        // 2. Get ALL Athletes (Loop through pages)
        $athletes = [];
        $page = 1;
        do {
            $res = Http::withoutVerifying()->withHeaders($headers)->post("$base/bio?pc=$pc&rid=$rid&token=$token&page=$page");
            $data = $res->json()['data'] ?? [];
            $athletes = array_merge($athletes, $data);
            $page++;
        } while (count($data) >= 50); // If we got 50, there's likely another page

        // 3. Get ALL Scores (Loop through pages)
        $scores = [];
        $page = 1;
        do {
            $res = Http::withoutVerifying()->withHeaders($headers)->post("$base/score?pc=$pc&rid=$rid&token=$token&page=$page");
            $data = $res->json()['data'] ?? [];
            $scores = array_merge($scores, $data);
            $page++;
        } while (count($data) >= 50);

        return view('score', compact('raceData', 'athletes', 'scores'));

    } catch (\Exception $e) {
        return "Connection Error: " . $e->getMessage();
    }
});


Route::get('/staff/scanner', function () {
    return view('staff.scanner');
})->name('staff.scanner');
/*
|--------------------------------------------------------------------------
| Dinner Registration Flow (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('dinner')->name('dinner.')->group(function () {
    // 1. List all available dinners
    Route::get('/', [DinnerController::class, 'index'])->name('index');
    
    // 2. Select Ticket Type (Standard/VIP) for a specific dinner
    Route::get('/tickets/{id}', [DinnerController::class, 'selectTickets'])->name('tickets');
    
    // 3. Form to enter Guest Name, Email, Phone
    Route::get('/register', [DinnerController::class, 'registerPage'])->name('register');
    
    // 4. Temporary store and redirect to checkout
    Route::post('/register/store', [DinnerController::class, 'storeRegistration'])->name('register.store');
    
    // 5. Review page
    Route::get('/checkout', [DinnerController::class, 'checkoutPage'])->name('checkout');
    
    // 6. Final save to database
    Route::post('/process', [DinnerController::class, 'process'])->name('process');
    
    // 7. Success page
    Route::get('/confirmation/{id}', [DinnerController::class, 'confirmation'])->name('confirmation');
    
    // 8. Handle payment slip upload
    Route::post('/upload-payment/{id}', [DinnerController::class, 'uploadPayment'])->name('upload.payment');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/login', [RegisterController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegisterController::class, 'login'])->name('login.submit');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'You have been logged out!');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Athlete Registration)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/ticket', [TicketController::class, 'showTicket'])->name('ticket');
    Route::post('/select-race', [AthleteController::class, 'handleSelection'])->name('athlete.selection.handle');
    Route::post('/payment/initiate/{id}', [TicketController::class, 'initiatePayment'])->name('initiatePayment');
    Route::get('/register-athlete', [AthleteController::class, 'showAthleteForm'])->name('athlete.register');
    Route::post('/register-athlete', [AthleteController::class, 'submit'])->name('athlete.register.submit');
    Route::get('/checkout/review', [TicketController::class, 'showReviewPage'])->name('checkout.review');
    Route::post('/checkout/process', [TicketController::class, 'processPayment'])->name('tickets.process-payment');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ticket/download/{id}', [TicketController::class, 'downloadPDF'])->name('ticket.download');
    Route::get('/ticket/preview/{id}', [TicketController::class, 'previewPDF'])->name('ticket.preview');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin'])->prefix('dashboard')->group(function () {
    Route::get('/register/1', [UserController::class, 'dashboard'])->name('dashboard.register-level-1');
    Route::get('/register/2', [AthleteController::class, 'dashboard'])->name('dashboard.register-level-2');
    Route::get('/events/ticket', [TicketController::class, 'dashboard'])->name('dashboard.events.ticket');

    Route::get('/events/{status}', [EventController::class, 'index'])
          ->where('status', 'now|past|coming')
          ->name('events.index');

    Route::resource('events', EventController::class)->except(['index', 'show']);
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    // Dinner Management
    Route::get('/dinner-management/{timeframe}', [DinnerController::class, 'manageDinners'])->name('admin.dinner.manage');
    Route::get('/dinner/create', [DinnerController::class, 'create'])->name('admin.dinner.create');
    Route::post('/dinner/store', [DinnerController::class, 'store'])->name('admin.dinner.store');
    Route::get('/dinner/{id}/edit', [DinnerController::class, 'edit'])->name('admin.dinner.edit');
    Route::put('/dinner/update/{id}', [DinnerController::class, 'update'])->name('admin.dinner.update');
    Route::delete('/dinner/{id}', [DinnerController::class, 'destroy'])->name('admin.dinner.destroy');
    Route::post('/dinner/{id}/toggle-scan', [App\Http\Controllers\DinnerController::class, 'toggleScanning'])
    ->name('admin.dinner.toggle-scan');
    
    // Dinner Tickets (Master/Detail)
    Route::get('/dinner-tickets', [DinnerController::class, 'dinnerTicketsIndex'])->name('admin.dinner.tickets.index');
    Route::get('/dinner-tickets/{id}', [DinnerController::class, 'showDinnerTickets'])->name('admin.dinner.tickets.show');
    Route::post('/dinner-tickets/{id}/approve', [DinnerController::class, 'adminApprove'])->name('admin.dinner.approve');
    Route::post('/dinner-tickets/reject/{id}', [DinnerController::class, 'adminReject'])->name('admin.dinner.reject');

    Route::get('/sponsors/{status}', [SponsorController::class, 'index'])
    ->where('status', 'now|past') // Only allow these two words
    ->name('admin.sponsor.index');
    Route::get('/sponsors/create', [SponsorController::class, 'create'])->name('admin.sponsor.create');
    Route::post('/sponsors/store', [SponsorController::class, 'store'])->name('admin.sponsor.store');
    Route::get('/sponsors/details/{id}', [SponsorController::class, 'show'])->name('admin.sponsor.show');
    Route::post('/sponsors/toggle/{id}', [SponsorController::class, 'toggleStatus'])->name('admin.sponsor.toggle');
    Route::get('/sponsor/{id}/batch-print', [SponsorController::class, 'batchPrint'])->name('admin.sponsor.batchPrint');
}); 

Route::post('/api/verify-ticket', [DinnerController::class, 'publicVerify'])->name('dinner.verify');

Route::get('/api/validate-discount', function (Illuminate\Http\Request $request) {
    // 1. Find the code in the new table
    $codeRecord = \App\Models\SponsorCode::where('code', $request->code)->first();

    if (!$codeRecord) {
        return response()->json(['success' => false, 'message' => 'Invalid sponsor code.']);
    }

    // 2. NEW LOGIC: Check if quota is full instead of is_used
    if ($codeRecord->used_count >= $codeRecord->max_uses) {
        return response()->json(['success' => false, 'message' => 'This code has reached its guest limit.']);
    }

    // 3. Optional: Check if the sponsor is active
    if ($codeRecord->sponsor->status !== 'active') {
        return response()->json(['success' => false, 'message' => 'This sponsor is currently inactive.']);
    }

    // 4. Return the discount from the SponsorCode record
    return response()->json([
        'success' => true,
        'discount' => $codeRecord->discount, // This is the % off
        'message' => 'Code applied!'
    ]);
});

/*
|--------------------------------------------------------------------------
| External Callbacks
|--------------------------------------------------------------------------
*/
Route::post('/payment/kbz/callback', [TicketController::class, 'kbzCallback'])->name('kbz.callback');
Route::post('/test-kbz-package', [TicketController::class, 'testPackageCallback']);
Route::get('/payment/status/{id}', [TicketController::class, 'checkStatus']);