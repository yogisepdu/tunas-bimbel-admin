<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Teacher extends Component
{
    /**
     * Seluruh halaman Admin dan Teacher
     * hanya boleh diakses administrator.
     */
    private function ensureAdministrator(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->role === 'administrator',
            403
        );
    }

    public function deleteAdmin($userId): void
    {
        $this->ensureAdministrator();

        $admin = User::query()
            ->where('role', 'admin')
            ->findOrFail($userId);

        $admin->delete();

        session()->flash(
            'success',
            'Akun admin berhasil dihapus.'
        );
    }

    public function deleteTeacher($userId): void
    {
        $this->ensureAdministrator();

        $teacherUser = User::query()
            ->where('role', 'teacher')
            ->with('teacher')
            ->findOrFail($userId);

        DB::transaction(function () use ($teacherUser) {
            /*
             * Lepaskan hubungan teacher dengan kelas
             * sebelum profil teacher dihapus.
             */
            if ($teacherUser->teacher) {
                $teacherUser->teacher
                    ->classes()
                    ->detach();

                $teacherUser->teacher->delete();
            }

            $teacherUser->delete();
        });

        session()->flash(
            'success',
            'Akun teacher berhasil dihapus.'
        );
    }

    public function render()
    {
        $this->ensureAdministrator();

        /*
         * Akun Admin.
         */
        $admins = User::query()
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        /*
         * Akun Teacher beserta profilnya.
         */
        $teachers = User::query()
            ->where('role', 'teacher')
            ->with('teacher')
            ->orderBy('name')
            ->get();

        return view('livewire.user.teacher', [
            'admins' => $admins,
            'teachers' => $teachers,
        ])->layout('layouts.admin', [
            'title' => 'Admin dan Teacher',
        ]);
    }
}
