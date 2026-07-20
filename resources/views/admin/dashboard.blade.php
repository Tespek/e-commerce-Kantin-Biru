@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group hover:shadow-md transition-shadow duration-200">
        <div class="absolute top-0 right-0 w-20 h-20 bg-primary-50 rounded-full -translate-y-6 translate-x-6"></div>
        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mb-3 relative z-10">
            <i class="fas fa-sack-dollar text-primary-600 text-lg"></i>
        </div>
        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pendapatan</p>
        <p class="text-xl font-bold text-slate-900 leading-tight">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p class="text-xs text-emerald-600 mt-1.5 font-medium">
            <i class="fas fa-arrow-trend-up mr-1"></i>Dari pesanan selesai
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group hover:shadow-md transition-shadow duration-200">
        <div class="absolute top-0 right-0 w-20 h-20 bg-violet-50 rounded-full -translate-y-6 translate-x-6"></div>
        <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-shopping-bag text-violet-600 text-lg"></i>
        </div>
        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pesanan</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalOrder }}</p>
        <p class="text-xs text-slate-400 mt-1.5 font-medium">Semua status pesanan</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group hover:shadow-md transition-shadow duration-200">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-full -translate-y-6 translate-x-6"></div>
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-box-open text-amber-600 text-lg"></i>
        </div>
        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Produk</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalProduk }}</p>
        <p class="text-xs text-slate-400 mt-1.5 font-medium">Aktif & nonaktif</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group hover:shadow-md transition-shadow duration-200">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-full -translate-y-6 translate-x-6"></div>
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
            <i class="fas fa-users text-emerald-600 text-lg"></i>
        </div>
        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pelanggan</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalPelanggan }}</p>
        <p class="text-xs text-slate-400 mt-1.5 font-medium">Pengguna terdaftar</p>
    </div>
</div>

{{-- Chart + Top Products --}}
<div class="grid lg:grid-cols-3 gap-5 mb-5">

    {{-- Sales Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-slate-800">Penjualan 7 Hari Terakhir</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pendapatan harian</p>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-primary-600 bg-primary-50 border border-primary-100 px-3 py-1.5 rounded-full font-medium">
                <i class="fas fa-chart-line"></i>
                Minggu Ini
            </div>
        </div>
        <canvas id="grafikPenjualan" height="110"></canvas>
    </div>

    {{-- Top Products --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-slate-800">Produk Terlaris</h2>
                <p class="text-xs text-slate-400 mt-0.5">Top 5 produk</p>
            </div>
            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-fire text-amber-500 text-sm"></i>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($produkTerlaris as $i => $item)
                <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors duration-150">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold shrink-0
                        {{ $i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-slate-300 text-white' : ($i === 2 ? 'bg-orange-300 text-white' : 'bg-primary-100 text-primary-700')) }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $item->nama_produk }}</p>
                        <p class="text-xs text-slate-400">{{ $item->total_terjual }} terjual</p>
                    </div>
                    {{-- Simple bar --}}
                    <div class="w-16 bg-slate-100 rounded-full h-1.5 shrink-0">
                        <div class="bg-primary-500 h-1.5 rounded-full"
                             style="width: {{ $produkTerlaris->first()->total_terjual > 0 ? ($item->total_terjual / $produkTerlaris->first()->total_terjual * 100) : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-bar text-slate-300 text-lg"></i>
                    </div>
                    <p class="text-sm text-slate-400">Belum ada data penjualan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-slate-800">Pesanan Terbaru</h2>
            <p class="text-xs text-slate-400 mt-0.5">10 pesanan terakhir masuk</p>
        </div>
        <a href="{{ route('admin.pesanan.index') }}"
           class="text-sm text-primary-600 hover:text-primary-700 font-semibold hover:underline transition-colors duration-150">
            Lihat Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Order</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Pelanggan</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Total</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Pembayaran</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($orderTerbaru as $order)
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
                    <tr class="hover:bg-slate-50/70 transition-colors duration-100">
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-800">#{{ $order->id_order }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-primary-100 rounded-lg flex items-center justify-center text-xs font-bold text-primary-700 shrink-0">
                                    {{ strtoupper(substr($order->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-slate-700 font-medium">{{ $order->user->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-slate-500">{{ $order->pembayaran->metode ?? '-' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.pesanan.detail', $order->id_order) }}"
                               class="inline-flex items-center gap-1.5 text-xs bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 font-semibold px-3 py-1.5 rounded-lg transition-colors duration-150">
                                <i class="fas fa-eye text-[10px]"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-slate-300 text-xl"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Belum ada pesanan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const labels = @json($grafikPenjualan->pluck('tanggal'));
const data   = @json($grafikPenjualan->pluck('total'));

new Chart(document.getElementById('grafikPenjualan'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data,
            borderColor: '#2563eb',
            backgroundColor: (ctx) => {
                const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                gradient.addColorStop(0, 'rgba(37,99,235,0.18)');
                gradient.addColorStop(1, 'rgba(37,99,235,0)');
                return gradient;
            },
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#2563eb',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#94a3b8',
                bodyColor: '#f1f5f9',
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: v => ' Rp ' + Number(v.raw).toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
            y: {
                grid: { color: '#f1f5f9' },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11 },
                    callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endpush
@endsection
