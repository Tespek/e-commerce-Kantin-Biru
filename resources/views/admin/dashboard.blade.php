@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500 mb-1">Total Pendapatan</p>
        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>Pesanan selesai</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-indigo-500">
        <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
        <p class="text-xl font-bold text-gray-800">{{ $totalOrder }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua status</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-orange-500">
        <p class="text-sm text-gray-500 mb-1">Total Produk</p>
        <p class="text-xl font-bold text-gray-800">{{ $totalProduk }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua produk</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500 mb-1">Total Pelanggan</p>
        <p class="text-xl font-bold text-gray-800">{{ $totalPelanggan }}</p>
        <p class="text-xs text-gray-400 mt-1">Pengguna terdaftar</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    {{-- Grafik --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Penjualan 7 Hari Terakhir</h2>
        <canvas id="grafikPenjualan" height="100"></canvas>
    </div>

    {{-- Produk Terlaris --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Produk Terlaris</h2>
        <div class="space-y-3">
            @forelse($produkTerlaris as $i => $item)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-700 text-white text-xs flex items-center justify-center font-bold">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item->nama_produk }}</p>
                        <p class="text-xs text-gray-400">{{ $item->total_terjual }} terjual</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada data penjualan</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Order Terbaru --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-gray-800">Pesanan Terbaru</h2>
        <a href="{{ route('admin.pesanan.index') }}" class="text-blue-700 text-sm hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="pb-2 text-left">Order</th>
                    <th class="pb-2 text-left">Pelanggan</th>
                    <th class="pb-2 text-left">Total</th>
                    <th class="pb-2 text-left">Pembayaran</th>
                    <th class="pb-2 text-left">Status</th>
                    <th class="pb-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orderTerbaru as $order)
                    @php
                        $statusColor = match($order->status) {
                            'pending'    => 'bg-yellow-100 text-yellow-700',
                            'dibayar'    => 'bg-blue-100 text-blue-700',
                            'diproses'   => 'bg-indigo-100 text-indigo-700',
                            'selesai'    => 'bg-green-100 text-green-700',
                            'dibatalkan' => 'bg-red-100 text-red-700',
                            default      => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <tr>
                        <td class="py-3">#{{ $order->id_order }}</td>
                        <td class="py-3">{{ $order->user->nama ?? '-' }}</td>
                        <td class="py-3 font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="py-3">{{ $order->pembayaran->metode ?? '-' }}</td>
                        <td class="py-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-3">
                            <a href="{{ route('admin.pesanan.detail', $order->id_order) }}"
                               class="text-blue-700 hover:underline text-xs">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-gray-400">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const labels  = @json($grafikPenjualan->pluck('tanggal'));
const data    = @json($grafikPenjualan->pluck('total'));

new Chart(document.getElementById('grafikPenjualan'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data,
            borderColor: '#1e40af',
            backgroundColor: 'rgba(30, 64, 175, 0.08)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
        }
    }
});
</script>
@endpush
@endsection
