<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            // Identitas metode pembayaran
            $table->string('name', 100);
            $table->string('type', 30);
            $table->string('provider', 100)->nullable();

            // Data rekening / e-wallet
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 100)->nullable();

            // Untuk QRIS atau gambar petunjuk pembayaran
            $table->string('qr_image')->nullable();

            // Instruksi tambahan
            $table->text('instructions')->nullable();

            // Manual / payment gateway
            $table->string('mode', 20)->default('manual');

            // Contoh nantinya: midtrans / xendit
            $table->string('gateway_provider', 50)->nullable();

            // Pengaturan
            $table->boolean('requires_proof')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
