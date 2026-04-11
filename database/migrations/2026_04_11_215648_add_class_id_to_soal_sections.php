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
        Schema::table('soal_sections', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('class_id')->after('id');

            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_sections', function (Blueprint $table) {
            //
        });
    }
};
