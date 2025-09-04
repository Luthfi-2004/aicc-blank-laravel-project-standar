<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\Auth\Login as LoginPage;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\GreenSand\Greensand;
use App\Http\Livewire\JshGreenSand\JshGreenSand;
use App\Http\Controllers\GreensandExportController;
use App\Http\Livewire\Auth\ChangePassword;

/*
|--------------------------------------------------------------------------
| Guest routes (hanya bisa diakses ketika BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes (harus login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard sebagai halaman utama setelah login
    Route::get('/', Dashboard::class)->name('dashboard');

    // Green Sand
    Route::prefix('greensand')->group(function () {
        Route::get('/', Greensand::class)->name('greensand.index');
        Route::get('/export', [GreensandExportController::class, 'download'])->name('greensand.export');
    });

    // JSH Green Sand
    Route::get('/jsh-green-sand', JshGreenSand::class)->name('jsh-green-sand.index');

    // Change Password
    Route::get('/change-password', ChangePassword::class)->name('password.change');

    // Logout (POST agar aman, gunakan form dengan @csrf)
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
