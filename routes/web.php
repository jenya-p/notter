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

Route::get('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'create'])->name('backfeed.create');
Route::post('/backfeed', [\App\Http\Controllers\BackfeedController::class, 'store'])->name('backfeed.store');


Route::middleware('auth')->group(function () {
    Route::get('profile/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('profile/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('profile/test', [\App\Http\Controllers\Profile\TestsController::class, 'index'])->name('profile-test.index');
    Route::get('profile/finance', [\App\Http\Controllers\Profile\FinanceController::class, 'index'])->name('profile-finance.index');

    Route::get('/profile', [InfoController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InfoController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [InfoController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tests', [\App\Http\Controllers\HomeController::class, 'tests'])->name('tests');
    Route::get('/test/{ticket}/complete', [\App\Http\Controllers\TestsController::class, 'complete'])->name('test.complete');
    Route::get('/test/{test}/{ticketIndex?}/{questionIndex?}', [\App\Http\Controllers\TestsController::class, 'test'])->name('test');
    Route::post('/test/{question}', [\App\Http\Controllers\TestsController::class, 'answer'])->name('test.answer');
    Route::delete('/test/{question}', [\App\Http\Controllers\TestsController::class, 'skip'])->name('test.skip');


    Route::get('/purchase', [\App\Http\Controllers\PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase', [\App\Http\Controllers\PurchaseController::class, 'store'])->name('purchase.store');

    Route::resource('attachment', \App\Http\Controllers\AttachmentController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/attachment/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachment.download');
    Route::get('/attachment/{attachment}/thumb', [\App\Http\Controllers\AttachmentController::class, 'thumb'])->name('attachment.thumb');

});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'can:admin']], function () {

    Route::any('/', function(){
        return redirect()->route('admin.user.index');
    })->name('dashboard');

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class)
        ->except(['destroy', 'show']);
    Route::resource('content', \App\Http\Controllers\Admin\ContentController::class)
        ->only(['index', 'edit', 'update']);

    Route::resource('block', \App\Http\Controllers\Admin\BlockController::class)
        ->except(['show']);

    Route::post('question/order', [\App\Http\Controllers\Admin\QuestionController::class, 'order'])
        ->name('question.order');
    Route::resource('question', \App\Http\Controllers\Admin\QuestionController::class)
        ->except(['show', 'index']);

    Route::resource('backfeed', \App\Http\Controllers\Admin\BackfeedController::class)
        ->except(['show']);

});


Route::get('tst.html', function(){

    $msg = (new \Illuminate\Notifications\Messages\MailMessage())
        ->subject(Lang::get('Reset Password Notification'))
        ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
        ->action(Lang::get('Reset Password'), 'asdasdasd')
        ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
        ->line(Lang::get('If you did not request a password reset, no further action is required.'));

    echo $msg->render(); die;

});

require __DIR__.'/auth.php';

