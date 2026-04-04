<?php

use App\Http\Controllers\Api\Announcement\AnnouncementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Calendar\CalendarController;
use App\Http\Controllers\Api\EBook\ChapterController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Progress\ProgressController;
use App\Http\Controllers\Api\Quiz\QuizController;
use App\Http\Controllers\Api\Quiz\QuizMetaController;
use App\Http\Controllers\Api\Soal\SoalController;
use App\Models\Linked;

/*
|--------------------------------------------------------------------------
| API Rate Limiter
|--------------------------------------------------------------------------
*/

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [ProfileController::class, 'me']);
    Route::post('/profile', [ProfileController::class, 'update']);
    
    Route::get('/chapters', [ChapterController::class,'index']);

    Route::get('/chapters/{id}', [ChapterController::class,'show']);

    Route::post('/chapter-progress', [ProgressController::class, 'store']);

    Route::post('/quiz-result', [ProgressController::class, 'storeResult']);

    Route::get('/quiz-progress/{chapterId}', [ProgressController::class, 'checkQuizProgress']);

    Route::get('/leaderboard/{quizId}', [ProgressController::class, 'leaderboard']);

    Route::get('/calendar-events', [CalendarController::class, 'index']);

    Route::get('/chapter/{chapter}/quiz', [QuizController::class, 'questions']);

    Route::get('/quiz/{quiz}/meta', [QuizMetaController::class, 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/links/{name}', function ($name) {
        $link = Linked::where('name', $name)->first();

        if (!$link) {
            return response()->json([
                'message' => 'Link tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'name' => $link->name,
            'url' => $link->url,
        ]);
    });

    // 🔥 SOAL
    // 🔥 SECTION + SET
    Route::get('/soal-sections', [SoalController::class, 'sections']);

    Route::get('/soal-sections/{setId}', [SoalController::class, 'sectionsBySet']);

    // 🔥 QUESTIONS
    Route::get('/soal-sets/{id}/questions', [SoalController::class, 'questions']);

    // 🔥 SOAL PROGRESS
    Route::get('/soal-progress/{setId}', [SoalController::class, 'checkSoalProgress']);
    Route::post('/soal-result', [SoalController::class, 'storeResult']);

    // 🔥 LEADERBOARD
    Route::get('/soal-leaderboard/{soalSetId}', [SoalController::class, 'leaderboard']);

    // 🔥 ANNOUNCEMENT
    Route::get('/announcements', [AnnouncementController::class, 'index']);

});