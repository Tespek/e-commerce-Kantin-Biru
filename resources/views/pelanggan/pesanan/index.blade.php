@extends('layouts.app')

@section('title', 'Pesanan Saya — Kantin Biru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-7">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                <i class="fas fa-box-open text-primary-600 mr-2"></i>Pesanan Saya
            </h1>
            @if(!$orders->isEmpty())
                <p class="text-slate-500 text-sm mt-0.5">Riwayat semua pesanan kamu</p>
            @endif
        </div>
        <a href="{{ route('pelanggan.home') }}"
           class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow">
            <i class="fas fa-plus text-xs"></i>Belanja Lagi
        </a>
    </div>

    @if($orders->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-20 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center">
                    <svg viewBox="0 0 120 120" class="w-20 h-20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="55" fill="#EFF6FF"/>
                        <rect x="30" y="35" width="60" height="55" rx="6" fill="#DBEAFE" stroke="#2563EB" stroke-width="2"/>
                        <path d="M40 50h40M40 60h30M40 70h20" stroke="#93C5FD" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="82" cy="38" r="12" fill="#FACC15"/>
                        <path d="M78 38l3 3 5-5" stroke="#1e3a8a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada pesanan</h3>
            <p class="text-slate-400 text-sm mb-8 max-w-xs mx-auto">Kamu belum pernah melakukan pemesanan. Mulai belanja sekarang!</p>
            <a href="{{ route('pelanggan.home') }}"
               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="fas fa-store"></i>Mulai Belanja
            </a>
        </div>

    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusConfig = match($order->status) {
                        'pending'    => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-200',  'icon' => 'fa-clock',         'label' => 'Menunggu Pembayaran'],
                        'dibayar'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',   'icon' => 'fa-check-circle',  'label' => 'Dibayar'],
                        'diproses'   => ['bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-200', 'icon' => 'fa-gear',          'label' => 'Diproses'],
                        'selesai'    => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200','icon' => 'fa-circle-check', 'label' => 'Selesai'],
                        'dibatalkan' => ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'border' => 'border-red-200',    'icon' => 'fa-circle-xmark',  'label' => 'Dibatalkan'],
                        default      => ['bg' => 'bg-slate-50',   'text' => 'text-slate-700',   'border' => 'border-slate-200',  'icon' => 'fa-circle',        'label' => ucfirst($order->status)],
                    };
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all duration-200">

                    {{-- Order Header --}}
                    <div class="flex items-start sm:items-center justify-between p-5 pb-4 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <p class="font-bold text-slate-800">Order #{{ $order->id_order }}</p>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                                    <i class="fas {{ $statusConfig['icon'] }} text-[10px]"></i>
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">
                                <i class="fas fa-calendar text-slate-300 mr-1"></i>
                                {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                        <p class="font-bold text-primary-600 text-lg">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Order Details --}}
                    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-box text-slate-300"></i>
                                {{ $order->details->count() }} produk
                            </span>
                            @if($order->pembayaran)
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-wallet text-slate-300"></i>
                                    {{ $order->pembayaran->metode }}
                                </span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('pesanan.detail', $order->id_order) }}"
                               class="flex items-center gap-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-150">
                                <i class="fas fa-eye text-xs"></i>Detail
                            </a>
                            @if($order->pembayaran && $order->pembayaran->status === 'menunggu')
                                <a href="{{ route('pesanan.upload-bukti', $order->id_order) }}"
                                   class="flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-150">
                                    <i class="fas fa-upload text-xs"></i>Upload Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
