<?php

namespace App\Livewire\User\Form;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StudentEdit extends Component
{
    public $studentId;
    public $userId;

    public $name;
    public $email;

    public $phone;
    public $school;
    public $grade;
    public $birth_date;
    public $address;

    public $password;
    public $password_confirmation;

    public function mount($id)
    {
        $student = Student::with('user')->findOrFail($id);

        $this->studentId = $student->id;
        $this->userId = $student->user->id;

        $this->name = $student->user->name;
        $this->email = $student->user->email;

        $this->phone = $student->phone;
        $this->school = $student->school;
        $this->grade = $student->grade;
        $this->birth_date = $student->birth_date;
        $this->address = $student->address;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'required',
            'school' => 'required',
        ]);

        $user = User::find($this->userId);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $user->update([
                'password' => Hash::make($this->password)
            ]);
        }

        Student::find($this->studentId)->update([
            'phone' => $this->phone,
            'school' => $this->school,
            'grade' => $this->grade,
            'birth_date' => $this->birth_date,
            'address' => $this->address
        ]);

        session()->flash('success','Student updated successfully');

        return $this->redirect(route('student.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.user.form.student-edit')->layout('layouts.admin');
    }
}
