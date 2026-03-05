<?php

use App\Http\Controllers\PaymentController;

Route::post('/kbz/notify', [PaymentController::class, 'handleKbzCallback']);