<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'username' => 'required|string|min:3',
        'password' => 'required|string|min:6',
    ];

    #[Layout('layouts.auth.app')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function mount()
    {
        // Jika sudah login, lempar ke dashboard agar tidak bisa buka halaman login lagi
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }

    public function login()
    {
        $this->validate();

        // Throttle (5 attempts / minute per username+IP)
        $key = Str::lower($this->username).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts("login:{$key}", 5)) {
            $seconds = RateLimiter::availableIn("login:{$key}");
            $this->addError('username', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
            return;
        }

        // Attempt login pakai kolom 'username'
        $ok = Auth::attempt(
            ['username' => $this->username, 'password' => $this->password],
            $this->remember
        );

        if (! $ok) {
            RateLimiter::hit("login:{$key}", 60);
            $this->addError('username', 'Username atau password salah.');
            return;
        }

        // Reset throttle & amankan sesi
        RateLimiter::clear("login:{$key}");
        session()->regenerate();

        // Redirect ke halaman yang diinginkan sebelumnya / dashboard
        return redirect()->intended(route('dashboard'));
    }
}
