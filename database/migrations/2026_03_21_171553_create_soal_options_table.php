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
        Schema::create('soal_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_question_id')->constrained()->cascadeOnDelete();

            $table->string('key'); // A, B, C, D
            $table->text('text');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_options');
    }
};
