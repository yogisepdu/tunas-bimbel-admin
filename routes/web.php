<?php

use App\Livewire\Auth\Login;
use App\Livewire\Class\CourseIndex;
use App\Livewire\Class\Form\Create;
use App\Livewire\Class\Form\Edit;
use App\Livewire\Dashboard;
use App\Livewire\SubCourse\Form\Create as FormCreate;
use App\Livewire\SubCourse\Form\Edit as FormEdit;
use App\Livewire\SubCourse\Index;
use App\Livewire\User\Form\CreateTeacher;
use App\Livewire\User\Form\StudentCreate;
use App\Livewire\User\Form\StudentEdit;
use App\Livewire\User\Form\TeacherEdit;
use App\Livewire\User\Student;
use App\Livewire\User\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard');
    Route::get('/student', Student::class)
        ->name('student.index');
    Route::get('/student/create', StudentCreate::class)->name('student.create');
    Route::get('/student/{id}/edit', StudentEdit::class)->name('student.edit');

    // Teacher
    Route::get('/teacher', Teacher::class)->name('teacher.index');
    Route::get('/teacher/create', CreateTeacher::class)->name('teacher.create');
    Route::get('/teacher/{id}/edit', TeacherEdit::class)->name('teacher.edit');

    // Course
    Route::get('/course', CourseIndex::class)->name('course.index');
    Route::get('/course/create', Create::class)->name('course.create');
    Route::get('/course/{id}/edit', Edit::class)->name('course.edit');

    // Sub Course
    Route::get('/sub-course', Index::class)->name('sub-course.index');
    Route::get('/sub-course/create', FormCreate::class)->name('sub-course.create');
    Route::get('/sub-course/{id}/edit', FormEdit::class)->name('sub-course.edit');


    // Logout
    Route::post('/logout', [Dashboard::class, 'logout'])->name('logout');

});