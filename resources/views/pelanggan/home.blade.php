@extends('layouts.app')

@section('title', 'Beranda — Kantin Biru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Hero --}}
    <div class="bg-gradient-to-r from-blue-700 to-blue-900 rounded-2xl text-white p-8 mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang di Kantin Biru! 🍽️</h1>
            <p class="text-blue-100 mb-4">Temukan makanan & minuman lezat favorit kamu</p>
            <a href="#produk" class="bg-white text-blue-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-50 transition inline-block">
                Belanja Sekarang
            </a>
        </div>
        <div class="hidden md:block text-8xl opacity-30">
            <i class="fas fa-utensils"></i>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div id="produk" class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('pelanggan.home') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-600 mb-1">Cari Produk</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Nama produk...">
                </div>
            </div>
            <div class="min-w-40">
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                <select name="kategori" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-filter mr-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','kategori']))
                <a href="{{ route('pelanggan.home') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-1"></i>Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Produk Grid --}}
    @if($produks->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-search text-5xl mb-4 text-gray-300"></i>
            <p class="text-lg">Produk tidak ditemukan.</p>
            <a href="{{ route('pelanggan.home') }}" class="text-blue-600 hover:underline mt-2 inline-block">Lihat semua produk</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
            @foreach($produks as $produk)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group">
                    {{-- Gambar --}}
                    <a href="{{ route('produk.detail', $produk->id_produk) }}">
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            @if($produk->foto)
                                <img src="{{ asset('storage/' . $produk->foto) }}"
                                     alt="{{ $produk->nama_produk }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                    </a>

                    <div class="p-3">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            {{ $produk->kategori->nama_kategori ?? '-' }}
                        </span>
                        <a href="{{ route('produk.detail', $produk->id_produk) }}">
                            <h3 class="font-semibold text-gray-800 mt-1.5 text-sm line-clamp-2 hover:text-blue-700">
                                {{ $produk->nama_produk }}
                            </h3>
                        </a>
                        <p class="text-blue-700 font-bold mt-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Stok: {{ $produk->stok }}</p>

                        @if($produk->stok > 0)
                            @auth
                                @if(auth()->user()->isPelanggan())
                                    <form method="POST" action="{{ route('keranjang.tambah') }}" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <button type="submit" class="w-full bg-blue-700 text-white text-xs py-1.5 rounded-lg hover:bg-blue-800 transition">
                                            <i class="fas fa-cart-plus mr-1"></i>Tambah
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center bg-blue-700 text-white text-xs py-1.5 rounded-lg hover:bg-blue-800 transition mt-2">
                                    <i class="fas fa-cart-plus mr-1"></i>Tambah
                                </a>
                            @endauth
                        @else
                            <span class="block w-full text-center bg-gray-200 text-gray-500 text-xs py-1.5 rounded-lg mt-2">Habis</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $produks->links() }}
        </div>
    @endif

</div>
@endsection
