<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController
{
    // 🔹 TAMPILKAN FORM
    public function showForm(Request $request)
    {
        $email = urldecode($request->query('email'));
        $token = $request->query('token');

        if (!$email || !$token) {
            abort(404);
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    // 🔹 PROSES RESET
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('password.success');
        }

        return back()->withErrors([
            'email' => __($status),
        ])->withInput();
    }
}