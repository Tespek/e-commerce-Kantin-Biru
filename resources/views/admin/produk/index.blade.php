@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')

@section('content')

{{-- Filter Bar --}}
<form method="GET" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Cari Produk</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                       placeholder="Nama produk...">
            </div>
        </div>
        <div class="min-w-36">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Kategori</label>
            <select name="kategori"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="min-w-32">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Status</label>
            <select name="status"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                <i class="fas fa-filter mr-1.5"></i>Filter
            </button>
            <a href="{{ route('admin.produk.index') }}"
               class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium px-4 py-2.5 rounded-xl text-sm transition-colors duration-150">
                Reset
            </a>
        </div>
        <a href="{{ route('admin.produk.create') }}"
           class="ml-auto bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow flex items-center gap-2">
            <i class="fas fa-plus"></i>Tambah Produk
        </a>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Produk</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Harga</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Stok</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($produks as $produk)
                    <tr class="hover:bg-slate-50/70 transition-colors duration-100">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                    @if($produk->foto)
                                        <img src="{{ asset('storage/' . $produk->foto) }}" class="w-full h-full object-cover" alt="{{ $produk->nama_produk }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i class="fas fa-image text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <span class="font-semibold text-slate-800">{{ $produk->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-primary-50 text-primary-700 border border-primary-100 text-xs font-medium px-2.5 py-1 rounded-full">
                                {{ $produk->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-800">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            @if($produk->stok == 0)
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-circle-xmark text-[10px]"></i>Habis
                                </span>
                            @elseif($produk->stok <= 5)
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i>{{ $produk->stok }}
                                </span>
                            @else
                                <span class="text-slate-700 font-semibold">{{ $produk->stok }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border
                                {{ $produk->status === 'aktif'
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $produk->status === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ ucfirst($produk->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.produk.edit', $produk->id_produk) }}"
                                   class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150">
                                    <i class="fas fa-pen text-[10px]"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('admin.produk.destroy', $produk->id_produk) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Hapus produk \"{{ $produk->nama_produk }}\"?')"
                                            class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150">
                                        <i class="fas fa-trash text-[10px]"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-box text-slate-300 text-2xl"></i>
                            </div>
                            <p class="font-medium text-slate-500 mb-1">Tidak ada produk ditemukan</p>
                            <a href="{{ route('admin.produk.create') }}"
                               class="text-sm text-primary-600 hover:underline font-medium">Tambah produk baru</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($produks->hasPages())
        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50">
            {{ $produks->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
