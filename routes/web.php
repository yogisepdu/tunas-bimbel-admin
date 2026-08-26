<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// EXPORT
use App\Exports\SoalTemplateExport;

// CONTROLLER
use App\Http\Controllers\Auth\ResetPasswordController;

// PUBLIC PAGE
use App\Livewire\HomeDashboard;
use App\Livewire\Checkout\CheckoutPage;

// AUTH
use App\Livewire\Auth\Login;

// DASHBOARD
use App\Livewire\Dashboard;

// USER MANAGEMENT
use App\Livewire\User\Student;
use App\Livewire\User\Teacher;
use App\Livewire\User\Form\StudentCreate;
use App\Livewire\User\Form\StudentEdit;
use App\Livewire\User\Form\CreateTeacher;
use App\Livewire\User\Form\TeacherEdit;

// COURSE
use App\Livewire\Class\CourseIndex;
use App\Livewire\Class\Form\Create as CourseCreate;
use App\Livewire\Class\Form\Edit as CourseEdit;

// SUB COURSE
use App\Livewire\SubCourse\Index as SubCourseIndex;
use App\Livewire\SubCourse\Form\Create as SubCourseCreate;
use App\Livewire\SubCourse\Form\Edit as SubCourseEdit;

// VIDEO
use App\Livewire\Video\Index as VideoIndex;
use App\Livewire\Video\Form\Create as VideoCreate;
use App\Livewire\Video\Form\Edit as VideoEdit;

// PDF
use App\Livewire\Pdf\Index as PdfIndex;
use App\Livewire\Pdf\Form\Create as PdfCreate;
use App\Livewire\Pdf\Form\Edit as PdfEdit;

// QUIZ
use App\Livewire\Quiz\Index as QuizIndex;
use App\Livewire\Quiz\Form\Create as QuizCreate;
use App\Livewire\Quiz\Form\Edit as QuizEdit;

// QUIZ QUESTIONS
use App\Livewire\Quizez\Index as QuestionIndex;
use App\Livewire\Quizez\Form\Create as QuestionCreate;

// TRYOUT
use App\Livewire\SoalSection\Index as SoalSectionIndex;
use App\Livewire\SoalSection\SoalSet;
use App\Livewire\SoalSection\SoalQuestion;
use App\Livewire\SoalSection\Form\Create as SoalQuestionCreate;
use App\Livewire\SoalSection\Form\ImportExcel;

// KALENDER
use App\Livewire\Kalender\Index as KalenderIndex;
use App\Livewire\Kalender\Form\Index as KalenderCreate;

// LINKED DAN ANNOUNCEMENT
use App\Livewire\Linked\Index as LinkedIndex;
use App\Livewire\Linked\AnnouncementController;

// PACKAGES
use App\Livewire\Packages\Index as PackagesIndex;
use App\Livewire\Packages\Form\Create as PackagesCreate;
use App\Livewire\Packages\Form\Edit as PackagesEdit;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', HomeDashboard::class)
    ->name('home');

Route::get('/checkout/{id}', CheckoutPage::class)
    ->whereNumber('id')
    ->name('checkout');

/*
|--------------------------------------------------------------------------
| GUEST / AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)
        ->name('login');

    /*
     * Registrasi melalui website admin tidak disediakan.
     * Registrasi student dilakukan melalui aplikasi/API.
     *
     * Route tetap tersedia agar pemanggilan route('register')
     * tidak menghasilkan error.
     */
    Route::redirect('/register', '/login')
        ->name('register');

    Route::get(
        '/reset-password',
        [ResetPasswordController::class, 'showForm']
    )->name('password.reset');

    Route::post(
        '/reset-password',
        [ResetPasswordController::class, 'reset']
    )->name('password.update');

    Route::view('/reset-success', 'auth.reset-success')
        ->name('password.success');
});

/*
|--------------------------------------------------------------------------
| PANEL ADMIN
|--------------------------------------------------------------------------
|
| Hanya role berikut yang boleh masuk:
| - administrator
| - admin
| - teacher
|
| Student tidak dapat mengakses panel website.
|
*/

Route::middleware([
    'auth',
    'role:administrator,admin,teacher',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    |
    | Hanya administrator yang boleh mengelola akun student dan teacher.
    |
    */

    Route::middleware('role:administrator')->group(function () {

        // STUDENT
        Route::get('/student', Student::class)
            ->name('student.index');

        Route::get('/student/create', StudentCreate::class)
            ->name('student.create');

        Route::get('/student/{id}/edit', StudentEdit::class)
            ->whereNumber('id')
            ->name('student.edit');

        // TEACHER
        Route::get('/teacher', Teacher::class)
            ->name('teacher.index');

        Route::get('/teacher/create', CreateTeacher::class)
            ->name('teacher.create');

        Route::get('/teacher/{userId}/edit', TeacherEdit::class)
            ->whereNumber('userId')
            ->name('teacher.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | MASTER KELAS
    |--------------------------------------------------------------------------
    |
    | Administrator dan admin dapat membuat/mengedit kelas.
    | Teacher hanya dapat melihat kelas yang ditugaskan kepadanya.
    |
    */

    Route::get('/course', CourseIndex::class)
        ->name('course.index');

    Route::middleware('role:administrator,admin')->group(function () {
        Route::get('/course/create', CourseCreate::class)
            ->name('course.create');

        Route::get('/course/{id}/edit', CourseEdit::class)
            ->whereNumber('id')
            ->name('course.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | MATERI
    |--------------------------------------------------------------------------
    |
    | Dapat diakses administrator, admin, dan teacher.
    | Untuk teacher, data wajib difilter menggunakan ClassAccess.
    |
    */

    // SUB MATERI
    Route::get('/sub-course', SubCourseIndex::class)
        ->name('sub-course.index');

    Route::get('/sub-course/create', SubCourseCreate::class)
        ->name('sub-course.create');

    Route::get('/sub-course/{id}/edit', SubCourseEdit::class)
        ->whereNumber('id')
        ->name('sub-course.edit');

    // VIDEO
    Route::get('/video', VideoIndex::class)
        ->name('video.index');

    Route::get('/video/create', VideoCreate::class)
        ->name('video.create');

    Route::get('/video/{id}/edit', VideoEdit::class)
        ->whereNumber('id')
        ->name('video.edit');

    // PDF
    Route::get('/pdf', PdfIndex::class)
        ->name('pdf.index');

    Route::get('/pdf/create', PdfCreate::class)
        ->name('pdf.create');

    Route::get('/pdf/{id}/edit', PdfEdit::class)
        ->whereNumber('id')
        ->name('pdf.edit');

    /*
    |--------------------------------------------------------------------------
    | QUIZ
    |--------------------------------------------------------------------------
    */

    Route::get('/quiz', QuizIndex::class)
        ->name('quiz.index');

    Route::get('/quiz/create', QuizCreate::class)
        ->name('quiz.create');

    Route::get('/quiz/{id}/edit', QuizEdit::class)
        ->whereNumber('id')
        ->name('quiz.edit');

    // DAFTAR PERTANYAAN QUIZ
    Route::get('/quiz/{quiz}/questions', QuestionIndex::class)
        ->whereNumber('quiz')
        ->name('question.index');

    Route::get('/quiz/{quiz}/questions/create', QuestionCreate::class)
        ->whereNumber('quiz')
        ->name('question.create');

    /*
    |--------------------------------------------------------------------------
    | TRYOUT
    |--------------------------------------------------------------------------
    */

    // SECTION TRYOUT
    Route::get('/soal-section', SoalSectionIndex::class)
        ->name('soal-section.index');

    // SET TRYOUT
    Route::get('/soal-set/create', SoalSet::class)
        ->name('soal-set.index');

    // DAFTAR SOAL TRYOUT
    Route::get('/soal-question/home', SoalQuestion::class)
        ->name('soal-question.index');

    Route::get('/soal-question/create', SoalQuestionCreate::class)
        ->name('soal-question.create');

    // IMPORT SOAL
    Route::get('/soal/import', ImportExcel::class)
        ->name('soal.import');

    // DOWNLOAD TEMPLATE EXCEL
    Route::get('/soal/template', function () {
        return Excel::download(
            new SoalTemplateExport(),
            'template-soal.xlsx'
        );
    })->name('soal.template');

    /*
    |--------------------------------------------------------------------------
    | PACKAGES
    |--------------------------------------------------------------------------
    |
    | Hanya administrator dan admin.
    |
    */

    Route::middleware('role:administrator,admin')->group(function () {
        Route::get('/packages', PackagesIndex::class)
            ->name('packages.index');

        Route::get('/packages/create', PackagesCreate::class)
            ->name('packages.create');

        Route::get('/packages/{id}/edit', PackagesEdit::class)
            ->whereNumber('id')
            ->name('packages.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | OPERASIONAL ADMIN
    |--------------------------------------------------------------------------
    |
    | Kalender, Linked dan Announcement hanya dapat diakses oleh:
    | - administrator
    | - admin
    |
    */

    Route::middleware('role:administrator,admin')->group(function () {

        // KALENDER
        Route::get('/kalender', KalenderIndex::class)
            ->name('kalender.index');

        Route::get('/kalender/create', KalenderCreate::class)
            ->name('kalender.create');

        // LINKED
        Route::get('/linked', LinkedIndex::class)
            ->name('linked.index');

        // ANNOUNCEMENT
        Route::get('/announcement', AnnouncementController::class)
            ->name('announcement.index');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
