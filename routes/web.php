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
    Route::get('/test/{test}/summary', [\App\Http\Controllers\TestsController::class, 'summary'])->name('test.summary');
    Route::get('/test/{test}/{ticketIndex?}/{questionIndex?}', [\App\Http\Controllers\TestsController::class, 'test'])->name('test');
    Route::post('/test/{test}/{variant}', [\App\Http\Controllers\TestsController::class, 'answer'])->name('answer.store');
    Route::delete('/test/{test}/{question}', [\App\Http\Controllers\TestsController::class, 'answerDelete'])->name('answer.delete');


    Route::get('/purchase', [\App\Http\Controllers\PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase', [\App\Http\Controllers\PurchaseController::class, 'store'])->name('purchase.store');

});
Route::get('/admin', function(){
    return redirect()->route('admin.user.index');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {


    Route::resource('user', \App\Http\Controllers\Admin\UserController::class)
        ->except(['destroy']);
    Route::resource('content', \App\Http\Controllers\Admin\ContentController::class)
        ->only(['index', 'edit', 'update']);

});

require __DIR__.'/auth.php';

