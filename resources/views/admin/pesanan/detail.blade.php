@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->id_order)

@section('content')
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Kiri: Detail --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Info Order --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-500">ID Order</p><p class="font-medium">#{{ $order->id_order }}</p></div>
                <div><p class="text-gray-500">Tanggal</p><p class="font-medium">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</p></div>
                <div><p class="text-gray-500">Pelanggan</p><p class="font-medium">{{ $order->user->nama ?? '-' }}</p></div>
                <div><p class="text-gray-500">No. HP</p><p class="font-medium">{{ $order->user->no_hp ?? '-' }}</p></div>
                <div class="col-span-2"><p class="text-gray-500">Alamat Pengiriman</p><p class="font-medium">{{ $order->alamat_pengiriman }}</p></div>
            </div>
        </div>

        {{-- Produk --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Produk</h2>
            <div class="space-y-3">
                @foreach($order->details as $detail)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($detail->produk->foto)
                                <img src="{{ asset('storage/' . $detail->produk->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $detail->produk->nama_produk }}</p>
                            <p class="text-gray-500">{{ $detail->jumlah }} × Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <hr class="my-3">
            <div class="flex justify-between font-bold text-sm">
                <span>Total</span>
                <span class="text-blue-700">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Kanan: Aksi --}}
    <div class="space-y-5">

        {{-- Status Order --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Update Status Pesanan</h2>
            <form method="POST" action="{{ route('admin.pesanan.status', $order->id_order) }}">
                @csrf @method('PATCH')
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none mb-3">
                    @foreach(['pending','dibayar','diproses','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded-lg text-sm hover:bg-blue-800 transition">
                    <i class="fas fa-save mr-1"></i>Update Status
                </button>
            </form>
        </div>

        {{-- Pembayaran --}}
        @if($order->pembayaran)
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-4">Pembayaran</h2>
                <div class="text-sm space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-medium">{{ $order->pembayaran->metode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        @php
                            $pc = match($order->pembayaran->status) {
                                'diterima' => 'text-green-600',
                                'ditolak'  => 'text-red-600',
                                default    => 'text-yellow-600',
                            };
                        @endphp
                        <span class="font-semibold {{ $pc }}">{{ ucfirst($order->pembayaran->status) }}</span>
                    </div>
                </div>

                @if($order->pembayaran->bukti_pembayaran)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Bukti Pembayaran:</p>
                        <a href="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                                 alt="Bukti" class="w-full rounded-lg border hover:opacity-90 transition">
                        </a>
                    </div>

                    @if($order->pembayaran->status === 'menunggu')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.pesanan.konfirmasi', $order->pembayaran->id_pembayaran) }}" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('Konfirmasi pembayaran ini?')"
                                    class="w-full bg-green-600 text-white py-2 rounded-lg text-sm hover:bg-green-700 transition">
                                    <i class="fas fa-check mr-1"></i>Terima
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.pesanan.tolak', $order->pembayaran->id_pembayaran) }}" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('Tolak pembayaran ini?')"
                                    class="w-full bg-red-600 text-white py-2 rounded-lg text-sm hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-400 text-center py-2">Belum ada bukti pembayaran</p>
                @endif
            </div>
        @endif

        <a href="{{ route('admin.pesanan.index') }}" class="block text-center text-gray-500 text-sm hover:text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>
</div>
@endsection
