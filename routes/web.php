<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Sales;
use App\Livewire\SalesMonth;
use App\Livewire\SalesDay;
use App\Livewire\Member;
use App\Livewire\Activity;
use App\Livewire\Contribution;
use Illuminate\Support\Facades\Auth;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Sales Routes
    Route::get('/sales', Sales::class)->name('sales');
    Route::get('/sales/{month}', SalesMonth::class)->name('sales.month');
    Route::get('/sales/{month}/{day}', SalesDay::class)->name('sales.day');

    // Member, Activity, Contribution Routes
    Route::get('/member', Member::class)->name('member');
    Route::get('/activity', Activity::class)->name('activity');
    Route::get('/contribution/{period?}', Contribution::class)->name('contribution');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
