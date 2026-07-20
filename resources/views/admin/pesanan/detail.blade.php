@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->id_order)

@section('content')
<div class="grid lg:grid-cols-3 gap-5">

    {{-- LEFT: Order Details --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Order Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-primary-600 text-xs"></i>
                </div>
                Informasi Pesanan
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">ID Order</p>
                    <p class="font-bold text-slate-800">#{{ $order->id_order }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">Tanggal</p>
                    <p class="font-medium text-slate-800 text-sm">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">Pelanggan</p>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-primary-100 rounded-md flex items-center justify-center text-xs font-bold text-primary-700">
                            {{ strtoupper(substr($order->user->nama ?? 'U', 0, 1)) }}
                        </div>
                        <p class="font-medium text-slate-800 text-sm">{{ $order->user->nama ?? '-' }}</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 mb-1">No. HP</p>
                    <p class="font-medium text-slate-800 text-sm">{{ $order->user->no_hp ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 col-span-2">
                    <p class="text-xs text-slate-400 mb-1">Alamat Pengiriman</p>
                    <p class="font-medium text-slate-800 text-sm">{{ $order->alamat_pengiriman }}</p>
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-primary-600 text-xs"></i>
                </div>
                Produk Dipesan
            </h2>
            <div class="space-y-3">
                @foreach($order->details as $detail)
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 bg-white">
                            @if($detail->produk->foto)
                                <img src="{{ asset('storage/' . $detail->produk->foto) }}" class="w-full h-full object-cover" alt="{{ $detail->produk->nama_produk }}">
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
            <div class="h-px bg-slate-100 my-4"></div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-800">Total Pesanan</span>
                <span class="font-bold text-xl text-primary-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- RIGHT: Actions --}}
    <div class="space-y-5">

        {{-- Update Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <div class="w-7 h-7 bg-violet-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-gear text-violet-600 text-xs"></i>
                </div>
                Update Status Pesanan
            </h2>

            {{-- Current Status Badge --}}
            @php
                $sc = match($order->status) {
                    'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
                    'dibayar'    => 'bg-blue-50 text-blue-700 border-blue-200',
                    'diproses'   => 'bg-violet-50 text-violet-700 border-violet-200',
                    'selesai'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                    default      => 'bg-slate-50 text-slate-700 border-slate-200',
                };
            @endphp
            <div class="mb-3 flex items-center gap-2">
                <span class="text-xs text-slate-400">Status saat ini:</span>
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <form method="POST" action="{{ route('admin.pesanan.status', $order->id_order) }}">
                @csrf @method('PATCH')
                <select name="status"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer mb-3">
                    @foreach(['pending','dibayar','diproses','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fas fa-floppy-disk mr-2"></i>Update Status
                </button>
            </form>
        </div>

        {{-- Payment Info --}}
        @if($order->pembayaran)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-amber-600 text-xs"></i>
                    </div>
                    Konfirmasi Pembayaran
                </h2>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-400 mb-1">Metode</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $order->pembayaran->metode }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-400 mb-1">Status Bayar</p>
                        @php
                            $pc = match($order->pembayaran->status) {
                                'diterima' => 'text-emerald-600',
                                'ditolak'  => 'text-red-600',
                                default    => 'text-amber-600',
                            };
                        @endphp
                        <p class="font-bold text-sm {{ $pc }}">{{ ucfirst($order->pembayaran->status) }}</p>
                    </div>
                </div>

                @if($order->pembayaran->bukti_pembayaran)
                    <div class="mb-4">
                        <p class="text-xs text-slate-400 mb-2 font-medium">Bukti Pembayaran:</p>
                        <a href="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                                 alt="Bukti"
                                 class="w-full rounded-xl border border-slate-200 shadow-sm hover:opacity-90 transition-opacity duration-150 cursor-zoom-in">
                        </a>
                        <p class="text-[10px] text-slate-400 mt-1.5 text-center">Klik gambar untuk memperbesar</p>
                    </div>

                    @if($order->pembayaran->status === 'menunggu')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.pesanan.konfirmasi', $order->pembayaran->id_pembayaran) }}" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Konfirmasi pembayaran ini? Status akan menjadi DITERIMA.')"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-check mr-1.5"></i>Terima
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.pesanan.tolak', $order->pembayaran->id_pembayaran) }}" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Tolak pembayaran ini?')"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-times mr-1.5"></i>Tolak
                                </button>
                            </form>
                        </div>
                    @elseif($order->pembayaran->status === 'diterima')
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                            <i class="fas fa-circle-check"></i>
                            Pembayaran telah dikonfirmasi
                        </div>
                    @elseif($order->pembayaran->status === 'ditolak')
                        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
                            <i class="fas fa-circle-xmark"></i>
                            Pembayaran telah ditolak
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-image text-slate-300 text-xl"></i>
                        </div>
                        <p class="text-sm text-slate-400">Belum ada bukti pembayaran</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Back --}}
        <a href="{{ route('admin.pesanan.index') }}"
           class="flex items-center justify-center gap-2 text-sm text-slate-500 hover:text-primary-600 transition-colors duration-150 font-medium py-2">
            <i class="fas fa-arrow-left text-xs"></i>Kembali ke Daftar Pesanan
        </a>
    </div>
</div>
@endsection
