<?php

namespace App\Livewire\Class\Form;

use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';

    public array $teacher_ids = [];

    public function save()
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
                'nullable',
                'array',
            ],
            'teacher_ids.*' => [
                'integer',
                'exists:teachers,id',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $class = ClassRoom::create([
                'name' => trim($validated['name']),
                'description' =>
                $validated['description'] ?: null,
            ]);

            $class->teachers()->sync(
                $validated['teacher_ids'] ?? []
            );
        });

        session()->flash(
            'success',
            'Kelas dan penugasan teacher berhasil dibuat.'
        );

        return $this->redirect(
            route('course.index'),
            navigate: true
        );
    }

    public function render()
    {
        ClassAccess::ensureManager();

        $teachers = Teacher::query()
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->with('user')
            ->get()
            ->sortBy('user.name');

        return view(
            'livewire.class.form.create',
            compact('teachers')
        )->layout('layouts.admin');
    }
}
