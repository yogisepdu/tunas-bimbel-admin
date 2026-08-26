<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $credentials = $this->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($credentials)) {
            $this->password = '';

            $this->addError(
                'email',
                'Email atau password salah.'
            );

            return null;
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Hanya admin dan teacher yang boleh masuk
        |--------------------------------------------------------------------------
        */
        if (
            !$user ||
            !in_array(
                $user->role,
                [
                    'administrator',
                    'admin',
                    'teacher',
                ],
                true
            )
        ) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            $this->password = '';

            $this->addError(
                'email',
                'Akun ini tidak memiliki akses ke panel.'
            );

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Mencegah session fixation
        |--------------------------------------------------------------------------
        */
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
