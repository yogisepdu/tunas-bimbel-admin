<?php

namespace App\Livewire\User\Form;

use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class CreateTeacher extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public $phone;
    public $company;
    public $specialization;
    public $experience_years;
    public $bio;

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required',
            'specialization' => 'required'
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => 'teacher'
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'phone' => $this->phone,
            'company' => $this->company,
            'specialization' => $this->specialization,
            'experience_years' => $this->experience_years,
            'bio' => $this->bio
        ]);

        return $this->redirect(route('teacher.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.user.form.create-teacher')->layout('layouts.admin');
    }
}
