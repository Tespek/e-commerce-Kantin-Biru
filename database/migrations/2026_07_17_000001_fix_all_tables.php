<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. PRODUK: tambah kolom status ───────────────────────────────────
        if (!Schema::hasColumn('produk', 'status')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('foto');
            });
        }

        // ── 2. ORDERS: sesuaikan kolom ───────────────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom yang belum ada
            if (!Schema::hasColumn('orders', 'tanggal_order')) {
                $table->datetime('tanggal_order')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->after('id_user');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['pending','dibayar','diproses','selesai','dibatalkan'])->default('pending')->after('total_harga');
            }
            if (!Schema::hasColumn('orders', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('status');
            }
        });

        // Hapus kolom status_pesanan yang tidak dipakai aplikasi
        if (Schema::hasColumn('orders', 'status_pesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status_pesanan');
            });
        }

        // ── 3. ORDER_DETAILS: sesuaikan kolom ────────────────────────────────
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'harga')) {
                $table->decimal('harga', 10, 2)->after('jumlah');
            }
            if (!Schema::hasColumn('order_details', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->after('harga');
            }
        });

        // Hapus kolom harga_satuan yang tidak dipakai aplikasi
        if (Schema::hasColumn('order_details', 'harga_satuan')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('harga_satuan');
            });
        }

        // ── 4. PEMBAYARAN: sesuaikan kolom ───────────────────────────────────
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'metode')) {
                $table->enum('metode', ['Transfer Bank', 'QRIS', 'COD'])->after('id_order');
            }
            if (!Schema::hasColumn('pembayaran', 'tanggal_bayar')) {
                $table->datetime('tanggal_bayar')->nullable()->after('bukti_pembayaran');
            }
            if (!Schema::hasColumn('pembayaran', 'status')) {
                $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu')->after('tanggal_bayar');
            }
        });

        // Hapus kolom yang tidak dipakai aplikasi
        Schema::table('pembayaran', function (Blueprint $table) {
            $dropCols = [];
            if (Schema::hasColumn('pembayaran', 'status_konfirmasi')) $dropCols[] = 'status_konfirmasi';
            if (Schema::hasColumn('pembayaran', 'bank_asal'))         $dropCols[] = 'bank_asal';
            if (Schema::hasColumn('pembayaran', 'nama_rekening'))     $dropCols[] = 'nama_rekening';
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });

        // Pastikan bukti_pembayaran nullable (bisa kosong sebelum upload)
        if (Schema::hasColumn('pembayaran', 'bukti_pembayaran')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->string('bukti_pembayaran')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Revert produk
        if (Schema::hasColumn('produk', 'status')) {
            Schema::table('produk', fn(Blueprint $t) => $t->dropColumn('status'));
        }

        // Revert orders
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'tanggal_order'))     $table->dropColumn('tanggal_order');
            if (Schema::hasColumn('orders', 'status'))            $table->dropColumn('status');
            if (Schema::hasColumn('orders', 'alamat_pengiriman')) $table->dropColumn('alamat_pengiriman');
            $table->enum('status_pesanan', ['pending','diproses','selesai','dibatalkan'])->default('pending');
        });

        // Revert order_details
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'harga'))    $table->dropColumn('harga');
            if (Schema::hasColumn('order_details', 'subtotal')) $table->dropColumn('subtotal');
            $table->integer('harga_satuan');
        });

        // Revert pembayaran
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'metode'))       $table->dropColumn('metode');
            if (Schema::hasColumn('pembayaran', 'tanggal_bayar'))$table->dropColumn('tanggal_bayar');
            if (Schema::hasColumn('pembayaran', 'status'))       $table->dropColumn('status');
            $table->enum('status_konfirmasi', ['pending','valid','ditolak'])->default('pending');
            $table->string('bank_asal')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('bukti_pembayaran')->nullable(false)->change();
        });
    }
};
