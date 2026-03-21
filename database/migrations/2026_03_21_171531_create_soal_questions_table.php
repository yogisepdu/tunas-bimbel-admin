<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('soal_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_set_id')->constrained()->cascadeOnDelete();

            $table->text('question');
            $table->string('correct_answer'); // A, B, C, D

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_questions');
    }
};
