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
        if (
            ! Schema::hasColumn(
                'materi_pdfs',
                'storage_type'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table
                        ->string(
                            'storage_type',
                            30
                        )
                        ->default(
                            'external_url'
                        );
                }
            );
        }

        if (
            ! Schema::hasColumn(
                'materi_pdfs',
                'file_mime_type'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table
                        ->string(
                            'file_mime_type',
                            100
                        )
                        ->nullable();
                }
            );
        }

        if (
            ! Schema::hasColumn(
                'materi_pdfs',
                'file_size'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table
                        ->unsignedBigInteger(
                            'file_size'
                        )
                        ->nullable();
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Data lama
        |--------------------------------------------------------------------------
        |
        | PDF lama dianggap sebagai URL/file legacy.
        |
        */

        DB::table('materi_pdfs')
            ->whereNull('storage_type')
            ->update([
                'storage_type' =>
                'external_url',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasColumn(
                'materi_pdfs',
                'file_size'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'file_size'
                    );
                }
            );
        }

        if (
            Schema::hasColumn(
                'materi_pdfs',
                'file_mime_type'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'file_mime_type'
                    );
                }
            );
        }

        if (
            Schema::hasColumn(
                'materi_pdfs',
                'storage_type'
            )
        ) {
            Schema::table(
                'materi_pdfs',
                function (Blueprint $table) {
                    $table->dropColumn(
                        'storage_type'
                    );
                }
            );
        }
    }
};
