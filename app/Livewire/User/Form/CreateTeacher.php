<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class CreateTeacher extends Component
{
    /*
    |--------------------------------------------------------------------------
    | PILIHAN ROLE
    |--------------------------------------------------------------------------
    */

    public $role = 'teacher';

    /*
    |--------------------------------------------------------------------------
    | DATA AKUN
    |--------------------------------------------------------------------------
    */

    public $name = '';
    public $email = '';

    public $password = '';
    public $password_confirmation = '';

    /*
    |--------------------------------------------------------------------------
    | DATA KHUSUS TEACHER
    |--------------------------------------------------------------------------
    */

    public $phone = '';
    public $company = '';
    public $specialization = '';
    public $experience_years = null;
    public $bio = '';

    /**
     * Halaman hanya dapat diakses administrator.
     */
    private function ensureAdministrator(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->role === 'administrator',
            403
        );
    }

    public function mount(): void
    {
        $this->ensureAdministrator();
    }

    /**
     * Menghapus pesan validasi field teacher
     * ketika pilihan role berubah.
     */
    public function updatedRole($value): void
    {
        $this->resetValidation([
            'role',
            'phone',
            'company',
            'specialization',
            'experience_years',
            'bio',
        ]);
    }

    public function save()
    {
        $this->ensureAdministrator();

        /*
        |--------------------------------------------------------------------------
        | BERSIHKAN INPUT
        |--------------------------------------------------------------------------
        */

        $this->role = strtolower(
            trim((string) $this->role)
        );

        $this->name = trim(
            (string) $this->name
        );

        $this->email = strtolower(
            trim((string) $this->email)
        );

        $this->phone = trim(
            (string) $this->phone
        );

        $this->company = trim(
            (string) $this->company
        );

        $this->specialization = trim(
            (string) $this->specialization
        );

        $this->bio = trim(
            (string) $this->bio
        );

        if ($this->experience_years === '') {
            $this->experience_years = null;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        |
        | Jika role teacher:
        | - Phone wajib.
        | - Specialization wajib.
        |
        | Jika role admin:
        | - Field teacher tidak wajib.
        |
        */

        $validated = $this->validate([
            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'teacher',
                ]),
            ],
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
                $this->role === 'teacher'
                    ? 'required'
                    : 'nullable',
                'string',
                'max:30',
            ],
            'company' => [
                'nullable',
                'string',
                'max:255',
            ],
            'specialization' => [
                $this->role === 'teacher'
                    ? 'required'
                    : 'nullable',
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
            'role.required' =>
            'Jenis akun wajib dipilih.',

            'role.in' =>
            'Jenis akun yang dipilih tidak valid.',

            'name.required' =>
            'Nama lengkap wajib diisi.',

            'email.required' =>
            'Email wajib diisi.',

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
            'Nomor telepon teacher wajib diisi.',

            'specialization.required' =>
            'Spesialisasi teacher wajib diisi.',

            'experience_years.integer' =>
            'Pengalaman kerja harus berupa angka.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                /*
                |--------------------------------------------------------------------------
                | SIMPAN KE TABEL USERS
                |--------------------------------------------------------------------------
                */

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make(
                        $validated['password']
                    ),
                    'role' => $validated['role'],
                ]);

                /*
                 * Akun dibuat langsung oleh administrator,
                 * sehingga email langsung diverifikasi.
                 */
                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();

                /*
                |--------------------------------------------------------------------------
                | SIMPAN PROFIL TEACHER
                |--------------------------------------------------------------------------
                |
                | Hanya dijalankan jika role yang dipilih adalah teacher.
                |
                */

                if ($validated['role'] === 'teacher') {
                    Teacher::create([
                        'user_id' => $user->id,
                        'phone' => $validated['phone'],
                        'company' =>
                        $validated['company'] ?: null,
                        'specialization' =>
                        $validated['specialization'],
                        'experience_years' =>
                        $validated['experience_years'] ?? null,
                        'bio' =>
                        $validated['bio'] ?: null,
                    ]);
                }
            });

            $accountName = $validated['role'] === 'teacher'
                ? 'Teacher'
                : 'Admin';

            session()->flash(
                'success',
                "Akun {$accountName} berhasil dibuat."
            );

            return $this->redirect(
                route('teacher.index'),
                navigate: true
            );
        } catch (Throwable $exception) {
            report($exception);

            session()->flash(
                'error',
                'Akun gagal dibuat. Silakan coba kembali.'
            );

            return null;
        }
    }

    public function render()
    {
        $this->ensureAdministrator();

        return view(
            'livewire.user.form.create-teacher'
        )->layout('layouts.admin', [
            'title' => 'Tambah Admin atau Teacher',
        ]);
    }
}
