<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'pembayaran']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));
        }

        $orders = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.pesanan.index', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with(['user', 'details.produk', 'pembayaran'])->findOrFail($id);
        return view('admin.pesanan.detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,dibayar,diproses,selesai,dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function konfirmasiPembayaran($id)
    {
        $pembayaran = Pembayaran::with('order')->findOrFail($id);

        $pembayaran->update([
            'status'       => 'diterima',
            'tanggal_bayar' => now(),
        ]);

        $pembayaran->order->update(['status' => 'diproses']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function tolakPembayaran($id)
    {
        $pembayaran = Pembayaran::with('order')->findOrFail($id);

        $pembayaran->update(['status' => 'ditolak']);

        return back()->with('error', 'Pembayaran ditolak.');
    }
}
