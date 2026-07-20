@extends('layouts.app')

@section('title', $produk->nama_produk . ' — Kantin Biru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-7" aria-label="Breadcrumb">
        <a href="{{ route('pelanggan.home') }}" class="hover:text-primary-600 transition-colors duration-150 font-medium">
            <i class="fas fa-home text-xs mr-1"></i>Beranda
        </a>
        <i class="fas fa-chevron-right text-xs text-slate-300"></i>
        <a href="{{ route('pelanggan.home') }}" class="hover:text-primary-600 transition-colors duration-150">
            {{ $produk->kategori->nama_kategori ?? 'Produk' }}
        </a>
        <i class="fas fa-chevron-right text-xs text-slate-300"></i>
        <span class="text-slate-800 font-medium truncate max-w-48">{{ $produk->nama_produk }}</span>
    </nav>

    {{-- Main Product Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-10">
        <div class="md:flex">

            {{-- Product Image --}}
            <div class="md:w-2/5 relative bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-8 min-h-72">
                @if($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}"
                         id="product-main-img"
                         alt="{{ $produk->nama_produk }}"
                         class="max-h-80 w-full object-contain rounded-xl cursor-zoom-in transition-transform duration-300 hover:scale-105"
                         onclick="openLightbox(this.src)">
                @else
                    <div class="w-full h-64 flex flex-col items-center justify-center text-slate-300 gap-3">
                        <i class="fas fa-image text-7xl"></i>
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                @endif

                {{-- Stock Badge on Image --}}
                @if($produk->stok == 0)
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-2xl">
                        <span class="bg-red-500 text-white font-bold text-lg px-6 py-2 rounded-xl">STOK HABIS</span>
                    </div>
                @elseif($produk->stok <= 5)
                    <div class="absolute top-4 left-4">
                        <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow-md">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas
                        </span>
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="md:w-3/5 p-7 lg:p-10 flex flex-col">

                {{-- Category + Status --}}
                <div class="flex items-center gap-2 mb-4 flex-wrap">
                    <span class="bg-primary-50 text-primary-700 border border-primary-100 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $produk->kategori->nama_kategori ?? '-' }}
                    </span>
                    @if($produk->stok > 0)
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-circle-check mr-1"></i>Tersedia
                        </span>
                    @else
                        <span class="bg-red-50 text-red-700 border border-red-100 text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-circle-xmark mr-1"></i>Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Name --}}
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight mb-3">
                    {{ $produk->nama_produk }}
                </h1>

                {{-- Price --}}
                <div class="flex items-baseline gap-2 mb-5">
                    <p class="text-3xl lg:text-4xl font-bold text-primary-600">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>
                    <span class="text-slate-400 text-sm font-normal">/ item</span>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-slate-100 mb-5"></div>

                {{-- Stock info --}}
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cubes text-slate-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Stok Tersedia</p>
                            <p class="text-sm font-bold {{ $produk->stok <= 5 ? 'text-amber-600' : 'text-slate-800' }}">
                                {{ $produk->stok }} unit
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($produk->deskripsi)
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</p>
                        <p class="text-slate-600 text-sm leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100">
                            {{ $produk->deskripsi }}
                        </p>
                    </div>
                @endif

                {{-- Cart Action --}}
                @if($produk->stok > 0)
                    @auth
                        @if(auth()->user()->isPelanggan())
                            <form method="POST" action="{{ route('keranjang.tambah') }}" class="mt-auto">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">

                                <div class="flex items-center gap-4 flex-wrap">
                                    {{-- Qty selector --}}
                                    <div class="flex items-center bg-slate-100 rounded-xl overflow-hidden border border-slate-200">
                                        <button type="button" onclick="changeQty(-1)"
                                                class="w-10 h-11 flex items-center justify-center hover:bg-slate-200 text-slate-700 font-bold text-lg transition-colors duration-150">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <input type="number" id="jumlah" name="jumlah" value="1"
                                               min="1" max="{{ $produk->stok }}"
                                               class="w-16 text-center h-11 bg-transparent font-bold text-slate-800 outline-none text-base border-0">
                                        <button type="button" onclick="changeQty(1)"
                                                class="w-10 h-11 flex items-center justify-center hover:bg-slate-200 text-slate-700 font-bold text-lg transition-colors duration-150">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>

                                    {{-- Add to Cart --}}
                                    <button type="submit"
                                            class="flex-1 min-w-40 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                        <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                                    </button>
                                </div>

                                {{-- Subtotal preview --}}
                                <p class="text-xs text-slate-400 mt-3" id="subtotal-preview">
                                    Subtotal: <span class="font-semibold text-slate-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                </p>
                            </form>
                        @endif
                    @else
                        <div class="mt-auto">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <i class="fas fa-sign-in-alt"></i>Masuk untuk Memesan
                            </a>
                            <p class="text-xs text-slate-400 mt-2">Daftar gratis jika belum punya akun</p>
                        </div>
                    @endauth
                @else
                    <div class="mt-auto">
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas fa-ban text-red-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-700 text-sm">Stok Habis</p>
                                <p class="text-xs text-slate-400">Produk sedang tidak tersedia</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-slate-800">Produk Terkait</h2>
                <a href="{{ route('pelanggan.home') }}"
                   class="text-sm text-primary-600 hover:text-primary-700 font-semibold hover:underline transition-colors duration-150">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($related as $item)
                    <a href="{{ route('produk.detail', $item->id_produk) }}"
                       class="bg-white rounded-2xl shadow-sm hover:shadow-card-hover border border-slate-100 overflow-hidden group transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-square overflow-hidden bg-slate-100">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}"
                                     alt="{{ $item->nama_produk }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-200">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5">
                            <h3 class="font-semibold text-sm text-slate-800 line-clamp-2 group-hover:text-primary-600 transition-colors duration-150 mb-1.5">
                                {{ $item->nama_produk }}
                            </h3>
                            <p class="text-primary-600 font-bold text-sm">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 bg-black/80 z-[200] hidden items-center justify-center p-4" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white text-3xl hover:text-slate-300 transition-colors">
        <i class="fas fa-xmark"></i>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl">
</div>

@push('scripts')
<script>
const harga = {{ $produk->harga }};

function changeQty(delta) {
    const input = document.getElementById('jumlah');
    const max   = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;

    // Update subtotal preview
    const subtotal = val * harga;
    const el = document.getElementById('subtotal-preview');
    if (el) el.innerHTML = `Subtotal: <span class="font-semibold text-slate-600">Rp ${subtotal.toLocaleString('id-ID')}</span>`;
}

// Update subtotal when input changes manually
document.getElementById('jumlah')?.addEventListener('input', function() {
    const max = parseInt(this.max);
    let val = parseInt(this.value) || 1;
    if (val < 1) val = 1;
    if (val > max) { val = max; this.value = val; }
    const el = document.getElementById('subtotal-preview');
    if (el) el.innerHTML = `Subtotal: <span class="font-semibold text-slate-600">Rp ${(val * harga).toLocaleString('id-ID')}</span>`;
});

function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    const lb = document.getElementById('lightbox');
    lb.classList.remove('hidden');
    lb.classList.add('flex');
}

function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.add('hidden');
    lb.classList.remove('flex');
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endpush
@endsection
