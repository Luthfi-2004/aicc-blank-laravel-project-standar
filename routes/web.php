<?php

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\GreenSand\Greensand;
use App\Http\Controllers\GreensandExportController;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/login', Login::class)->name('login');
Route::get('/greensand', Greensand::class)->name('greensand.index');
Route::get('/greensand/export', [GreensandExportController::class, 'download'])->name('greensand.export');
