@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')

{{-- Filter Bar --}}
<form method="GET" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Cari Pelanggan</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                       placeholder="Nama pelanggan...">
            </div>
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Status Pesanan</label>
            <select name="status"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                <option value="">Semua Status</option>
                @foreach(['pending','dibayar','diproses','selesai','dibatalkan'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                <i class="fas fa-filter mr-1.5"></i>Filter
            </button>
            <a href="{{ route('admin.pesanan.index') }}"
               class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium px-4 py-2.5 rounded-xl text-sm transition-colors duration-150">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Order</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pelanggan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Total</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pembayaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orders as $order)
                    @php
                        $sc = match($order->status) {
                            'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
                            'dibayar'    => 'bg-blue-50 text-blue-700 border-blue-200',
                            'diproses'   => 'bg-violet-50 text-violet-700 border-violet-200',
                            'selesai'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                            default      => 'bg-slate-50 text-slate-700 border-slate-200',
                        };
                        $pc = match($order->pembayaran?->status) {
                            'diterima' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'ditolak'  => 'bg-red-50 text-red-700 border-red-200',
                            default    => 'bg-amber-50 text-amber-700 border-amber-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors duration-100">
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-800">#{{ $order->id_order }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center text-xs font-bold text-primary-700 shrink-0">
                                    {{ strtoupper(substr($order->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-700">{{ $order->user->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-500 text-xs">
                            {{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-800">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            @if($order->pembayaran)
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-slate-500">{{ $order->pembayaran->metode }}</span>
                                    <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-full border {{ $pc }}">
                                        {{ ucfirst($order->pembayaran->status) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.pesanan.detail', $order->id_order) }}"
                               class="inline-flex items-center gap-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors duration-150">
                                <i class="fas fa-eye text-[10px]"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-shopping-bag text-slate-300 text-2xl"></i>
                            </div>
                            <p class="font-medium text-slate-500">Tidak ada pesanan ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
