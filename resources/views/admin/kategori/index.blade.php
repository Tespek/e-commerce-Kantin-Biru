@extends('layouts.admin')
@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $kategoris->total() }} kategori ditemukan</p>
    <a href="{{ route('admin.kategori.create') }}"
       class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition text-sm font-medium">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">No</th>
                <th class="px-5 py-3 text-left">Nama Kategori</th>
                <th class="px-5 py-3 text-left">Jumlah Produk</th>
                <th class="px-5 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($kategoris as $i => $kat)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500">{{ $kategoris->firstItem() + $i }}</td>
                    <td class="px-5 py-3 font-medium">{{ $kat->nama_kategori }}</td>
                    <td class="px-5 py-3">
                        <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs">
                            {{ $kat->produks_count }} produk
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.kategori.edit', $kat->id_kategori) }}"
                               class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs hover:bg-yellow-200 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('admin.kategori.destroy', $kat->id_kategori) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200 transition">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Belum ada kategori</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t">{{ $kategoris->links() }}</div>
</div>
@endsection
