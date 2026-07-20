@extends('layouts.app')

@section('title', 'Beranda — Kantin Biru')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 relative overflow-hidden">
    {{-- Decorative elements --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
    <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-accent-400/10 rounded-full -translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex-1 text-white">
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-medium mb-5 border border-white/20">
                    <span class="w-2 h-2 bg-accent-400 rounded-full animate-pulse"></span>
                    UMKM Kuliner Lhokseumawe sejak 2019
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                    Camilan Lezat,<br>
                    <span class="text-accent-400">Harga Terjangkau</span>
                </h1>
                <p class="text-blue-100 text-base md:text-lg leading-relaxed mb-8 max-w-lg">
                    Temukan berbagai pilihan camilan berkualitas — perpaduan cita rasa manis, asin, dan gurih yang cocok dinikmati kapan saja.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="#produk"
                       class="bg-accent-400 hover:bg-accent-500 text-slate-900 font-bold px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                        <i class="fas fa-store mr-2"></i>Belanja Sekarang
                    </a>
                    @guest
                    <a href="{{ route('register') }}"
                       class="bg-white/15 hover:bg-white/25 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 border border-white/30 text-sm">
                        Daftar Gratis
                    </a>
                    @endguest
                </div>

                {{-- Stats --}}
                <div class="flex gap-6 mt-10 pt-6 border-t border-white/15">
                    <div>
                        <p class="text-2xl font-bold">200+</p>
                        <p class="text-blue-200 text-xs">Pelanggan</p>
                    </div>
                    <div class="w-px bg-white/15"></div>
                    <div>
                        <p class="text-2xl font-bold text-accent-400">5★</p>
                        <p class="text-blue-200 text-xs">Rating</p>
                    </div>
                </div>
            </div>

            {{-- Hero Illustration --}}
            <div class="hidden md:flex flex-col items-center gap-4 shrink-0">
                <div class="grid grid-cols-2 gap-3">
                    <div class="w-28 h-28 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 hover:bg-white/20 transition-all duration-200 hover:-translate-y-1">
                        <div class="text-center">
                            <i class="fas fa-cookie-bite text-3xl text-accent-400 mb-1"></i>
                            <p class="text-white text-xs font-medium">Snack</p>
                        </div>
                    </div>

                    <div class="w-28 h-28 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 hover:bg-white/20 transition-all duration-200 hover:-translate-y-1">
                        <div class="text-center">
                            <i class="fas fa-bread-slice text-3xl text-accent-400 mb-1"></i>
                            <p class="text-white text-xs font-medium">Roti</p>
                        </div>
                    </div>
                    <div class="w-28 h-28 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 hover:bg-white/20 transition-all duration-200 hover:-translate-y-1 mt-4">
                        <div class="text-center">
                            <i class="fas fa-utensils text-3xl text-accent-400 mb-1"></i>
                            <p class="text-white text-xs font-medium">Makanan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== SEARCH & FILTER ===== --}}
<div id="produk" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-5">
        <form method="GET" action="{{ route('pelanggan.home') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                           placeholder="Cari produk camilan...">
                </div>

                {{-- Kategori --}}
                <div class="sm:w-52">
                    <select name="kategori"
                            class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150 cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 text-sm shadow-sm hover:shadow">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->hasAny(['search','kategori']))
                        <a href="{{ route('pelanggan.home') }}"
                           class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium px-4 py-3 rounded-xl transition-colors duration-150 text-sm">
                            <i class="fas fa-times"></i>
                            <span class="hidden sm:inline">Reset</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Active filters info --}}
            @if(request('search') || request('kategori'))
                <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                    <i class="fas fa-filter text-primary-600"></i>
                    <span>Filter aktif:</span>
                    @if(request('search'))
                        <span class="bg-primary-50 text-primary-700 border border-primary-200 px-2.5 py-1 rounded-full font-medium">
                            "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('kategori'))
                        <span class="bg-primary-50 text-primary-700 border border-primary-200 px-2.5 py-1 rounded-full font-medium">
                            {{ $kategoris->firstWhere('id_kategori', request('kategori'))?->nama_kategori }}
                        </span>
                    @endif
                </div>
            @endif
        </form>
    </div>
</div>

{{-- ===== PRODUK GRID ===== --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Section header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                @if(request('search') || request('kategori'))
                    Hasil Pencarian
                @else
                    Semua Produk
                @endif
            </h2>
            @if(!$produks->isEmpty())
                <p class="text-slate-500 text-sm mt-0.5">{{ $produks->total() }} produk tersedia</p>
            @endif
        </div>
        @if(!request()->hasAny(['search','kategori']))
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fas fa-fire text-orange-500"></i>
                <span>Produk Pilihan</span>
            </div>
        @endif
    </div>

    {{-- Empty State --}}
    @if($produks->isEmpty())
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-search text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Produk tidak ditemukan</h3>
            <p class="text-slate-400 text-sm mb-6">Coba gunakan kata kunci yang berbeda atau lihat semua produk</p>
            <a href="{{ route('pelanggan.home') }}"
               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fas fa-arrow-left"></i> Lihat Semua Produk
            </a>
        </div>

    @else
        {{-- Product Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($produks as $produk)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-card-hover border border-slate-100 overflow-hidden group transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    {{-- Product Image --}}
                    <a href="{{ route('produk.detail', $produk->id_produk) }}" class="block relative overflow-hidden bg-slate-100">
                        <div class="aspect-square overflow-hidden">
                            @if($produk->foto)
                                <img src="{{ asset('storage/' . $produk->foto) }}"
                                     alt="{{ $produk->nama_produk }}"
                                     class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500"
                                     style="transform-origin:center; transition: transform .5s ease;">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                    <i class="fas fa-image text-4xl text-slate-300"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Badges --}}
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            @if($produk->stok == 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">HABIS</span>
                            @elseif($produk->stok <= 5)
                                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">TERBATAS</span>
                            @endif
                        </div>
                    </a>

                    {{-- Product Info --}}
                    <div class="p-3.5 flex flex-col flex-1">
                        {{-- Kategori Badge --}}
                        <span class="inline-block text-[10px] bg-primary-50 text-primary-700 border border-primary-100 px-2 py-0.5 rounded-full font-medium mb-2 self-start">
                            {{ $produk->kategori->nama_kategori ?? '-' }}
                        </span>

                        {{-- Name --}}
                        <a href="{{ route('produk.detail', $produk->id_produk) }}" class="block flex-1">
                            <h3 class="font-semibold text-slate-800 text-sm leading-snug line-clamp-2 hover:text-primary-600 transition-colors duration-150 mb-2">
                                {{ $produk->nama_produk }}
                            </h3>
                        </a>

                        {{-- Price + Stock --}}
                        <div class="flex items-end justify-between mb-3">
                            <p class="text-primary-600 font-bold text-base leading-none">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </p>
                            @if($produk->stok > 5)
                                <span class="text-[10px] text-emerald-600 font-medium">Stok {{ $produk->stok }}</span>
                            @elseif($produk->stok > 0)
                                <span class="text-[10px] text-amber-600 font-medium">Sisa {{ $produk->stok }}</span>
                            @endif
                        </div>

                        {{-- Cart Button --}}
                        @if($produk->stok > 0)
                            @auth
                                @if(auth()->user()->isPelanggan())
                                    <form method="POST" action="{{ route('keranjang.tambah') }}">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <button type="submit"
                                                class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white text-xs font-semibold py-2.5 rounded-xl transition-all duration-200 hover:shadow-sm">
                                            <i class="fas fa-cart-plus mr-1.5"></i>Tambah ke Keranjang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold py-2.5 rounded-xl transition-all duration-200">
                                    <i class="fas fa-cart-plus mr-1.5"></i>Tambah ke Keranjang
                                </a>
                            @endauth
                        @else
                            <div class="w-full text-center bg-slate-100 text-slate-400 text-xs font-semibold py-2.5 rounded-xl cursor-not-allowed">
                                <i class="fas fa-ban mr-1.5"></i>Stok Habis
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $produks->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Override default Laravel pagination to match design */
    nav[aria-label="Pagination Navigation"] { display: flex; justify-content: center; }
    .pagination { display: flex; gap: 0.375rem; align-items: center; }
    .pagination li .page-link,
    .pagination li span {
        display: flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 .5rem;
        border-radius: 10px; font-size: .8125rem; font-weight: 500;
        border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
        text-decoration: none; transition: all .15s;
    }
    .pagination li .page-link:hover { background: #eff6ff; border-color: #2563eb; color: #2563eb; }
    .pagination li span[aria-current="page"] { background: #2563eb; border-color: #2563eb; color: #fff; }
    .pagination li span.disabled { opacity: .4; cursor: not-allowed; }
</style>
@endpush

@endsection
