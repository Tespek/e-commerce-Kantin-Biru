@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-5 text-sm">
                @foreach($errors->all() as $error)<p><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kategori.update', $kategori->id_kategori) }}">
            @csrf @method('PUT')
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-700 text-white px-5 py-2.5 rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('admin.kategori.index') }}"
                   class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
