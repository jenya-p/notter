<?php

use Illuminate\Support\Facades\Route;

Route::any('/yookassa', [\App\Http\Controllers\Profile\PaymentController::class, 'yookassaWebHook'])
    ->name('yookassaWebHook');
