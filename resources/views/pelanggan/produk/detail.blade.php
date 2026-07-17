@extends('layouts.app')

@section('title', $produk->nama_produk . ' — Kantin Biru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('pelanggan.home') }}" class="hover:text-blue-700">Beranda</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">{{ $produk->nama_produk }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="md:flex">
            {{-- Gambar --}}
            <div class="md:w-2/5 bg-gray-100 flex items-center justify-center p-6">
                @if($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}"
                         alt="{{ $produk->nama_produk }}"
                         class="max-h-96 object-contain rounded-xl">
                @else
                    <div class="w-full h-64 flex items-center justify-center text-gray-300">
                        <i class="fas fa-image text-8xl"></i>
                    </div>
                @endif
            </div>

            {{-- Info Produk --}}
            <div class="md:w-3/5 p-8">
                <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">
                    {{ $produk->kategori->nama_kategori ?? '-' }}
                </span>

                <h1 class="text-2xl font-bold text-gray-800 mt-3">{{ $produk->nama_produk }}</h1>
                <p class="text-3xl font-bold text-blue-700 mt-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                <div class="flex items-center gap-2 mt-3">
                    @if($produk->stok > 0)
                        <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia ({{ $produk->stok }} stok)
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>Stok Habis
                        </span>
                    @endif
                </div>

                @if($produk->deskripsi)
                    <div class="mt-4 text-gray-600 leading-relaxed">
                        <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p>{{ $produk->deskripsi }}</p>
                    </div>
                @endif

                {{-- Tambah ke Keranjang --}}
                @if($produk->stok > 0)
                    @auth
                        @if(auth()->user()->isPelanggan())
                            <form method="POST" action="{{ route('keranjang.tambah') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" onclick="changeQty(-1)"
                                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">−</button>
                                        <input type="number" id="jumlah" name="jumlah" value="1" min="1" max="{{ $produk->stok }}"
                                            class="w-16 text-center py-2 border-0 outline-none">
                                        <button type="button" onclick="changeQty(1)"
                                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">+</button>
                                    </div>
                                    <button type="submit"
                                        class="flex-1 bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-800 transition">
                                        <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                                    </button>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="mt-6">
                            <a href="{{ route('login') }}"
                               class="inline-block bg-blue-700 text-white px-8 py-2.5 rounded-lg font-semibold hover:bg-blue-800 transition">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk untuk Memesan
                            </a>
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </div>

    {{-- Produk Terkait --}}
    @if($related->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Produk Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($related as $item)
                    <a href="{{ route('produk.detail', $item->id_produk) }}"
                       class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group">
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}"
                                     alt="{{ $item->nama_produk }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-200">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm text-gray-800 line-clamp-2 group-hover:text-blue-700">{{ $item->nama_produk }}</h3>
                            <p class="text-blue-700 font-bold text-sm mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('jumlah');
    const max   = parseInt(input.max);
    let val     = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>
@endpush
@endsection
