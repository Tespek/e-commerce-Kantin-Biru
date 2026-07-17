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
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_keranjang'); // Menggunakan id_keranjang sebagai Primary Key
            
            // Relasi ke tabel users (Foreign Key)
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            // Relasi ke tabel produk (Foreign Key)
            $table->foreignId('id_produk')
                  ->constrained('produk', 'id_produk')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};