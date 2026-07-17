<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->where('status', 'aktif');

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $produks   = $query->latest('created_at')->paginate(12)->withQueryString();
        $kategoris = Kategori::all();

        return view('pelanggan.home', compact('produks', 'kategoris'));
    }

    public function detail($id)
    {
        $produk    = Produk::with('kategori')->where('status', 'aktif')->findOrFail($id);
        $related   = Produk::with('kategori')
                        ->where('id_kategori', $produk->id_kategori)
                        ->where('id_produk', '!=', $produk->id_produk)
                        ->where('status', 'aktif')
                        ->limit(4)
                        ->get();

        return view('pelanggan.produk.detail', compact('produk', 'related'));
    }
}
