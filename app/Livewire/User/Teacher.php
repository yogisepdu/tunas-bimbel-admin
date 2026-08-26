<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Teacher extends Component
{
    public string $title = 'Teacher';

    public function render()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh akun dengan role teacher
        |--------------------------------------------------------------------------
        */
        $teachers = User::query()
            ->where('role', 'teacher')
            ->with('teacher')
            ->latest()
            ->get();

        return view(
            'livewire.user.teacher',
            compact('teachers')
        )->layout('layouts.admin', [
            'title' => $this->title,
        ]);
    }

    public function delete(int $id): void
    {
        $user = User::query()
            ->where('role', 'teacher')
            ->findOrFail($id);

        DB::transaction(function () use ($user) {
            /*
            | Data pada tabel teachers ikut terhapus karena
            | foreign key menggunakan cascadeOnDelete.
            */
            $user->delete();
        });

        session()->flash(
            'success',
            'Akun teacher berhasil dihapus.'
        );
    }
}
