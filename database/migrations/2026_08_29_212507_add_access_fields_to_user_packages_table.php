<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_packages', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Transaksi terakhir yang mengaktifkan / memperpanjang paket
            |--------------------------------------------------------------------------
            */
            $table->foreignId('transaction_id')
                ->nullable()
                ->after('package_id')
                ->constrained('transactions')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status Entitlement
            |--------------------------------------------------------------------------
            |
            | active
            | expired
            | cancelled
            |
            */
            $table->string(
                'status',
                20
            )
                ->default('active')
                ->after('transaction_id');

            /*
            |--------------------------------------------------------------------------
            | Masa Aktif
            |--------------------------------------------------------------------------
            */
            $table->timestamp(
                'activated_at'
            )
                ->nullable()
                ->after('status');

            $table->timestamp(
                'expires_at'
            )
                ->nullable()
                ->after('activated_at');

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */
            $table->index([
                'user_id',
                'status'
            ]);

            $table->index([
                'status',
                'expires_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropIndex([
                'user_id',
                'status'
            ]);

            $table->dropIndex([
                'status',
                'expires_at'
            ]);

            $table->dropForeign([
                'transaction_id'
            ]);

            $table->dropColumn([
                'transaction_id',
                'status',
                'activated_at',
                'expires_at',
            ]);
        });
    }
};
