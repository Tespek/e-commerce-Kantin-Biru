@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-box-open mr-2 text-blue-700"></i>Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-16 text-center text-gray-500">
            <i class="fas fa-box-open text-6xl text-gray-200 mb-4"></i>
            <p class="text-lg mb-4">Belum ada pesanan</p>
            <a href="{{ route('pelanggan.home') }}" class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800 transition">
                <i class="fas fa-store mr-2"></i>Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusColor = match($order->status) {
                        'pending'     => 'bg-yellow-100 text-yellow-700',
                        'dibayar'     => 'bg-blue-100 text-blue-700',
                        'diproses'    => 'bg-indigo-100 text-indigo-700',
                        'selesai'     => 'bg-green-100 text-green-700',
                        'dibatalkan'  => 'bg-red-100 text-red-700',
                        default       => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-xs text-gray-400">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</p>
                            <p class="font-semibold text-gray-800 mt-0.5">Order #{{ $order->id_order }}</p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $statusColor }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 mb-3">
                        <span>{{ $order->details->count() }} produk</span>
                        <span class="mx-2">•</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        @if($order->pembayaran)
                            <span class="mx-2">•</span>
                            <span>{{ $order->pembayaran->metode }}</span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('pesanan.detail', $order->id_order) }}"
                           class="text-sm bg-blue-700 text-white px-4 py-1.5 rounded-lg hover:bg-blue-800 transition">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        @if($order->pembayaran && $order->pembayaran->metode !== 'COD' && $order->pembayaran->status === 'menunggu')
                            <a href="{{ route('pesanan.upload-bukti', $order->id_order) }}"
                               class="text-sm bg-orange-500 text-white px-4 py-1.5 rounded-lg hover:bg-orange-600 transition">
                                <i class="fas fa-upload mr-1"></i>Upload Bukti
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
