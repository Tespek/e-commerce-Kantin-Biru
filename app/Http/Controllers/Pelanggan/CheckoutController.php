<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('produk')
                    ->where('id_user', Auth::id())
                    ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        }

        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);
        $user  = Auth::user();

        return view('pelanggan.checkout.index', compact('items', 'total', 'user'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string',
            'metode_pembayaran' => 'required|in:Transfer Bank,QRIS,COD',
        ], [
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $items = Keranjang::with('produk')
                    ->where('id_user', Auth::id())
                    ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        }

        // Cek stok
        foreach ($items as $item) {
            if ($item->produk->stok < $item->jumlah) {
                return back()->with('error', "Stok produk '{$item->produk->nama_produk}' tidak mencukupi.");
            }
        }

        DB::transaction(function () use ($request, $items) {
            $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

            $order = Order::create([
                'id_user'           => Auth::id(),
                'tanggal_order'     => now(),
                'total_harga'       => $total,
                'status'            => 'pending',
                'alamat_pengiriman' => $request->alamat_pengiriman,
            ]);

            foreach ($items as $item) {
                OrderDetail::create([
                    'id_order'  => $order->id_order,
                    'id_produk' => $item->id_produk,
                    'jumlah'    => $item->jumlah,
                    'harga'     => $item->produk->harga,
                    'subtotal'  => $item->produk->harga * $item->jumlah,
                ]);

                // Kurangi stok
                $item->produk->decrement('stok', $item->jumlah);
            }

            Pembayaran::create([
                'id_order' => $order->id_order,
                'metode'   => $request->metode_pembayaran,
                'status'   => 'menunggu',
            ]);

            // Hapus keranjang
            Keranjang::where('id_user', Auth::id())->delete();

            session(['last_order_id' => $order->id_order]);
        });

        return redirect()->route('pesanan.upload-bukti', session('last_order_id'))
                         ->with('success', 'Pesanan berhasil dibuat!');
    }
}
