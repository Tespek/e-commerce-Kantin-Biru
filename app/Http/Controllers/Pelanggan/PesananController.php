<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    public function index()
    {
        $orders = Order::with(['details.produk', 'pembayaran'])
                    ->where('id_user', Auth::id())
                    ->latest('created_at')
                    ->paginate(10);

        return view('pelanggan.pesanan.index', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with(['details.produk', 'pembayaran', 'user'])
                    ->where('id_user', Auth::id())
                    ->findOrFail($id);

        return view('pelanggan.pesanan.detail', compact('order'));
    }

    public function uploadBukti($id)
    {
        $order = Order::with('pembayaran')
                    ->where('id_user', Auth::id())
                    ->findOrFail($id);

        if (!$order->pembayaran) {
            return redirect()->route('pesanan.index')->with('info', 'Pesanan ini tidak memerlukan upload bukti.');
        }

        return view('pelanggan.pesanan.upload-bukti', compact('order'));
    }

    public function simpanBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image'    => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes'    => 'Format gambar harus jpg, jpeg, atau png.',
            'bukti_pembayaran.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $order = Order::with('pembayaran')
                    ->where('id_user', Auth::id())
                    ->findOrFail($id);

        $pembayaran = $order->pembayaran;

        // Hapus bukti lama jika ada
        if ($pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $pembayaran->update([
            'bukti_pembayaran' => $path,
            'tanggal_bayar'    => now(),
            'status'           => 'menunggu',
        ]);

        return redirect()->route('pesanan.detail', $id)
                         ->with('success', 'Bukti pembayaran berhasil diupload.');
    }
}
