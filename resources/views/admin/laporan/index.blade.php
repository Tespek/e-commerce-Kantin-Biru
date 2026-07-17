@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')

{{-- Filter Tanggal --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
        <input type="date" name="dari" value="{{ $dari }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
        <input type="date" name="sampai" value="{{ $sampai }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
        <i class="fas fa-search mr-1"></i>Tampilkan
    </button>
</form>

{{-- Stat --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Total Pendapatan</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Total Order Selesai</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrder }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    {{-- Grafik --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Grafik Penjualan Harian</h2>
        @if($grafikHarian->isNotEmpty())
            <canvas id="grafikLaporan" height="100"></canvas>
        @else
            <div class="text-center py-10 text-gray-400">
                <i class="fas fa-chart-bar text-4xl mb-2 text-gray-200"></i>
                <p>Tidak ada data pada periode ini</p>
            </div>
        @endif
    </div>

    {{-- Produk Terlaris --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Produk Terlaris</h2>
        <div class="space-y-3">
            @forelse($produkTerlaris as $i => $item)
                <div class="flex items-center gap-3 text-sm">
                    <span class="w-6 h-6 rounded-full bg-blue-700 text-white text-xs flex items-center justify-center font-bold flex-shrink-0">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ $item->nama_produk }}</p>
                        <p class="text-xs text-gray-400">{{ $item->total_terjual }} terjual</p>
                    </div>
                    <p class="text-xs font-semibold text-green-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada data</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Tabel Order --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b">
        <h2 class="font-bold text-gray-800">Daftar Pesanan Selesai</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Order</th>
                    <th class="px-5 py-3 text-left">Pelanggan</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Metode</th>
                    <th class="px-5 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium">#{{ $order->id_order }}</td>
                        <td class="px-5 py-3">{{ $order->user->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-3">{{ $order->pembayaran->metode ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
            @if($orders->isNotEmpty())
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-5 py-3 font-bold text-right">Total Pendapatan</td>
                        <td class="px-5 py-3 font-bold text-right text-blue-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

@push('scripts')
<script>
@if($grafikHarian->isNotEmpty())
new Chart(document.getElementById('grafikLaporan'), {
    type: 'bar',
    data: {
        labels: @json($grafikHarian->pluck('tanggal')),
        datasets: [{
            label: 'Pendapatan',
            data: @json($grafikHarian->pluck('total')),
            backgroundColor: 'rgba(30, 64, 175, 0.7)',
            borderColor: '#1e40af',
            borderWidth: 1,
            borderRadius: 4,
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
@endif
</script>
@endpush
@endsection
