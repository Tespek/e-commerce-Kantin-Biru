<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->input('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));

        $orders = Order::with(['user', 'details.produk', 'pembayaran'])
                    ->where('status', 'selesai')
                    ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
                    ->latest('created_at')
                    ->get();

        $totalPendapatan = $orders->sum('total_harga');
        $totalOrder      = $orders->count();

        $produkTerlaris = OrderDetail::join('orders', 'order_details.id_order', '=', 'orders.id_order')
                            ->join('produk', 'order_details.id_produk', '=', 'produk.id_produk')
                            ->where('orders.status', 'selesai')
                            ->whereBetween('orders.created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
                            ->select(
                                'produk.nama_produk',
                                DB::raw('SUM(order_details.jumlah) as total_terjual'),
                                DB::raw('SUM(order_details.subtotal) as total_pendapatan')
                            )
                            ->groupBy('produk.id_produk', 'produk.nama_produk')
                            ->orderByDesc('total_terjual')
                            ->limit(10)
                            ->get();

        $grafikHarian = Order::where('status', 'selesai')
                            ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
                            ->select(
                                DB::raw('DATE(created_at) as tanggal'),
                                DB::raw('SUM(total_harga) as total'),
                                DB::raw('COUNT(*) as jumlah_order')
                            )
                            ->groupBy('tanggal')
                            ->orderBy('tanggal')
                            ->get();

        return view('admin.laporan.index', compact(
            'orders',
            'totalPendapatan',
            'totalOrder',
            'produkTerlaris',
            'grafikHarian',
            'dari',
            'sampai'
        ));
    }
}
