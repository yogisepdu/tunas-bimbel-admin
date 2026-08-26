<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Throwable;

class CreateTeacher extends Component
{
    public $name = '';
    public $email = '';

    public $password = '';
    public $password_confirmation = '';

    public $phone = '';
    public $company = '';
    public $specialization = '';
    public $experience_years = null;
    public $bio = '';

    public function save()
    {
        /*
        |--------------------------------------------------------------------------
        | Bersihkan input
        |--------------------------------------------------------------------------
        */
        $this->name = trim((string) $this->name);
        $this->email = strtolower(
            trim((string) $this->email)
        );

        $this->phone = trim((string) $this->phone);
        $this->company = trim((string) $this->company);
        $this->specialization = trim(
            (string) $this->specialization
        );

        $this->bio = trim((string) $this->bio);

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
            ],
            'company' => [
                'nullable',
                'string',
                'max:255',
            ],
            'specialization' => [
                'required',
                'string',
                'max:255',
            ],
            'experience_years' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            'bio' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'name.required' =>
            'Nama teacher wajib diisi.',

            'email.required' =>
            'Email teacher wajib diisi.',

            'email.email' =>
            'Format email tidak valid.',

            'email.unique' =>
            'Email tersebut sudah digunakan.',

            'password.required' =>
            'Password wajib diisi.',

            'password.min' =>
            'Password minimal 6 karakter.',

            'password.confirmed' =>
            'Konfirmasi password tidak sama.',

            'phone.required' =>
            'Nomor telepon wajib diisi.',

            'specialization.required' =>
            'Spesialisasi teacher wajib diisi.',

            'experience_years.integer' =>
            'Pengalaman kerja harus berupa angka.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                /*
                |--------------------------------------------------------------------------
                | Simpan akun utama ke tabel users
                |--------------------------------------------------------------------------
                */
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make(
                        $validated['password']
                    ),
                    'role' => 'teacher',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Teacher dibuat oleh admin sehingga langsung diverifikasi
                |--------------------------------------------------------------------------
                */
                $user->email_verified_at = now();
                $user->save();

                /*
                |--------------------------------------------------------------------------
                | Simpan data tambahan ke tabel teachers
                |--------------------------------------------------------------------------
                */
                Teacher::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'company' =>
                    $validated['company'] ?: null,
                    'specialization' =>
                    $validated['specialization'],
                    'experience_years' =>
                    $validated['experience_years'] ?: null,
                    'bio' => $validated['bio'] ?: null,
                ]);
            });

            session()->flash(
                'success',
                'Akun teacher berhasil dibuat.'
            );

            return $this->redirect(
                route('teacher.index'),
                navigate: true
            );
        } catch (Throwable $exception) {
            report($exception);

            session()->flash(
                'error',
                'Akun teacher gagal dibuat. Silakan coba kembali.'
            );

            return null;
        }
    }

    public function render()
    {
        return view(
            'livewire.user.form.create-teacher'
        )->layout('layouts.admin');
    }
}
