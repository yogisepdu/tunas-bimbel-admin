<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileSiswa;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        if (
            !$user ||
            !Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email belum diverifikasi.',
            ], 403);
        }

        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya student yang dapat login.',
            ], 403);
        }

        $token = $user->createToken(
            'mobile-token',
            ['student']
        )->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

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

            // Membuat akun utama.
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            // Membuat data siswa.
            Student::create([
                'user_id' => $user->id,
                'phone' => null,
                'school' => null,
                'grade' => null,
                'address' => null,
                'birth_date' => null,
            ]);

            // Membuat profil aplikasi Android.
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

        // Mengirim email verifikasi.
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Registrasi berhasil, silakan cek email untuk verifikasi.',
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | RESEND EMAIL VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function resendVerification(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan.',
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email sudah diverifikasi.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Link verifikasi telah dikirim ulang.',
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(Request $request)
    {
        /*
         * Validasi dilakukan sebelum try-catch.
         * Email harus terdaftar di tabel users.
         */

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
        ]);

        try {

            /*
             * Meminta Laravel Password Broker
             * mengirimkan link reset password.
             */

            $status = Password::sendResetLink([
                'email' => $validated['email'],
            ]);


            /*
             * Link reset password berhasil dikirim.
             */

            if ($status === Password::RESET_LINK_SENT) {

                return response()->json([
                    'message' => 'Link reset password telah dikirim ke email.',
                ], 200);
            }


            /*
             * Permintaan terlalu sering dilakukan.
             */

            if ($status === Password::RESET_THROTTLED) {

                Log::warning(
                    'Forgot Password: permintaan terkena rate limit.',
                    [
                        'status' => $status,
                    ]
                );

                return response()->json([
                    'message' => 'Terlalu banyak permintaan. Silakan tunggu sebelum mencoba kembali.',
                ], 429);
            }


            /*
             * Pengguna tidak ditemukan oleh Password Broker.
             */

            if ($status === Password::INVALID_USER) {

                return response()->json([
                    'message' => 'Email tidak ditemukan.',
                ], 404);
            }


            /*
             * Menangani status lain dari Password Broker.
             */

            Log::warning(
                'Forgot Password: Password Broker mengembalikan status gagal.',
                [
                    'status' => $status,
                ]
            );

            return response()->json([
                'message' => __($status),
            ], 400);
        } catch (\Throwable $e) {

            /*
             * Mencatat exception sebenarnya ke laravel.log.
             * Detail exception tidak dikirim ke aplikasi Android.
             */

            Log::error(
                'Forgot Password Exception.',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'message' => 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ], 200);
    }
}
