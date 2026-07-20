@extends('layouts.app')

@section('title', 'Keranjang Belanja — Kantin Biru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-7">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                <i class="fas fa-shopping-cart text-primary-600 mr-2"></i>Keranjang Belanja
            </h1>
            @if(!$items->isEmpty())
                <p class="text-slate-500 text-sm mt-0.5">{{ $items->count() }} produk dalam keranjang</p>
            @endif
        </div>
        <a href="{{ route('pelanggan.home') }}"
           class="flex items-center gap-2 text-sm text-slate-500 hover:text-primary-600 transition-colors duration-150 font-medium">
            <i class="fas fa-arrow-left text-xs"></i>Lanjut Belanja
        </a>
    </div>

    @if($items->isEmpty())
        {{-- Empty Cart State --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-20 text-center">
            {{-- SVG Illustration --}}
            <div class="flex justify-center mb-6">
                <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center">
                    <svg viewBox="0 0 120 120" class="w-20 h-20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="55" fill="#EFF6FF"/>
                        <path d="M30 35h6l8 35h36l6-25H46" stroke="#2563EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <circle cx="55" cy="78" r="5" fill="#2563EB"/>
                        <circle cx="75" cy="78" r="5" fill="#2563EB"/>
                        <path d="M52 50h20M52 58h14" stroke="#93C5FD" stroke-width="2" stroke-linecap="round"/>
                        <path d="M80 28l4 4M80 36l4-4" stroke="#FACC15" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Keranjangmu kosong!</h3>
            <p class="text-slate-400 text-sm mb-8 max-w-xs mx-auto">Belum ada produk yang ditambahkan. Yuk mulai pilih camilan favoritmu!</p>
            <a href="{{ route('pelanggan.home') }}"
               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-store"></i>Jelajahi Produk
            </a>
        </div>

    @else
        {{-- Checkout Progress --}}
        <div class="flex items-center justify-center mb-8 gap-0">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">1</div>
                <span class="text-sm font-semibold text-primary-600 hidden sm:block">Keranjang</span>
            </div>
            <div class="flex-1 max-w-16 h-0.5 bg-slate-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                <span class="text-sm font-medium text-slate-400 hidden sm:block">Checkout</span>
            </div>
            <div class="flex-1 max-w-16 h-0.5 bg-slate-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                <span class="text-sm font-medium text-slate-400 hidden sm:block">Selesai</span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            {{-- Cart Items --}}
            <div class="md:col-span-2 space-y-3">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex gap-4 hover:border-primary-200 transition-all duration-200">

                        {{-- Product Image --}}
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if($item->produk->foto)
                                <img src="{{ asset('storage/' . $item->produk->foto) }}"
                                     alt="{{ $item->produk->nama_produk }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-800 text-sm sm:text-base leading-snug mb-1 line-clamp-2">
                                {{ $item->produk->nama_produk }}
                            </h3>
                            <p class="text-primary-600 font-bold text-sm mb-3">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                            </p>

                            {{-- Quantity Form --}}
                            <form method="POST" action="{{ route('keranjang.update', $item->id_keranjang) }}" class="inline-flex">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center bg-slate-100 rounded-xl overflow-hidden border border-slate-200">
                                    <button type="button" onclick="changeQtyCart(this, -1)"
                                            class="w-8 h-8 flex items-center justify-center hover:bg-slate-200 text-slate-600 transition-colors duration-150">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" name="jumlah" value="{{ $item->jumlah }}"
                                           min="1" max="{{ $item->produk->stok }}"
                                           class="w-10 text-center bg-transparent font-bold text-slate-800 outline-none text-sm border-0 py-0"
                                           onchange="this.form.submit()">
                                    <button type="button" onclick="changeQtyCart(this, 1)"
                                            class="w-8 h-8 flex items-center justify-center hover:bg-slate-200 text-slate-600 transition-colors duration-150">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Subtotal + Delete --}}
                        <div class="flex flex-col items-end justify-between flex-shrink-0">
                            <p class="font-bold text-slate-800 text-sm sm:text-base">
                                Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                            </p>
                            <form method="POST" action="{{ route('keranjang.hapus', $item->id_keranjang) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus produk ini dari keranjang?')"
                                        class="flex items-center gap-1.5 text-xs text-red-400 hover:text-red-600 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-all duration-150 font-medium">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="hidden sm:inline">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sticky top-24">
                    <h2 class="font-bold text-slate-800 text-lg mb-5">Ringkasan Pesanan</h2>

                    {{-- Item list summary --}}
                    <div class="space-y-2 mb-4">
                        @foreach($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 truncate mr-2 flex-1">{{ $item->produk->nama_produk }} ×{{ $item->jumlah }}</span>
                                <span class="text-slate-700 font-medium shrink-0">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="h-px bg-slate-100 my-4"></div>

                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-slate-500">Subtotal ({{ $items->count() }} item)</span>
                        <span class="font-medium text-slate-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-5 text-sm">
                        <span class="text-slate-500">Biaya layanan</span>
                        <span class="text-emerald-600 font-medium">Gratis</span>
                    </div>

                    <div class="h-px bg-slate-100 mb-4"></div>
                    <div class="flex justify-between mb-6">
                        <span class="font-bold text-slate-800">Total</span>
                        <span class="font-bold text-xl text-primary-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-accent-400 hover:bg-accent-500 text-slate-900 font-bold py-3.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                        <i class="fas fa-credit-card mr-2"></i>Lanjut ke Checkout
                    </a>
                    <a href="{{ route('pelanggan.home') }}"
                       class="block w-full text-center text-slate-400 hover:text-slate-600 py-2.5 text-sm font-medium transition-colors duration-150 mt-2">
                        <i class="fas fa-arrow-left mr-1.5 text-xs"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function changeQtyCart(btn, delta) {
    const input = btn.parentElement.querySelector('input');
    const max   = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
    input.form.submit();
}
</script>
@endpush
@endsection
