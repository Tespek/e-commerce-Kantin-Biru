<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Drop constraint lama (RESTRICT)
            $table->dropForeign(['id_produk']);

            // Jadikan nullable agar bisa SET NULL saat produk dihapus
            $table->foreignId('id_produk')
                  ->nullable()
                  ->change();

            // Pasang ulang dengan ON DELETE SET NULL
            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['id_produk']);

            $table->foreignId('id_produk')
                  ->nullable(false)
                  ->change();

            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produk')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }
};
