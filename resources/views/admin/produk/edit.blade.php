@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.produk.index') }}"
           class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-primary-600 hover:border-primary-300 transition-all duration-150 shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <p class="text-slate-500 text-sm">Kembali ke daftar produk</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-pen text-amber-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800">Edit Produk</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $produk->nama_produk }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl mb-5 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1.5">
                    <i class="fas fa-circle-exclamation text-red-500"></i>Terdapat kesalahan:
                </div>
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1.5"><i class="fas fa-circle text-red-400 text-[6px]"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.produk.update', $produk->id_produk) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ (old('id_kategori', $produk->id_kategori) == $kat->id_kategori) ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <select name="status" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 cursor-pointer">
                        <option value="aktif"    {{ old('status', $produk->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $produk->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm font-medium pointer-events-none">Rp</span>
                        <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" required min="0"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required min="0"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150 resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Produk</label>
                    @if($produk->foto)
                        <div class="mb-3 bg-slate-50 rounded-xl p-4 border border-slate-200 flex items-start gap-4">
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="Foto saat ini" class="max-h-24 rounded-xl border border-slate-200 shadow-sm object-contain">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Foto saat ini</p>
                                <p class="text-xs text-slate-400 mt-0.5">Upload baru untuk mengganti</p>
                            </div>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-slate-300 hover:border-primary-400 rounded-xl p-5 text-center transition-all duration-200 cursor-pointer bg-slate-50 hover:bg-primary-50"
                         onclick="document.getElementById('foto-edit-input').click()">
                        <div id="upload-ph">
                            <i class="fas fa-cloud-arrow-up text-2xl text-slate-300 mb-2"></i>
                            <p class="text-sm text-slate-500 font-medium">Klik untuk upload foto baru</p>
                            <p class="text-xs text-slate-400">JPG, JPEG, PNG, WEBP • Maks 2MB</p>
                        </div>
                        <div id="upload-prev" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-h-32 mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
                            <p class="text-xs text-emerald-600 mt-2 font-medium" id="file-name-span"></p>
                        </div>
                    </div>
                    <input type="file" name="foto" id="foto-edit-input" accept="image/*" class="hidden" onchange="previewImg(this)">
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100 mt-2">
                <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fas fa-floppy-disk mr-2"></i>Update Produk
                </button>
                <a href="{{ route('admin.produk.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium px-6 py-2.5 rounded-xl text-sm transition-colors duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('upload-ph').classList.add('hidden');
            document.getElementById('upload-prev').classList.remove('hidden');
            document.getElementById('file-name-span').textContent = '✓ ' + input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
