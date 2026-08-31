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
        Schema::create(
            'soal_attempts',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | ATTEMPT TOKEN
                |--------------------------------------------------------------------------
                */

                $table
                    ->uuid('token')
                    ->unique();

                /*
                |--------------------------------------------------------------------------
                | STUDENT
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | SOAL SET / TRYOUT
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'soal_set_id'
                    )
                    ->constrained(
                        'soal_sets'
                    )
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                |
                | active
                | submitted
                | expired
                |
                */

                $table
                    ->string(
                        'status',
                        20
                    )
                    ->default('active');

                /*
                |--------------------------------------------------------------------------
                | TIMER SERVER
                |--------------------------------------------------------------------------
                */

                $table->timestamp(
                    'started_at'
                );

                $table->timestamp(
                    'expires_at'
                );

                /*
                |--------------------------------------------------------------------------
                | SUBMITTED
                |--------------------------------------------------------------------------
                */

                $table
                    ->timestamp(
                        'submitted_at'
                    )
                    ->nullable();

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | INDEX
                |--------------------------------------------------------------------------
                */

                $table->index([
                    'user_id',
                    'soal_set_id',
                    'status',
                ]);

                $table->index(
                    'expires_at'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'soal_attempts'
        );
    }
};
