<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class TeacherEdit extends Component
{
    public $userId;
    public $teacherId;
    public $role;

    public $name = '';
    public $email = '';

    public $phone = '';
    public $company = '';
    public $specialization = '';
    public $experience_years = null;
    public $bio = '';

    public $password = '';
    public $password_confirmation = '';

    private function ensureAdministrator(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->role === 'administrator',
            403
        );
    }

    /**
     * Parameter $userId menggunakan users.id.
     */
    public function mount($userId): void
    {
        $this->ensureAdministrator();

        $user = User::query()
            ->whereIn('role', [
                'admin',
                'teacher',
            ])
            ->with('teacher')
            ->findOrFail($userId);

        $this->userId = $user->id;
        $this->teacherId = $user->teacher?->id;
        $this->role = $user->role;

        $this->name = $user->name;
        $this->email = $user->email;

        if ($user->role === 'teacher') {
            $this->phone = $user->teacher?->phone ?? '';
            $this->company = $user->teacher?->company ?? '';
            $this->specialization =
                $user->teacher?->specialization ?? '';
            $this->experience_years =
                $user->teacher?->experience_years;
            $this->bio = $user->teacher?->bio ?? '';
        }
    }

    public function update()
    {
        $this->ensureAdministrator();

        /*
         * Role diambil kembali dari database.
         * Pengguna tidak dapat mengubah role melalui browser.
         */
        $user = User::query()
            ->whereIn('role', [
                'admin',
                'teacher',
            ])
            ->with('teacher')
            ->findOrFail($this->userId);

        $this->role = $user->role;

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
                Rule::unique('users', 'email')
                    ->ignore($this->userId),
            ],
            'phone' => [
                $user->role === 'teacher'
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
                $user->role === 'teacher'
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
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'name.required' =>
            'Nama lengkap wajib diisi.',

            'email.required' =>
            'Email wajib diisi.',

            'email.email' =>
            'Format email tidak valid.',

            'email.unique' =>
            'Email tersebut sudah digunakan.',

            'phone.required' =>
            'Nomor telepon teacher wajib diisi.',

            'specialization.required' =>
            'Spesialisasi teacher wajib diisi.',

            'password.min' =>
            'Password minimal 6 karakter.',

            'password.confirmed' =>
            'Konfirmasi password tidak sama.',
        ]);

        try {
            DB::transaction(
                function () use ($user, $validated) {
                    $userData = [
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                    ];

                    if (!empty($validated['password'])) {
                        $userData['password'] = Hash::make(
                            $validated['password']
                        );
                    }

                    /*
                     * Role tidak diperbarui karena harus tetap
                     * mengikuti role yang tersimpan di database.
                     */
                    $user->update($userData);

                    /*
                     * Profil teacher hanya dibuat atau diperbarui
                     * untuk user dengan role teacher.
                     */
                    if ($user->role === 'teacher') {
                        $teacher = Teacher::updateOrCreate(
                            [
                                'user_id' => $user->id,
                            ],
                            [
                                'phone' =>
                                $validated['phone'],
                                'company' =>
                                $validated['company'] ?: null,
                                'specialization' =>
                                $validated['specialization'],
                                'experience_years' =>
                                $validated['experience_years'] ?? null,
                                'bio' =>
                                $validated['bio'] ?: null,
                            ]
                        );

                        $this->teacherId = $teacher->id;
                    }
                }
            );

            $this->reset([
                'password',
                'password_confirmation',
            ]);

            $accountName = $user->role === 'teacher'
                ? 'Teacher'
                : 'Admin';

            session()->flash(
                'success',
                "Akun {$accountName} berhasil diperbarui."
            );

            return $this->redirect(
                route('teacher.index'),
                navigate: true
            );
        } catch (Throwable $exception) {
            report($exception);

            session()->flash(
                'error',
                'Akun gagal diperbarui. Silakan coba kembali.'
            );

            return null;
        }
    }

    public function render()
    {
        $this->ensureAdministrator();

        return view(
            'livewire.user.form.teacher-edit'
        )->layout('layouts.admin', [
            'title' => $this->role === 'teacher'
                ? 'Edit Teacher'
                : 'Edit Admin',
        ]);
    }
}
