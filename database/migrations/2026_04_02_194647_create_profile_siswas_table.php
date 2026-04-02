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
        Schema::create('profile_siswas', function (Blueprint $table) {
            $table->id();

            // 🔗 RELASI KE USERS
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // DATA PROFIL
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['pria', 'wanita'])->nullable();

            $table->string('province_id')->nullable();
            $table->string('regency_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('village_id')->nullable();

            $table->string('postal_code')->nullable();
            $table->string('avatar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_siswas');
    }
};
