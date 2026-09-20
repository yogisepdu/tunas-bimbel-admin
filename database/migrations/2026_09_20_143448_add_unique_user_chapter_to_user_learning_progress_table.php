<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Gabungkan duplicate progress terlebih dahulu.
         *
         * Ini penting karena tabel mungkin sudah
         * memiliki data lama.
         */
        $duplicates = DB::table(
            'user_learning_progress'
        )
            ->select(
                'user_id',
                'chapter_id',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(
                'user_id',
                'chapter_id'
            )
            ->having(
                DB::raw('COUNT(*)'),
                '>',
                1
            )
            ->get();

        foreach ($duplicates as $duplicate) {
            $rows = DB::table(
                'user_learning_progress'
            )
                ->where(
                    'user_id',
                    $duplicate->user_id
                )
                ->where(
                    'chapter_id',
                    $duplicate->chapter_id
                )
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $keep = $rows->first();

            $videoId =
                $rows->firstWhere(
                    fn($row) =>
                    !empty($row->video_id)
                )?->video_id
                ?? $keep->video_id;

            $pdfId =
                $rows->firstWhere(
                    fn($row) =>
                    !empty($row->pdf_id)
                )?->pdf_id
                ?? $keep->pdf_id;

            $quizId =
                $rows->firstWhere(
                    fn($row) =>
                    !empty($row->quiz_id)
                )?->quiz_id
                ?? $keep->quiz_id;

            $done = 0;

            if ($videoId) {
                $done++;
            }

            if ($pdfId) {
                $done++;
            }

            if ($quizId) {
                $done++;
            }

            $percent = (int) round(
                ($done / 3) * 100
            );

            DB::table(
                'user_learning_progress'
            )
                ->where('id', $keep->id)
                ->update([
                    'video_id' =>
                    $videoId,

                    'pdf_id' =>
                    $pdfId,

                    'quiz_id' =>
                    $quizId,

                    'progress_percent' =>
                    $percent,

                    'status' =>
                    $percent >= 100,

                    'updated_at' =>
                    now(),
                ]);

            $deleteIds = $rows
                ->skip(1)
                ->pluck('id');

            if ($deleteIds->isNotEmpty()) {
                DB::table(
                    'user_learning_progress'
                )
                    ->whereIn(
                        'id',
                        $deleteIds
                    )
                    ->delete();
            }
        }

        /*
         * Satu user + satu chapter = satu row.
         */
        Schema::table(
            'user_learning_progress',
            function (Blueprint $table) {
                $table->unique(
                    [
                        'user_id',
                        'chapter_id',
                    ],
                    'user_learning_progress_user_chapter_unique'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'user_learning_progress',
            function (Blueprint $table) {
                $table->dropUnique(
                    'user_learning_progress_user_chapter_unique'
                );
            }
        );
    }
};
