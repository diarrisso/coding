<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\TeaserController;
use App\Livewire\TeaserDelete;
use App\Livewire\TeaserForm;
use App\Livewire\TeaserList;
use App\Livewire\Teasers\Index;
use App\Livewire\TeaserShow;
use App\Livewire\TeaserUpdate;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');

    Route::get('/teasers', TeaserList::class)->name('teasers.index');
    Route::get('/teasers/create',TeaserForm::class)->name('teasers.create');
    Route::get('/teasers/{teaser:slug}', TeaserShow::class)->name('teasers.show');
    Route::get('/teasers/{teaser}/edit',TeaserUpdate::class)->name('teasers.edit');
    Route::delete('/teasers/{teaser}', [TeaserController::class, 'destroy'])->name('teasers.destroy');
    Route::get('/', Index::class)->name('teasers.home');
});

Route::post('logout', App\Livewire\Actions\Logout::class)->name('logout');
