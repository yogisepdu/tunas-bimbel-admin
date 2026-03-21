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
        Schema::create('soal_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_section_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->integer('total_questions')->default(0);
            $table->integer('duration'); // menit
            $table->integer('points')->default(0);
            $table->string('badge')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_sets');
    }
};
