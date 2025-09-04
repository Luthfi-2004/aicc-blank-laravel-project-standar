<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $logout_others = false;

    protected function rules(): array
    {
        return [
            // validasi bawaan Laravel untuk cek password saat ini
            'current_password'        => ['required', 'current_password'],
            // minimal 8, wajib konfirmasi, dan tidak boleh sama dengan current
            'password'                => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'password_confirmation'   => ['required'],
        ];
    }

    protected $messages = [
        'current_password.required' => 'Password saat ini wajib diisi.',
        'current_password.current_password' => 'Password saat ini tidak sesuai.',
        'password.required'         => 'Password baru wajib diisi.',
        'password.min'              => 'Password baru minimal 8 karakter.',
        'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        'password.different'        => 'Password baru tidak boleh sama dengan password saat ini.',
        'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
    ];

    #[Layout('layouts.app')] // Ganti jika layout utama kamu beda (mis. 'layouts.master')
    #[Title('Change Password')]
    public function render()
    {
        return view('livewire.auth.change-password');
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        // Opsional: logout all other devices/sessions
        if ($this->logout_others) {
            // butuh plain current password
            Auth::logoutOtherDevices($this->current_password);
        }

        // Update password
        $user->password = Hash::make($this->password);
        $user->save();

        // Bersihkan input form
        $this->reset(['current_password', 'password', 'password_confirmation', 'logout_others']);

        // Feedback sukses
        session()->flash('success', 'Password berhasil diubah.');

        // (Opsional) dispatch event untuk memicu toast JS
        // $this->dispatch('password-changed');
    }
}
