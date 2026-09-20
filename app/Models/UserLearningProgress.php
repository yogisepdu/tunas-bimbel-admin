<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLearningProgress extends Model
{
    use HasFactory;

    protected $table = 'user_learning_progress';

    protected $fillable = [
        'user_id',
        'chapter_id',

        // Legacy / compatibility
        'video_id',
        'pdf_id',
        'quiz_id',

        // New progress storage
        'completed_video_ids',
        'completed_pdf_ids',
        'completed_quiz_ids',

        'status',
        'progress_percent',
    ];

    protected $casts = [
        'status' => 'boolean',
        'progress_percent' => 'integer',

        'completed_video_ids' => 'array',
        'completed_pdf_ids' => 'array',
        'completed_quiz_ids' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasCompletedVideo(int $videoId): bool
    {
        return in_array(
            $videoId,
            array_map('intval', $this->completed_video_ids ?? []),
            true
        );
    }

    public function hasCompletedPdf(int $pdfId): bool
    {
        return in_array(
            $pdfId,
            array_map('intval', $this->completed_pdf_ids ?? []),
            true
        );
    }

    public function hasCompletedQuiz(int $quizId): bool
    {
        return in_array(
            $quizId,
            array_map('intval', $this->completed_quiz_ids ?? []),
            true
        );
    }
}
