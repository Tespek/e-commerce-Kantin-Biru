@extends('layouts.admin')
@section('title', 'Produk')
@section('page-title', 'Kelola Produk')

@section('content')
{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-40">
        <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
        <input type="text" name="search" value="{{ request('search') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Nama produk...">
    </div>
    <div class="min-w-36">
        <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
        <select name="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Semua</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="min-w-32">
        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Semua</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
        <i class="fas fa-filter mr-1"></i>Filter
    </button>
    <a href="{{ route('admin.produk.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition">Reset</a>
    <a href="{{ route('admin.produk.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition ml-auto">
        <i class="fas fa-plus mr-1"></i>Tambah Produk
    </a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Produk</th>
                <th class="px-5 py-3 text-left">Kategori</th>
                <th class="px-5 py-3 text-left">Harga</th>
                <th class="px-5 py-3 text-left">Stok</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($produks as $produk)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($produk->foto)
                                    <img src="{{ asset('storage/' . $produk->foto) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="font-medium text-gray-800">{{ $produk->nama_produk }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-5 py-3 font-medium">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="{{ $produk->stok <= 5 ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                            {{ $produk->stok }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $produk->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($produk->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.produk.edit', $produk->id_produk) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs hover:bg-yellow-200 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('admin.produk.destroy', $produk->id_produk) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus produk ini?')"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 transition">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Tidak ada produk ditemukan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t">{{ $produks->links() }}</div>
</div>
@endsection
