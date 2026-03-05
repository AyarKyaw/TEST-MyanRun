<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\DinnerController;

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
Route::get('/blog', [StoryController::class, 'index'])->name('blog.index');
Route::get('/event', [EventController::class, 'showPublicEvents'])->name('public.events');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');

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
    Route::delete('/dinner/{id}', [DinnerController::class, 'destroy'])->name('admin.dinner.destroy');
    
    // Dinner Tickets (Master/Detail)
    Route::get('/dinner-tickets', [DinnerController::class, 'dinnerTicketsIndex'])->name('admin.dinner.tickets.index');
    Route::get('/dinner-tickets/{id}', [DinnerController::class, 'showDinnerTickets'])->name('admin.dinner.tickets.show');
    Route::post('/dinner-tickets/{id}/approve', [DinnerController::class, 'adminApprove'])->name('admin.dinner.approve');
});

/*
|--------------------------------------------------------------------------
| External Callbacks
|--------------------------------------------------------------------------
*/
Route::post('/payment/kbz/callback', [TicketController::class, 'kbzCallback'])->name('kbz.callback');
Route::post('/test-kbz-package', [TicketController::class, 'testPackageCallback']);
Route::get('/payment/status/{id}', [TicketController::class, 'checkStatus']);