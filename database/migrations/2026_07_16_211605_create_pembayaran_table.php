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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran'); // Menggunakan id_pembayaran sebagai Primary Key
            
            // Relasi ke tabel orders (Foreign Key)
            $table->foreignId('id_order')
                  ->constrained('orders', 'id_order')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->string('bukti_pembayaran');
            $table->string('bank_asal')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->enum('status_konfirmasi', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};