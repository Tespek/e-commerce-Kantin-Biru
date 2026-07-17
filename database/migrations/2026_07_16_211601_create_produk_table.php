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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk'); // Menggunakan id_produk sebagai Primary Key
            
            // Relasi ke tabel kategori (Foreign Key)
            $table->foreignId('id_kategori')
                  ->nullable()
                  ->constrained('kategori', 'id_kategori')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
            $table->string('nama_produk', 100);
            $table->integer('harga');
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};