<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class TeacherEdit extends Component
{
    public $teacherId;
    public $userId;

    public $name;
    public $email;
    public $phone;
    public $company;
    public $specialization;
    public $experience_years;
    public $bio;

    public $password;
    public $password_confirmation;

    public function mount($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $this->teacherId = $teacher->id;
        $this->userId = $teacher->user->id;

        $this->name = $teacher->user->name;
        $this->email = $teacher->user->email;
        $this->phone = $teacher->phone;
        $this->company = $teacher->company;
        $this->specialization = $teacher->specialization;
        $this->experience_years = $teacher->experience_years;
        $this->bio = $teacher->bio;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'required',
        ]);

        $user = User::find($this->userId);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $user->update([
                'password' => bcrypt($this->password)
            ]);
        }

        Teacher::find($this->teacherId)->update([
            'phone' => $this->phone,
            'company' => $this->company,
            'specialization' => $this->specialization,
            'experience_years' => $this->experience_years,
            'bio' => $this->bio
        ]);

        session()->flash('success','Teacher updated successfully');

        return $this->redirect(route('teacher.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.user.form.teacher-edit')->layout('layouts.admin');
    }
}
