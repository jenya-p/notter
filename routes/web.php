<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Profile\InfoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home']);
Route::get('/dashboard', function(){
    return redirect()->route('profile-test.index');
})->name('dashboard');
Route::get('/{slug}', [\App\Http\Controllers\HomeController::class, 'content'])
    ->whereIn('slug', ['about', 'agreement']);

Route::get('/prices', [\App\Http\Controllers\HomeController::class, 'prices']);


Route::middleware('auth')->group(function () {
    Route::get('profile/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('profile/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('profile/test', [\App\Http\Controllers\Profile\TestsController::class, 'index'])->name('profile-test.index');
    Route::get('profile/finance', [\App\Http\Controllers\Profile\FinanceController::class, 'index'])->name('profile-finance.index');

    Route::get('/profile', [InfoController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InfoController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [InfoController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tests', [\App\Http\Controllers\HomeController::class, 'tests'])->name('tests');
    Route::get('/test', [\App\Http\Controllers\HomeController::class, 'test'])->name('test');

});

require __DIR__.'/auth.php';

