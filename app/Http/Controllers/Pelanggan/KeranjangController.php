<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('produk.kategori')
                    ->where('id_user', Auth::id())
                    ->get();

        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        return view('pelanggan.keranjang.index', compact('items', 'total'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->id_produk);

        if ($produk->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $keranjang = Keranjang::where('id_user', Auth::id())
                        ->where('id_produk', $request->id_produk)
                        ->first();

        if ($keranjang) {
            $newJumlah = $keranjang->jumlah + $request->jumlah;
            if ($newJumlah > $produk->stok) {
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
            }
            $keranjang->update(['jumlah' => $newJumlah]);
        } else {
            Keranjang::create([
                'id_user'  => Auth::id(),
                'id_produk' => $request->id_produk,
                'jumlah'   => $request->jumlah,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);

        $keranjang = Keranjang::where('id_keranjang', $id)
                        ->where('id_user', Auth::id())
                        ->firstOrFail();

        if ($keranjang->produk->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $keranjang->update(['jumlah' => $request->jumlah]);

        return back()->with('success', 'Jumlah berhasil diperbarui.');
    }

    public function hapus($id)
    {
        Keranjang::where('id_keranjang', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
