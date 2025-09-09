<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\Auth\Login as LoginPage;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\GreenSand\Greensand;
use App\Http\Livewire\JshGreenSand\JshGreenSand;
use App\Http\Controllers\GreensandExportController;
use App\Http\Livewire\Auth\ChangePassword;

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
});

// Auth
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    // Greensand
    Route::prefix('greensand')->group(function () {
        Route::get('/', Greensand::class)->name('greensand.index');
        Route::get('/export', [GreensandExportController::class, 'download'])->name('greensand.export');
    });

    // JSH Greensand
    Route::get('/jsh-green-sand', JshGreenSand::class)->name('jsh-green-sand.index');

    // Change Password
    Route::get('/change-password', ChangePassword::class)->name('password.change');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
