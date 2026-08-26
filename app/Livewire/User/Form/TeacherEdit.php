<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TeacherEdit extends Component
{
    public $userId;
    public $teacherId;

    public $name;
    public $email;

    public $phone;
    public $company;
    public $specialization;
    public $experience_years;
    public $bio;

    public $password;
    public $password_confirmation;

    /**
     * Parameter $userId berasal dari route:
     * /teacher/{userId}/edit
     */
    public function mount($userId)
    {
        /*
         * Pencarian dimulai dari tabel users karena ID
         * yang dikirim melalui route adalah users.id.
         *
         * Relasi teacher boleh null untuk akun lama.
         */
        $user = User::query()
            ->where('role', 'teacher')
            ->with('teacher')
            ->findOrFail($userId);

        $this->userId = $user->id;
        $this->teacherId = $user->teacher?->id;

        $this->name = $user->name;
        $this->email = $user->email;

        /*
         * Operator null-safe "?->" mencegah error
         * jika data teacher belum tersedia.
         */
        $this->phone = $user->teacher?->phone;
        $this->company = $user->teacher?->company;
        $this->specialization = $user->teacher?->specialization;
        $this->experience_years = $user->teacher?->experience_years;
        $this->bio = $user->teacher?->bio;
    }

    public function update()
    {
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
            ],
            'password' => [
                'nullable',
                'confirmed',
                'min:6',
            ],
        ]);

        $user = User::query()
            ->where('role', 'teacher')
            ->findOrFail($this->userId);

        DB::transaction(function () use ($user, $validated) {
            /*
             * Data yang diperbarui pada tabel users.
             */
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'teacher',
            ];

            /*
             * Password hanya diperbarui jika diisi.
             */
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make(
                    $validated['password']
                );
            }

            $user->update($userData);

            /*
             * Jika data teacher sudah ada: update.
             * Jika belum ada: create.
             */
            $teacher = Teacher::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'phone' => $validated['phone'],
                    'company' => $validated['company'] ?? null,
                    'specialization' => $validated['specialization'],
                    'experience_years' => $validated['experience_years'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                ]
            );

            $this->teacherId = $teacher->id;
        });

        /*
         * Kosongkan password dari state Livewire.
         */
        $this->reset([
            'password',
            'password_confirmation',
        ]);

        session()->flash(
            'success',
            'Data teacher berhasil diperbarui.'
        );

        return $this->redirect(
            route('teacher.index'),
            navigate: true
        );
    }

    public function render()
    {
        return view(
            'livewire.user.form.teacher-edit'
        )->layout('layouts.admin');
    }
}
