@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id_order)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pesanan.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $order->id_order }}</h1>
    </div>

    @php
        $statusColor = match($order->status) {
            'pending'    => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'dibayar'    => 'bg-blue-100 text-blue-700 border-blue-200',
            'diproses'   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'selesai'    => 'bg-green-100 text-green-700 border-green-200',
            'dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
            default      => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    @endphp

    {{-- Status --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                <p class="font-medium">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-sm font-semibold border {{ $statusColor }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="mt-3">
            <p class="text-sm text-gray-500">Alamat Pengiriman</p>
            <p class="font-medium">{{ $order->alamat_pengiriman }}</p>
        </div>
    </div>

    {{-- Produk --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
        <h2 class="font-bold text-gray-800 mb-3">Produk Dipesan</h2>
        <div class="space-y-3">
            @foreach($order->details as $detail)
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($detail->produk->foto)
                            <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                 alt="{{ $detail->produk->nama_produk }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-gray-800 truncate">{{ $detail->produk->nama_produk }}</p>
                        <p class="text-xs text-gray-500">{{ $detail->jumlah }} × Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-semibold text-sm">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
        <hr class="my-3">
        <div class="flex justify-between font-bold">
            <span>Total</span>
            <span class="text-blue-700">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Pembayaran --}}
    @if($order->pembayaran)
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-3">Informasi Pembayaran</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-500">Metode</p>
                    <p class="font-medium">{{ $order->pembayaran->metode }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status Pembayaran</p>
                    @php
                        $payColor = match($order->pembayaran->status) {
                            'menunggu' => 'text-yellow-600',
                            'diterima' => 'text-green-600',
                            'ditolak'  => 'text-red-600',
                            default    => 'text-gray-600',
                        };
                    @endphp
                    <p class="font-semibold {{ $payColor }}">{{ ucfirst($order->pembayaran->status) }}</p>
                </div>
                @if($order->pembayaran->tanggal_bayar)
                    <div>
                        <p class="text-gray-500">Tanggal Bayar</p>
                        <p class="font-medium">{{ $order->pembayaran->tanggal_bayar->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>

            @if($order->pembayaran->bukti_pembayaran)
                <div class="mt-3">
                    <p class="text-gray-500 text-sm mb-2">Bukti Pembayaran</p>
                    <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                         alt="Bukti Pembayaran" class="max-w-xs rounded-lg border">
                </div>
            @endif

            @if($order->pembayaran->metode !== 'COD' && $order->pembayaran->status === 'menunggu')
                <a href="{{ route('pesanan.upload-bukti', $order->id_order) }}"
                   class="inline-block mt-4 bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition text-sm font-medium">
                    <i class="fas fa-upload mr-2"></i>
                    {{ $order->pembayaran->bukti_pembayaran ? 'Ganti Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
