@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id_order . ' — Kantin Biru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back + Title --}}
    <div class="flex items-center gap-3 mb-7">
        <a href="{{ route('pesanan.index') }}"
           class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-primary-600 hover:border-primary-300 transition-all duration-150 shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Detail Pesanan</h1>
            <p class="text-slate-500 text-sm">Order #{{ $order->id_order }}</p>
        </div>
    </div>

    @php
        $statusConfig = match($order->status) {
            'pending'    => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-200',  'icon' => 'fa-clock',         'label' => 'Menunggu Pembayaran', 'step' => 1],
            'dibayar'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',   'icon' => 'fa-check-circle',  'label' => 'Pembayaran Diterima',  'step' => 2],
            'diproses'   => ['bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-200', 'icon' => 'fa-gear',          'label' => 'Sedang Diproses',     'step' => 3],
            'selesai'    => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200','icon' => 'fa-circle-check',  'label' => 'Pesanan Selesai',     'step' => 4],
            'dibatalkan' => ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'border' => 'border-red-200',    'icon' => 'fa-circle-xmark',  'label' => 'Dibatalkan',          'step' => 0],
            default      => ['bg' => 'bg-slate-50',   'text' => 'text-slate-700',   'border' => 'border-slate-200',  'icon' => 'fa-circle',        'label' => ucfirst($order->status),'step' => 0],
        };
    @endphp

    {{-- Status Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-5">
        <div class="flex items-start justify-between mb-5">
            <div>
                <p class="text-xs text-slate-400 mb-1">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                </p>
                <p class="text-lg font-bold text-slate-900">Order #{{ $order->id_order }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                <i class="fas {{ $statusConfig['icon'] }}"></i>
                {{ $statusConfig['label'] }}
            </span>
        </div>

        {{-- Status Timeline --}}
        @if($order->status !== 'dibatalkan')
            <div class="flex items-center gap-0 mt-2 overflow-x-auto pb-1">
                @php
                    $steps = [
                        ['icon' => 'fa-clock',        'label' => 'Pending',  'step' => 1],
                        ['icon' => 'fa-credit-card',  'label' => 'Dibayar',  'step' => 2],
                        ['icon' => 'fa-gear',         'label' => 'Diproses', 'step' => 3],
                        ['icon' => 'fa-circle-check', 'label' => 'Selesai',  'step' => 4],
                    ];
                    $currentStep = $statusConfig['step'];
                @endphp
                @foreach($steps as $i => $step)
                    <div class="flex items-center {{ $i > 0 ? 'flex-1' : '' }} shrink-0">
                        @if($i > 0)
                            <div class="flex-1 h-0.5 {{ $currentStep >= $step['step'] ? 'bg-primary-500' : 'bg-slate-200' }} min-w-8"></div>
                        @endif
                        <div class="flex flex-col items-center gap-1.5 shrink-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm
                                {{ $currentStep >= $step['step'] ? 'bg-primary-600 text-white shadow-md' : 'bg-slate-100 text-slate-400' }}">
                                @if($currentStep > $step['step'])
                                    <i class="fas fa-check text-xs"></i>
                                @else
                                    <i class="fas {{ $step['icon'] }} text-xs"></i>
                                @endif
                            </div>
                            <span class="text-[10px] font-medium {{ $currentStep >= $step['step'] ? 'text-primary-600' : 'text-slate-400' }} whitespace-nowrap">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Shipping Address --}}
        <div class="mt-5 pt-5 border-t border-slate-100">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-map-marker-alt text-slate-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Alamat Pengiriman</p>
                    <p class="text-sm font-medium text-slate-800">{{ $order->alamat_pengiriman }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-5">
        <h2 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-primary-600 text-xs"></i>
            </div>
            Produk Dipesan
        </h2>

        <div class="space-y-3">
            @foreach($order->details as $detail)
                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                    <div class="w-14 h-14 bg-white rounded-xl overflow-hidden flex-shrink-0 border border-slate-100">
                        @if($detail->produk->foto)
                            <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                 alt="{{ $detail->produk->nama_produk }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fas fa-image text-lg"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-800 truncate">{{ $detail->produk->nama_produk }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $detail->jumlah }} × Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-bold text-slate-800 text-sm shrink-0">
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="h-px bg-slate-100 mt-4 mb-4"></div>
        <div class="flex justify-between items-center">
            <span class="font-bold text-slate-800">Total Pesanan</span>
            <span class="font-bold text-xl text-primary-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Payment Info --}}
    @if($order->pembayaran)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-primary-600 text-xs"></i>
                </div>
                Informasi Pembayaran
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">Metode</p>
                    <p class="font-semibold text-slate-800 text-sm">{{ $order->pembayaran->metode }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">Status Bayar</p>
                    @php
                        $payConfig = match($order->pembayaran->status) {
                            'diterima' => ['color' => 'text-emerald-600', 'label' => 'Diterima'],
                            'ditolak'  => ['color' => 'text-red-600',     'label' => 'Ditolak'],
                            default    => ['color' => 'text-amber-600',   'label' => 'Menunggu'],
                        };
                    @endphp
                    <p class="font-semibold text-sm {{ $payConfig['color'] }}">{{ $payConfig['label'] }}</p>
                </div>
                @if($order->pembayaran->tanggal_bayar)
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-400 mb-1">Tanggal Bayar</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $order->pembayaran->tanggal_bayar->format('d M Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- Bukti Pembayaran --}}
            @if($order->pembayaran->bukti_pembayaran)
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 mb-3 font-medium">Bukti Pembayaran</p>
                    <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                         alt="Bukti Pembayaran"
                         class="max-w-64 rounded-xl border border-slate-200 shadow-sm hover:opacity-90 transition-opacity duration-150 cursor-pointer"
                         onclick="window.open(this.src)">
                </div>
            @endif

            {{-- Upload Button --}}
            @if($order->pembayaran->metode !== 'COD' && $order->pembayaran->status === 'menunggu')
                <a href="{{ route('pesanan.upload-bukti', $order->id_order) }}"
                   class="inline-flex items-center gap-2 mt-4 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-semibold px-5 py-2.5 rounded-xl transition-all duration-150 text-sm">
                    <i class="fas fa-upload"></i>
                    {{ $order->pembayaran->bukti_pembayaran ? 'Ganti Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
