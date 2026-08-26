<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\ProfileSiswa;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ======================
    // 🔐 LOGIN
    // ======================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 🔥 CEK USER DULU (LEBIH AMAN DARI Auth::attempt)
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // 🔥 CEK VERIFIKASI EMAIL
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email belum diverifikasi'
            ], 403);
        }

        // 🔥 CEK ROLE
        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya student yang dapat login.'
            ], 403);
        }

        // 🔥 GENERATE TOKEN
        $token = $user->createToken('mobile-token', ['student'])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    // ======================
    // 📝 REGISTER
    // ======================
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = DB::transaction(function () use ($validated) {
            /*
        |--------------------------------------------------------------------------
        | Buat akun utama
        |--------------------------------------------------------------------------
        */
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            /*
        |--------------------------------------------------------------------------
        | Buat data siswa
        |--------------------------------------------------------------------------
        */
            Student::create([
                'user_id' => $user->id,
                'phone' => null,
                'school' => null,
                'grade' => null,
                'address' => null,
                'birth_date' => null,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Buat profil aplikasi Android
        |--------------------------------------------------------------------------
        */
            ProfileSiswa::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => null,
                'gender' => null,
                'province_id' => null,
                'regency_id' => null,
                'district_id' => null,
                'village_id' => null,
            ]);

            return $user;
        });

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Registrasi berhasil, silakan cek email untuk verifikasi.',
        ], 201);
    }

    // ======================
    // 🔁 RESEND VERIFICATION
    // ======================
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email sudah diverifikasi'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Link verifikasi telah dikirim ulang'
        ]);
    }

    // ======================
    // 🔵 GOOGLE LOGIN
    // ======================
    // public function googleLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'name' => 'required|string',
    //     ]);

    //     $user = User::firstOrCreate(
    //         ['email' => $request->email],
    //         [
    //             'name' => $request->name,
    //             'password' => bcrypt('google_login_dummy'),
    //             'role' => 'student',
    //             'email_verified_at' => now(), // 🔥 AUTO VERIFIED
    //         ]
    //     );

    //     // 🔥 GENERATE TOKEN
    //     $token = $user->createToken('mobile-token', ['student'])->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login Google berhasil',
    //         'user' => $user,
    //         'token' => $token
    //     ]);
    // }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Link reset password telah dikirim ke email 📩'
                ]);
            }

            return response()->json([
                'message' => __($status)
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage() // 🔥 INI PENTING BUAT DEBUG
            ], 500);
        }
    }

    // ======================
    // 🚪 LOGOUT
    // ======================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
