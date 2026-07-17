@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-shopping-cart mr-2 text-blue-700"></i>Keranjang Belanja</h1>

    @if($items->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-16 text-center text-gray-500">
            <i class="fas fa-shopping-cart text-6xl text-gray-200 mb-4"></i>
            <p class="text-lg mb-4">Keranjang kamu masih kosong</p>
            <a href="{{ route('pelanggan.home') }}" class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-store mr-2"></i>Mulai Belanja
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-6">
            {{-- Daftar Item --}}
            <div class="md:col-span-2 space-y-3">
                @foreach($items as $item)
                    <div class="bg-white rounded-xl shadow-sm p-4 flex gap-4 items-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->produk->foto)
                                <img src="{{ asset('storage/' . $item->produk->foto) }}"
                                     alt="{{ $item->produk->nama_produk }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $item->produk->nama_produk }}</h3>
                            <p class="text-blue-700 font-bold text-sm">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>

                            {{-- Update Jumlah --}}
                            <form method="POST" action="{{ route('keranjang.update', $item->id_keranjang) }}" class="flex items-center gap-2 mt-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden text-sm">
                                    <button type="button" onclick="changeQty(this, -1)"
                                        class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 font-bold">−</button>
                                    <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" max="{{ $item->produk->stok }}"
                                        class="w-12 text-center py-1 border-0 outline-none text-sm"
                                        onchange="this.form.submit()">
                                    <button type="button" onclick="changeQty(this, 1)"
                                        class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 font-bold">+</button>
                                </div>
                            </form>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</p>
                            <form method="POST" action="{{ route('keranjang.hapus', $item->id_keranjang) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-xs transition"
                                    onclick="return confirm('Hapus produk ini dari keranjang?')">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Ringkasan --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-5 sticky top-20">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Ringkasan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $items->count() }} item)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-bold text-gray-800 text-base">
                            <span>Total</span>
                            <span class="text-blue-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition mt-5">
                        <i class="fas fa-credit-card mr-2"></i>Checkout
                    </a>
                    <a href="{{ route('pelanggan.home') }}"
                       class="block w-full text-center text-gray-500 py-2 rounded-lg hover:text-gray-700 transition mt-2 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function changeQty(btn, delta) {
    const input = btn.parentElement.querySelector('input');
    const max   = parseInt(input.max);
    let val     = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
    input.form.submit();
}
</script>
@endpush
@endsection
