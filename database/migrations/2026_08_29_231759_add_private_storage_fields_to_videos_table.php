<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SOURCE TYPE
        |--------------------------------------------------------------------------
        |
        | youtube
        | private_file
        |
        */

        if (
            ! Schema::hasColumn(
                'videos',
                'source_type'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table
                        ->string(
                            'source_type',
                            30
                        )
                        ->default(
                            'youtube'
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRIVATE VIDEO PATH
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'videos',
                'video_path'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table
                        ->string(
                            'video_path'
                        )
                        ->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        if (
            ! Schema::hasColumn(
                'videos',
                'video_mime_type'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table
                        ->string(
                            'video_mime_type',
                            100
                        )
                        ->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILE SIZE
        |--------------------------------------------------------------------------
        |
        | Nilai disimpan dalam byte.
        |
        */

        if (
            ! Schema::hasColumn(
                'videos',
                'video_size'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table
                        ->unsignedBigInteger(
                            'video_size'
                        )
                        ->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA VIDEO LAMA
        |--------------------------------------------------------------------------
        |
        | Semua video lama yang menggunakan youtube_id
        | dianggap sebagai video YouTube.
        |
        */

        DB::table('videos')
            ->whereNull('source_type')
            ->update([
                'source_type' =>
                'youtube',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasColumn(
                'videos',
                'video_size'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'video_size'
                    );
                }
            );
        }

        if (
            Schema::hasColumn(
                'videos',
                'video_mime_type'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'video_mime_type'
                    );
                }
            );
        }

        if (
            Schema::hasColumn(
                'videos',
                'video_path'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'video_path'
                    );
                }
            );
        }

        if (
            Schema::hasColumn(
                'videos',
                'source_type'
            )
        ) {
            Schema::table(
                'videos',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'source_type'
                    );
                }
            );
        }
    }
};
