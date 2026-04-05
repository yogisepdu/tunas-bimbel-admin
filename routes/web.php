<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// 🔥 AUTH LIVEWIRE
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ResetSuccess;

// 🔥 MAIN APP
use App\Livewire\Dashboard;
use App\Livewire\User\Student;
use App\Livewire\User\Teacher;
use App\Livewire\User\Form\StudentCreate;
use App\Livewire\User\Form\StudentEdit;
use App\Livewire\User\Form\CreateTeacher;
use App\Livewire\User\Form\TeacherEdit;

// 🔥 COURSE
use App\Livewire\Class\CourseIndex;
use App\Livewire\Class\Form\Create;
use App\Livewire\Class\Form\Edit;

// 🔥 SUB COURSE
use App\Livewire\SubCourse\Index;
use App\Livewire\SubCourse\Form\Create as FormCreate;
use App\Livewire\SubCourse\Form\Edit as FormEdit;

// 🔥 VIDEO
use App\Livewire\Video\Index as VideoIndex;
use App\Livewire\Video\Form\Create as VideoFormCreate;
use App\Livewire\Video\Form\Edit as VideoFormEdit;

// 🔥 PDF
use App\Livewire\Pdf\Index as PdfIndex;
use App\Livewire\Pdf\Form\Create as PdfFormCreate;
use App\Livewire\Pdf\Form\Edit as PdfFormEdit;

// 🔥 QUIZ
use App\Livewire\Quiz\Index as QuizIndex;
use App\Livewire\Quiz\Form\Create as QuizFormCreate;
use App\Livewire\Quiz\Form\Edit as QuizFormEdit;
use App\Livewire\Quizez\Index as QuizezIndex;
use App\Livewire\Quizez\Form\Create as QuizezFormCreate;

// 🔥 SOAL
use App\Livewire\SoalSection\Index as SoalSectionIndex;
use App\Livewire\SoalSection\SoalSet;
use App\Livewire\SoalSection\SoalQuestion;
use App\Livewire\SoalSection\Form\Create as SoalSectionFormCreate;
use App\Livewire\SoalSection\Form\ImportExcel;

// 🔥 KALENDER
use App\Livewire\Kalender\Index as KalenderIndex;
use App\Livewire\Kalender\Form\Index as FormIndex;

// 🔥 LINKED
use App\Livewire\Linked\Index as LinkedIndex;
use App\Livewire\Linked\AnnouncementController;

// ======================
// 🔓 PUBLIC ROUTES
// ======================
Route::get('/', Login::class)->name('login');
Route::get('/register', Login::class)->name('register');

// FORM
Route::get('/reset-password', [ResetPasswordController::class, 'showForm'])
    ->name('password.reset');

// SUBMIT
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// SUCCESS
Route::view('/reset-success', 'auth.reset-success')
    ->name('password.success');


// ======================
// 🔐 PROTECTED ROUTES
// ======================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // STUDENT
    Route::get('/student', Student::class)->name('student.index');
    Route::get('/student/create', StudentCreate::class)->name('student.create');
    Route::get('/student/{id}/edit', StudentEdit::class)->name('student.edit');

    // TEACHER
    Route::get('/teacher', Teacher::class)->name('teacher.index');
    Route::get('/teacher/create', CreateTeacher::class)->name('teacher.create');
    Route::get('/teacher/{id}/edit', TeacherEdit::class)->name('teacher.edit');

    // COURSE
    Route::get('/course', CourseIndex::class)->name('course.index');
    Route::get('/course/create', Create::class)->name('course.create');
    Route::get('/course/{id}/edit', Edit::class)->name('course.edit');

    // SUB COURSE
    Route::get('/sub-course', Index::class)->name('sub-course.index');
    Route::get('/sub-course/create', FormCreate::class)->name('sub-course.create');
    Route::get('/sub-course/{id}/edit', FormEdit::class)->name('sub-course.edit');

    // VIDEO
    Route::get('/video', VideoIndex::class)->name('video.index');
    Route::get('/video/create', VideoFormCreate::class)->name('video.create');
    Route::get('/video/{id}/edit', VideoFormEdit::class)->name('video.edit');

    // PDF
    Route::get('/pdf', PdfIndex::class)->name('pdf.index');
    Route::get('/pdf/create', PdfFormCreate::class)->name('pdf.create');
    Route::get('/pdf/{id}/edit', PdfFormEdit::class)->name('pdf.edit');

    // QUIZ
    Route::get('/quiz', QuizIndex::class)->name('quiz.index');
    Route::get('/quiz/create', QuizFormCreate::class)->name('quiz.create');
    Route::get('/quiz/{id}/edit', QuizFormEdit::class)->name('quiz.edit');

    // QUESTION
    Route::get('/quiz/{quiz}/questions', QuizezIndex::class)->name('question.index');
    Route::get('/quiz/{quiz}/questions/create', QuizezFormCreate::class)->name('question.create');

    // SOAL
    Route::get('/soal-section', SoalSectionIndex::class)->name('soal-section.index');
    Route::get('/soal-set/create', SoalSet::class)->name('soal-set.index');
    Route::get('/soal-question/home', SoalQuestion::class)->name('soal-question.index');
    Route::get('/soal-question/create', SoalSectionFormCreate::class)->name('soal-question.create');
    Route::get('/soal/import', ImportExcel::class)->name('soal.import');

    Route::get('/soal/template', function () {
        return Excel::download(
            new \App\Exports\SoalTemplateExport,
            'template-soal.xlsx'
        );
    })->name('soal.template');

    // KALENDER
    Route::get('/kalender', KalenderIndex::class)->name('kalender.index');
    Route::get('/kalender/create', FormIndex::class)->name('kalender.create');

    // LINKED
    Route::get('/linked', LinkedIndex::class)->name('linked.index');

    // ANNOUNCEMENT
    Route::get('/announcement', AnnouncementController::class)->name('announcement.index');

    // LOGOUT
    Route::post('/logout', [Dashboard::class, 'logout'])->name('logout');
});