<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_learning_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('user_learning_progress', 'completed_video_ids')) {
                $table->jsonb('completed_video_ids')->default('[]');
            }

            if (!Schema::hasColumn('user_learning_progress', 'completed_pdf_ids')) {
                $table->jsonb('completed_pdf_ids')->default('[]');
            }

            if (!Schema::hasColumn('user_learning_progress', 'completed_quiz_ids')) {
                $table->jsonb('completed_quiz_ids')->default('[]');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_learning_progress', function (Blueprint $table) {
            if (Schema::hasColumn('user_learning_progress', 'completed_video_ids')) {
                $table->dropColumn('completed_video_ids');
            }

            if (Schema::hasColumn('user_learning_progress', 'completed_pdf_ids')) {
                $table->dropColumn('completed_pdf_ids');
            }

            if (Schema::hasColumn('user_learning_progress', 'completed_quiz_ids')) {
                $table->dropColumn('completed_quiz_ids');
            }
        });
    }
};
