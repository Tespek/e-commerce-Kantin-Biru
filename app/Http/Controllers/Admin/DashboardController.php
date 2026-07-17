<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_harga');
        $totalOrder      = Order::count();
        $totalProduk     = Produk::count();
        $totalPelanggan  = User::where('role', 'pelanggan')->count();

        $orderTerbaru = Order::with(['user', 'pembayaran'])
                            ->latest('created_at')
                            ->limit(5)
                            ->get();

        $produkTerlaris = DB::table('order_details')
                            ->join('produk', 'order_details.id_produk', '=', 'produk.id_produk')
                            ->select('produk.nama_produk', DB::raw('SUM(order_details.jumlah) as total_terjual'))
                            ->groupBy('produk.id_produk', 'produk.nama_produk')
                            ->orderByDesc('total_terjual')
                            ->limit(5)
                            ->get();

        // Data grafik penjualan 7 hari terakhir
        $grafikPenjualan = Order::where('status', 'selesai')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->select(
                                DB::raw('DATE(created_at) as tanggal'),
                                DB::raw('SUM(total_harga) as total')
                            )
                            ->groupBy('tanggal')
                            ->orderBy('tanggal')
                            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan',
            'totalOrder',
            'totalProduk',
            'totalPelanggan',
            'orderTerbaru',
            'produkTerlaris',
            'grafikPenjualan'
        ));
    }
}
