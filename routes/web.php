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
use App\Livewire\User\Teacher as TeacherIndex;
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

use App\Http\Controllers\TransactionProofController;

use App\Livewire\PaymentMethods\Index as PaymentMethodsIndex;
use App\Livewire\Transactions\Index as TransactionsIndex;
use App\Livewire\Transactions\Show as TransactionsShow;

use App\Livewire\Payment\PaymentPage;

use App\Http\Controllers\VideoPreviewController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', HomeDashboard::class)
    ->name('home');

Route::get(
    '/checkout/{id}',
    CheckoutPage::class
)
    ->whereNumber('id')
    ->name('checkout');

Route::get(
    '/payment/{token}',
    PaymentPage::class
)
    ->whereUuid('token')
    ->name('payment.show');

/*
|--------------------------------------------------------------------------
| GUEST / AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)
        ->name('login');

    /*
     * Registrasi melalui panel website tidak disediakan.
     * Registrasi student dilakukan melalui aplikasi/API.
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
| Role yang boleh masuk:
|
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
    | Hanya administrator yang dapat mengelola akun.
    |
    */

    Route::middleware('role:administrator')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        Route::get('/student', Student::class)
            ->name('student.index');

        Route::get('/student/create', StudentCreate::class)
            ->name('student.create');

        Route::get('/student/{id}/edit', StudentEdit::class)
            ->whereNumber('id')
            ->name('student.edit');

        /*
        |--------------------------------------------------------------------------
        | ADMIN DAN TEACHER
        |--------------------------------------------------------------------------
        |
        | Halaman ini menampilkan:
        |
        | - Tabel akun Admin
        | - Tabel akun Teacher
        |
        */

        Route::get('/teacher', TeacherIndex::class)
            ->name('teacher.index');

        /*
        |--------------------------------------------------------------------------
        | TAMBAH AKUN ADMIN ATAU TEACHER
        |--------------------------------------------------------------------------
        |
        | Satu komponen CreateTeacher digunakan untuk membuat:
        |
        | - Admin
        | - Teacher
        |
        */

        Route::get('/teacher/create', CreateTeacher::class)
            ->name('teacher.create');

        /*
        |--------------------------------------------------------------------------
        | EDIT AKUN ADMIN ATAU TEACHER
        |--------------------------------------------------------------------------
        |
        | TeacherEdit dapat membuka akun dengan role:
        |
        | - Admin
        | - Teacher
        |
        */

        Route::get(
            '/teacher/{userId}/edit',
            TeacherEdit::class
        )
            ->whereNumber('userId')
            ->name('teacher.edit');

        /*
        |--------------------------------------------------------------------------
        | KOMPATIBILITAS ROUTE ADMIN LAMA
        |--------------------------------------------------------------------------
        |
        | Route berikut dipertahankan agar link lama yang menggunakan
        | admin.index, admin.create, atau admin.edit tidak error.
        |
        */

        Route::get('/admin', function () {
            return redirect()->route('teacher.index');
        })->name('admin.index');

        Route::get('/admin/create', function () {
            return redirect()->route('teacher.create');
        })->name('admin.create');

        Route::get('/admin/{id}/edit', function ($id) {
            return redirect()->route('teacher.edit', [
                'userId' => $id,
            ]);
        })
            ->whereNumber('id')
            ->name('admin.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | MASTER KELAS
    |--------------------------------------------------------------------------
    |
    | Administrator dan admin dapat membuat atau mengedit kelas.
    | Teacher hanya dapat melihat kelas yang ditugaskan kepadanya.
    |
    */

    Route::get('/course', CourseIndex::class)
        ->name('course.index');

    Route::middleware('role:administrator,admin')->group(function () {

        Route::get(
            '/payment-methods',
            PaymentMethodsIndex::class
        )->name('payment-methods.index');

        Route::get(
            '/transactions',
            TransactionsIndex::class
        )->name('transactions.index');

        Route::get(
            '/transactions/{id}',
            TransactionsShow::class
        )
            ->whereNumber('id')
            ->name('transactions.show');

        Route::get(
            '/transactions/{transaction}/proof',
            [
                TransactionProofController::class,
                'show',
            ]
        )
            ->whereNumber('transaction')
            ->name('transactions.proof');

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
    |
    | Untuk teacher, data wajib difilter menggunakan ClassAccess.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | SUB MATERI
    |--------------------------------------------------------------------------
    */

    Route::get('/sub-course', SubCourseIndex::class)
        ->name('sub-course.index');

    Route::get('/sub-course/create', SubCourseCreate::class)
        ->name('sub-course.create');

    Route::get(
        '/sub-course/{id}/edit',
        SubCourseEdit::class
    )
        ->whereNumber('id')
        ->name('sub-course.edit');

    /*
    |--------------------------------------------------------------------------
    | VIDEO
    |--------------------------------------------------------------------------
    */

    Route::get('/video', VideoIndex::class)
        ->name('video.index');

    Route::get('/video/create', VideoCreate::class)
        ->name('video.create');

    Route::get('/video/{id}/edit', VideoEdit::class)
        ->whereNumber('id')
        ->name('video.edit');

    Route::get(
        '/video/{video}/preview',
        [
            VideoPreviewController::class,
            'show',
        ]
    )
        ->whereNumber('video')
        ->name('video.preview');

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | DAFTAR PERTANYAAN QUIZ
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/quiz/{quiz}/questions',
        QuestionIndex::class
    )
        ->whereNumber('quiz')
        ->name('question.index');

    Route::get(
        '/quiz/{quiz}/questions/create',
        QuestionCreate::class
    )
        ->whereNumber('quiz')
        ->name('question.create');

    /*
    |--------------------------------------------------------------------------
    | TRYOUT
    |--------------------------------------------------------------------------
    */

    /*
     * Section TryOut.
     */
    Route::get('/soal-section', SoalSectionIndex::class)
        ->name('soal-section.index');

    /*
     * Set TryOut.
     */
    Route::get('/soal-set/create', SoalSet::class)
        ->name('soal-set.index');

    /*
     * Daftar soal TryOut.
     */
    Route::get('/soal-question/home', SoalQuestion::class)
        ->name('soal-question.index');

    Route::get(
        '/soal-question/create',
        SoalQuestionCreate::class
    )->name('soal-question.create');

    /*
     * Import soal TryOut.
     */
    Route::get('/soal/import', ImportExcel::class)
        ->name('soal.import');

    /*
     * Download template Excel.
     */
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

        Route::get(
            '/packages/{id}/edit',
            PackagesEdit::class
        )
            ->whereNumber('id')
            ->name('packages.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | OPERASIONAL ADMIN
    |--------------------------------------------------------------------------
    |
    | Kalender, Linked, dan Announcement dapat diakses oleh:
    |
    | - administrator
    | - admin
    |
    */

    Route::middleware('role:administrator,admin')->group(function () {

        /*
         * Kalender.
         */
        Route::get('/kalender', KalenderIndex::class)
            ->name('kalender.index');

        Route::get(
            '/kalender/create',
            KalenderCreate::class
        )->name('kalender.create');

        /*
         * Linked.
         */
        Route::get('/linked', LinkedIndex::class)
            ->name('linked.index');

        /*
         * Announcement.
         */
        Route::get(
            '/announcement',
            AnnouncementController::class
        )->name('announcement.index');
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
