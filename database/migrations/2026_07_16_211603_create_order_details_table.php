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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('id_detail'); // Menggunakan id_detail sebagai Primary Key
            
            // Relasi ke tabel orders (Foreign Key)
            $table->foreignId('id_order')
                  ->constrained('orders', 'id_order')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            // Relasi ke tabel produk (Foreign Key)
            $table->foreignId('id_produk')
                  ->constrained('produk', 'id_produk')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
                  
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};