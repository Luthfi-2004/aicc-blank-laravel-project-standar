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

<<<<<<< HEAD
Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/login', Login::class)->name('login');
Route::get('/greensand', Greensand::class)->name('greensand.index');
Route::get('/greensand/export', [GreensandExportController::class, 'download'])->name('greensand.export');
Route::get('/jsh-green-sand', JshGreenSand::class)->name('jsh-green-sand.index');
=======
/*
|--------------------------------------------------------------------------
| Authenticated routes (harus login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard sebagai halaman utama setelah login
    Route::get('/', Dashboard::class)->name('dashboard');

    // Green Sand
    Route::get('/greensand', Greensand::class)->name('greensand.index');
    Route::get('/greensand/export', [GreensandExportController::class, 'download'])->name('greensand.export');
    Route::get('/change-password', ChangePassword::class)->name('password.change');
    // Logout (POST agar aman, gunakan form dengan @csrf)
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
>>>>>>> ec161f383fefd325abdcd6ac55e7e374c6adbafc
