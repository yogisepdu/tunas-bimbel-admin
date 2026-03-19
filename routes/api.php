<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Calendar\CalendarController;
use App\Http\Controllers\Api\EBook\ChapterController;
use App\Http\Controllers\Api\Progress\ProgressController;
use App\Http\Controllers\Api\Quiz\QuizController;
use App\Http\Controllers\Api\Quiz\QuizMetaController;

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

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/chapters', [ChapterController::class,'index']);

    Route::get('/chapters/{id}', [ChapterController::class,'show']);

    Route::post('/chapter-progress', [ProgressController::class, 'store']);

    Route::post('/quiz-result', [ProgressController::class, 'storeResult']);

    Route::get('/calendar-events', [CalendarController::class, 'index']);

    Route::get('/chapter/{chapter}/quiz', [QuizController::class, 'questions']);

    Route::get('/quiz/{quiz}/meta', [QuizMetaController::class, 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});