<?php

namespace App\Http\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    #[Layout('layouts.auth.app')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
