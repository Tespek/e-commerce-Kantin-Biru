@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1">Cari Pelanggan</label>
        <input type="text" name="search" value="{{ request('search') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Nama pelanggan...">
    </div>
    <div class="min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Semua</option>
            @foreach(['pending','dibayar','diproses','selesai','dibatalkan'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
        <i class="fas fa-filter mr-1"></i>Filter
    </button>
    <a href="{{ route('admin.pesanan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Order</th>
                    <th class="px-5 py-3 text-left">Pelanggan</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Pembayaran</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    @php
                        $sc = match($order->status) {
                            'pending'    => 'bg-yellow-100 text-yellow-700',
                            'dibayar'    => 'bg-blue-100 text-blue-700',
                            'diproses'   => 'bg-indigo-100 text-indigo-700',
                            'selesai'    => 'bg-green-100 text-green-700',
                            'dibatalkan' => 'bg-red-100 text-red-700',
                            default      => 'bg-gray-100 text-gray-700',
                        };
                        $pc = match($order->pembayaran?->status) {
                            'diterima' => 'text-green-600',
                            'ditolak'  => 'text-red-600',
                            default    => 'text-yellow-600',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium">#{{ $order->id_order }}</td>
                        <td class="px-5 py-3">{{ $order->user->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-3 font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ $pc }} text-xs font-medium">
                                {{ $order->pembayaran ? ucfirst($order->pembayaran->status) : '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sc }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.pesanan.detail', $order->id_order) }}"
                               class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs hover:bg-blue-200 transition">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">Tidak ada pesanan ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t">{{ $orders->links() }}</div>
</div>
@endsection
