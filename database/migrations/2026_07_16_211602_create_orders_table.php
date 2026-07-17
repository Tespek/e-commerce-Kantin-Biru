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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order'); // Menggunakan id_order sebagai Primary Key
            
            // Relasi ke tabel users (Foreign Key)
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->integer('total_harga');
            $table->enum('status_pesanan', ['pending', 'diproses', 'selesai', 'dibatalkan'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};