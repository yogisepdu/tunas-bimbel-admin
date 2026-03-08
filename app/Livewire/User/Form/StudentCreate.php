<?php

namespace App\Livewire\User\Form;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StudentCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public $phone;
    public $school;
    public $grade;
    public $birth_date;
    public $address;


    public function render()
    {
        return view('livewire.user.form.create')
        ->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required',
            'school' => 'required'
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'student'
        ]);

        Student::create([
            'user_id' => $user->id,
            'phone' => $this->phone,
            'school' => $this->school,
            'grade' => $this->grade,
            'birth_date' => $this->birth_date,
            'address' => $this->address
        ]);

        session()->flash('message', 'Student created successfully.');
        
        return $this->redirect(route('student.index'), navigate: true);
    }
}
