<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        /*
        |--------------------------------------------------------------------------
        | PostgreSQL
        |--------------------------------------------------------------------------
        */
        if ($driver === 'pgsql') {
            DB::statement(
                'ALTER TABLE users
                 DROP CONSTRAINT IF EXISTS users_role_check'
            );

            DB::statement(
                "ALTER TABLE users
                 ALTER COLUMN role SET DEFAULT 'student'"
            );

            DB::statement(
                "ALTER TABLE users
                 ADD CONSTRAINT users_role_check
                 CHECK (
                    role IN (
                        'administrator',
                        'admin',
                        'teacher',
                        'student'
                    )
                 )"
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MySQL/MariaDB
        |--------------------------------------------------------------------------
        */
        if ($driver === 'mysql') {
            DB::statement(
                "ALTER TABLE users
                 MODIFY role ENUM(
                    'administrator',
                    'admin',
                    'teacher',
                    'student'
                 )
                 NOT NULL DEFAULT 'student'"
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Database lainnya
        |--------------------------------------------------------------------------
        */
        Schema::table('users', function ($table) {
            $table->string('role', 30)
                ->default('student')
                ->change();
        });
    }

    public function down(): void
    {
        /*
        | Administrator dikembalikan menjadi admin sebelum
        | constraint lama diterapkan.
        */
        DB::table('users')
            ->where('role', 'administrator')
            ->update([
                'role' => 'admin',
            ]);

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement(
                'ALTER TABLE users
                 DROP CONSTRAINT IF EXISTS users_role_check'
            );

            DB::statement(
                "ALTER TABLE users
                 ADD CONSTRAINT users_role_check
                 CHECK (
                    role IN (
                        'admin',
                        'teacher',
                        'student'
                    )
                 )"
            );

            return;
        }

        if ($driver === 'mysql') {
            DB::statement(
                "ALTER TABLE users
                 MODIFY role ENUM(
                    'admin',
                    'teacher',
                    'student'
                 )
                 NOT NULL DEFAULT 'student'"
            );
        }
    }
};
