@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-credit-card mr-2 text-blue-700"></i>Checkout</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
            @foreach($errors->all() as $error)<p><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.proses') }}">
        @csrf
        <div class="grid md:grid-cols-3 gap-6">
            {{-- Kiri: Form --}}
            <div class="md:col-span-2 space-y-5">

                {{-- Alamat Pengiriman --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4"><i class="fas fa-map-marker-alt text-blue-700 mr-2"></i>Alamat Pengiriman</h2>
                    <textarea name="alamat_pengiriman" rows="3" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"
                        placeholder="Masukkan alamat pengiriman lengkap...">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4"><i class="fas fa-wallet text-blue-700 mr-2"></i>Metode Pembayaran</h2>
                    <div class="space-y-3">
                        @foreach(['Transfer Bank', 'QRIS', 'COD'] as $metode)
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="metode_pembayaran" value="{{ $metode }}"
                                    {{ old('metode_pembayaran') === $metode || ($loop->first && !old('metode_pembayaran')) ? 'checked' : '' }}
                                    class="text-blue-600">
                                <div>
                                    <p class="font-medium text-sm text-gray-800">
                                        @if($metode === 'Transfer Bank') <i class="fas fa-university mr-2 text-blue-600"></i>
                                        @elseif($metode === 'QRIS') <i class="fas fa-qrcode mr-2 text-blue-600"></i>
                                        @else <i class="fas fa-money-bill mr-2 text-blue-600"></i>
                                        @endif
                                        {{ $metode }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        @if($metode === 'Transfer Bank') Transfer ke rekening yang tertera
                                        @elseif($metode === 'QRIS') Scan QR code dengan aplikasi dompet digital
                                        @else Bayar langsung saat pesanan tiba
                                        @endif
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Kanan: Ringkasan --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-5 sticky top-20">
                    <h2 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-2 mb-4 max-h-48 overflow-y-auto">
                        @foreach($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 truncate mr-2">{{ $item->produk->nama_produk }} ×{{ $item->jumlah }}</span>
                                <span class="text-gray-800 font-medium flex-shrink-0">
                                    Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-3">
                    <div class="flex justify-between font-bold text-base">
                        <span>Total</span>
                        <span class="text-blue-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition mt-5">
                        <i class="fas fa-check mr-2"></i>Buat Pesanan
                    </button>
                    <a href="{{ route('keranjang.index') }}"
                       class="block w-full text-center text-gray-500 py-2 text-sm hover:text-gray-700 transition mt-2">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
