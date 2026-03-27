<?php

use App\Exports\SoalTemplateExport;
use App\Livewire\Auth\Login;
use App\Livewire\Class\CourseIndex;
use App\Livewire\Class\Form\Create;
use App\Livewire\Class\Form\Edit;
use App\Livewire\Dashboard;
use App\Livewire\Kalender\Form\Index as FormIndex;
use App\Livewire\Kalender\Index as KalenderIndex;
use App\Livewire\Linked\Index as LinkedIndex;
use App\Livewire\Pdf\Form\Create as PdfFormCreate;
use App\Livewire\Pdf\Form\Edit as PdfFormEdit;
use App\Livewire\Pdf\Index as PdfIndex;
use App\Livewire\Quiz\Form\Create as QuizFormCreate;
use App\Livewire\Quiz\Form\Edit as QuizFormEdit;
use App\Livewire\Quiz\Index as QuizIndex;
use App\Livewire\Quizez\Form\Create as QuizezFormCreate;
use App\Livewire\Quizez\Index as QuizezIndex;
use App\Livewire\SoalSection\Form\Create as SoalSectionFormCreate;
use App\Livewire\SoalSection\Form\ImportExcel;
use App\Livewire\SoalSection\Index as SoalSectionIndex;
use App\Livewire\SoalSection\SoalQuestion;
use App\Livewire\SoalSection\SoalSet;
use App\Livewire\SubCourse\Form\Create as FormCreate;
use App\Livewire\SubCourse\Form\Edit as FormEdit;
use App\Livewire\SubCourse\Index;
use App\Livewire\User\Form\CreateTeacher;
use App\Livewire\User\Form\StudentCreate;
use App\Livewire\User\Form\StudentEdit;
use App\Livewire\User\Form\TeacherEdit;
use App\Livewire\User\Student;
use App\Livewire\User\Teacher;
use App\Livewire\Video\Form\Create as VideoFormCreate;
use App\Livewire\Video\Form\Edit as VideoFormEdit;
use App\Livewire\Video\Index as VideoIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

    // Video
    Route::get('/video', VideoIndex::class)->name('video.index');
    Route::get('/video/create', VideoFormCreate::class)->name('video.create');
    Route::get('/video/{id}/edit', VideoFormEdit::class)->name('video.edit');

    // PDF
    Route::get('/pdf', PdfIndex::class)->name('pdf.index');
    Route::get('/pdf/create', PdfFormCreate::class)->name('pdf.create');
    Route::get('/pdf/{id}/edit', PdfFormEdit::class)->name('pdf.edit');

    // Quiz
    Route::get('/quiz', QuizIndex::class)->name('quiz.index');
    Route::get('/quiz/create', QuizFormCreate::class)->name('quiz.create');
    Route::get('/quiz/{id}/edit', QuizFormEdit::class)->name('quiz.edit');

    // Create Question
    Route::get('/quiz/{quiz}/questions', QuizezIndex::class)->name('question.index');
    Route::get('/quiz/{quiz}/questions/create', QuizezFormCreate::class)->name('question.create');

    // Soal Section
    Route::get('/soal-section', SoalSectionIndex::class)->name('soal-section.index');
    Route::get('/soal-set/create', SoalSet::class)->name('soal-set.index');
    Route::get('/soal-question/home', SoalQuestion::class)->name('soal-question.index');
    Route::get('/soal-question/create', SoalSectionFormCreate::class)->name('soal-question.create');
    Route::get('/soal/import', ImportExcel::class)->name('soal.import');
    Route::get('/soal/template', function () {
        return Excel::download(
            new SoalTemplateExport,
            'template-soal.xlsx'
        );
    })->name('soal.template');

    // Kalender
    Route::get('/kalender', KalenderIndex::class)->name('kalender.index');
    Route::get('/kalender/create', FormIndex::class)->name('kalender.create');
    
    // Linked
    Route::get('/linked', LinkedIndex::class)->name('linked.index');

    // Logout
    Route::post('/logout', [Dashboard::class, 'logout'])->name('logout');

});