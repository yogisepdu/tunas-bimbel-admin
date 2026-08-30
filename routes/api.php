<?php

use App\Http\Controllers\Api\ActivitiesController;
use App\Http\Controllers\Api\Announcement\AnnouncementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Calendar\CalendarController;
use App\Http\Controllers\Api\EBook\ChapterController;
use App\Http\Controllers\Api\Media\PrivateMediaController;
use App\Http\Controllers\Api\Package\UserPackageController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Progress\ProgressController;
use App\Http\Controllers\Api\Quiz\QuizController;
use App\Http\Controllers\Api\Quiz\QuizMetaController;
use App\Http\Controllers\Api\Soal\SoalController;
use App\Models\Linked;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RATE LIMIT
|--------------------------------------------------------------------------
*/

RateLimiter::for(
    'api',
    function (Request $request) {
        return Limit::perMinute(60)
            ->by(
                $request->user()?->id
                    ?: $request->ip()
            );
    }
);

/*
|--------------------------------------------------------------------------
| AUTH PUBLIC
|--------------------------------------------------------------------------
*/

Route::post(
    '/login',
    [
        AuthController::class,
        'login',
    ]
);

Route::post(
    '/register',
    [
        AuthController::class,
        'register',
    ]
);

Route::post(
    '/resend-verification',
    [
        AuthController::class,
        'resendVerification',
    ]
)->middleware(
    'throttle:3,1'
);

Route::post(
    '/forgot-password',
    [
        AuthController::class,
        'forgotPassword',
    ]
);

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/

Route::get(
    '/email/verify/{id}/{hash}',
    function (
        Request $request,
        $id,
        $hash
    ) {
        $user = User::find($id);

        if (! $user) {
            return response()->view(
                'emails.verify-failed'
            );
        }

        if (
            ! hash_equals(
                (string) $hash,
                sha1(
                    $user
                        ->getEmailForVerification()
                )
            )
        ) {
            return response()->view(
                'emails.verify-failed'
            );
        }

        if (
            $user->hasVerifiedEmail()
        ) {
            return response()->view(
                'emails.verify-success'
            );
        }

        $user->markEmailAsVerified();

        return response()->view(
            'emails.verify-success'
        );
    }
)
    ->middleware('signed')
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(
    'auth:sanctum'
)->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [
            ProfileController::class,
            'me',
        ]
    );

    Route::post(
        '/profile',
        [
            ProfileController::class,
            'update',
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | LEARNING CONTENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/chapters',
        [
            ChapterController::class,
            'index',
        ]
    );

    Route::get(
        '/chapters/{id}',
        [
            ChapterController::class,
            'show',
        ]
    )->whereNumber('id');

    Route::post(
        '/chapter-progress',
        [
            ProgressController::class,
            'store',
        ]
    );

    Route::get(
        '/media/pdf/{pdf}',
        [
            PrivateMediaController::class,
            'pdf',
        ]
    )
        ->whereNumber('pdf')
        ->name('api.media.pdf');

    Route::get(
        '/media/video/{video}',
        [
            PrivateMediaController::class,
            'video',
        ]
    )
        ->whereNumber('video')
        ->name('api.media.video');

    /*
    |--------------------------------------------------------------------------
    | QUIZ
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/chapter/{chapter}/quiz',
        [
            QuizController::class,
            'questions',
        ]
    )->whereNumber('chapter');

    Route::get(
        '/quiz/{quiz}/meta',
        [
            QuizMetaController::class,
            'show',
        ]
    )->whereNumber('quiz');

    Route::post(
        '/quiz-result',
        [
            ProgressController::class,
            'storeResult',
        ]
    );

    Route::get(
        '/quiz-progress/{chapterId}',
        [
            ProgressController::class,
            'checkQuizProgress',
        ]
    )->whereNumber('chapterId');

    Route::get(
        '/leaderboard/{quizId}',
        [
            ProgressController::class,
            'leaderboard',
        ]
    )->whereNumber('quizId');

    /*
    |--------------------------------------------------------------------------
    | TRYOUT / SOAL
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/soal-sections',
        [
            SoalController::class,
            'sections',
        ]
    );

    /*
     * Route ini memakai setId walaupun nama URL
     * masih "soal-sections" agar kompatibel
     * dengan aplikasi yang sudah ada.
     */
    Route::get(
        '/soal-sections/{setId}',
        [
            SoalController::class,
            'sectionsBySet',
        ]
    )->whereNumber('setId');

    Route::get(
        '/soal-sets/{id}/questions',
        [
            SoalController::class,
            'questions',
        ]
    )->whereNumber('id');

    Route::get(
        '/soal-progress/{setId}',
        [
            SoalController::class,
            'checkSoalProgress',
        ]
    )->whereNumber('setId');

    Route::post(
        '/soal-result',
        [
            SoalController::class,
            'storeResult',
        ]
    );

    Route::get(
        '/soal-leaderboard/{soalSetId}',
        [
            SoalController::class,
            'leaderboard',
        ]
    )->whereNumber('soalSetId');

    /*
    |--------------------------------------------------------------------------
    | PACKAGE & PAYMENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/packages',
        [
            UserPackageController::class,
            'index',
        ]
    );

    Route::get(
        '/my-classes',
        [
            UserPackageController::class,
            'myClasses',
        ]
    );

    Route::get(
        '/payment-methods',
        [
            UserPackageController::class,
            'paymentMethods',
        ]
    );

    Route::get(
        '/my-transactions',
        [
            UserPackageController::class,
            'myTransactions',
        ]
    );

    /*
     * buy-package TIDAK lagi insert langsung
     * ke user_packages.
     *
     * Request:
     * {
     *   "package_id": 1,
     *   "billing": "monthly",
     *   "payment_method_id": 1,
     *   "customer_phone": "081234567890"
     * }
     *
     * Response berisi payment_url.
     */
    Route::post(
        '/buy-package',
        [
            UserPackageController::class,
            'buy',
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | CALENDAR
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/calendar-events',
        [
            CalendarController::class,
            'index',
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | LINKS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/links/{name}',
        function ($name) {
            $link = Linked::where(
                'name',
                $name
            )->first();

            if (! $link) {
                return response()->json([
                    'message' =>
                    'Link tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'name' =>
                $link->name,

                'url' =>
                $link->url,
            ]);
        }
    );

    /*
    |--------------------------------------------------------------------------
    | ANNOUNCEMENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/announcements',
        [
            AnnouncementController::class,
            'index',
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | ACTIVITIES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/activities',
        [
            ActivitiesController::class,
            'index',
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [
            AuthController::class,
            'logout',
        ]
    );

    Route::get(
        '/user',
        function (
            Request $request
        ) {
            return $request->user();
        }
    );
});
