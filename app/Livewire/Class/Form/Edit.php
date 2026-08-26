<?php

namespace App\Livewire\Class\Form;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $classId;
    public $name;
    public $description;

    public array $teacher_ids = [];

    public function mount($id)
    {
        /*
         * Halaman edit master kelas hanya boleh
         * dibuka administrator dan admin.
         */
        ClassAccess::ensureManager();

        $class = ClassRoom::query()
            ->with('teachers')
            ->findOrFail($id);

        $this->classId = $class->id;
        $this->name = $class->name;
        $this->description = $class->description;

        /*
         * Mengambil teacher yang sudah ditugaskan
         * pada kelas ini agar checkbox tercentang.
         */
        $this->teacher_ids = $class->teachers
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    public function update()
    {
        ClassAccess::ensureManager();

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'teacher_ids' => [
                'array',
            ],
            'teacher_ids.*' => [
                'integer',
                'distinct',
                'exists:teachers,id',
            ],
        ]);

        $class = ClassRoom::findOrFail(
            $this->classId
        );

        DB::transaction(function () use ($class, $validated) {
            /*
             * Memperbarui informasi kelas.
             */
            $class->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            /*
             * Memperbarui teacher pada tabel class_teacher.
             *
             * Teacher yang dicentang akan ditambahkan.
             * Teacher yang tidak dicentang akan dilepas.
             */
            $class->teachers()->sync(
                $validated['teacher_ids'] ?? []
            );
        });

        session()->flash(
            'success',
            'Kelas dan teacher berhasil diperbarui.'
        );

        return $this->redirect(
            route('course.index'),
            navigate: true
        );
    }

    public function render()
    {
        ClassAccess::ensureManager();

        /*
         * Hanya mengambil data pada tabel teachers
         * yang terhubung dengan user ber-role teacher.
         */
        $teachers = Teacher::query()
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->with('user')
            ->get()
            ->sortBy(function ($teacher) {
                return strtolower(
                    $teacher->user?->name ?? ''
                );
            })
            ->values();

        return view('livewire.class.form.edit', [
            'teachers' => $teachers,
        ])->layout('layouts.admin');
    }
}
