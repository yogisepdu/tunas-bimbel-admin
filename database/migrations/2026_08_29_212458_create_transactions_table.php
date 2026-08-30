<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Identitas Invoice
            |--------------------------------------------------------------------------
            */
            $table->string('invoice_no', 50)->unique();

            // Token public agar URL pembayaran tidak menggunakan ID berurutan.
            $table->uuid('public_token')->unique();

            /*
            |--------------------------------------------------------------------------
            | Relasi
            |--------------------------------------------------------------------------
            |
            | Dibuat nullable agar histori transaksi tidak hilang bila suatu saat
            | user / package / metode pembayaran dihapus.
            |
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('packages')
                ->nullOnDelete();

            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Snapshot Paket
            |--------------------------------------------------------------------------
            |
            | Jangan hanya bergantung pada packages karena harga/nama paket
            | dapat berubah setelah transaksi terjadi.
            |
            */
            $table->string('package_name', 150);

            $table->decimal(
                'package_base_price',
                15,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | Periode
            |--------------------------------------------------------------------------
            */
            $table->string('billing', 20);

            // monthly = 1
            // yearly  = 12
            $table->unsignedSmallInteger(
                'duration_months'
            )->default(1);

            /*
            |--------------------------------------------------------------------------
            | Harga
            |--------------------------------------------------------------------------
            */
            $table->decimal(
                'subtotal',
                15,
                2
            )->default(0);

            $table->decimal(
                'discount',
                15,
                2
            )->default(0);

            $table->decimal(
                'total',
                15,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | Snapshot Data Pembeli
            |--------------------------------------------------------------------------
            */
            $table->string('customer_name', 150);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 30);

            /*
            |--------------------------------------------------------------------------
            | Snapshot Metode Pembayaran
            |--------------------------------------------------------------------------
            |
            | Supaya invoice lama tetap menyimpan rekening/metode yang
            | digunakan ketika transaksi dibuat.
            |
            */
            $table->string(
                'payment_method_name',
                150
            )->nullable();

            $table->string(
                'payment_provider',
                100
            )->nullable();

            $table->string(
                'payment_account_name',
                150
            )->nullable();

            $table->string(
                'payment_account_number',
                150
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Mode Pembayaran
            |--------------------------------------------------------------------------
            */
            $table->string(
                'payment_mode',
                20
            )->default('manual');

            /*
            |--------------------------------------------------------------------------
            | Payment Gateway
            |--------------------------------------------------------------------------
            |
            | Belum digunakan sekarang, tetapi sudah disiapkan agar nanti
            | Midtrans/Xendit tidak membutuhkan perubahan struktur besar.
            |
            */
            $table->string(
                'gateway_provider',
                50
            )->nullable();

            $table->string(
                'gateway_reference',
                150
            )->nullable();

            $table->json(
                'gateway_payload'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | pending_payment
            | waiting_verification
            | paid
            | rejected
            | cancelled
            | expired
            |
            */
            $table->string(
                'status',
                40
            )->default('pending_payment');

            /*
            |--------------------------------------------------------------------------
            | Bukti Pembayaran
            |--------------------------------------------------------------------------
            */
            $table->string(
                'proof_path'
            )->nullable();

            $table->string(
                'proof_original_name'
            )->nullable();

            $table->string(
                'proof_mime_type',
                100
            )->nullable();

            $table->timestamp(
                'proof_uploaded_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Verifikasi Admin
            |--------------------------------------------------------------------------
            */
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp(
                'reviewed_at'
            )->nullable();

            $table->text(
                'rejection_reason'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Waktu Pembayaran
            |--------------------------------------------------------------------------
            */
            $table->timestamp(
                'paid_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Kadaluarsa Invoice
            |--------------------------------------------------------------------------
            |
            | Berbeda dengan expires_at pada user_packages.
            | Ini adalah batas waktu pembayaran invoice.
            |
            */
            $table->timestamp(
                'expires_at'
            )->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */
            $table->index('status');

            $table->index([
                'user_id',
                'status'
            ]);

            $table->index([
                'package_id',
                'status'
            ]);

            $table->index([
                'payment_method_id',
                'status'
            ]);

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
