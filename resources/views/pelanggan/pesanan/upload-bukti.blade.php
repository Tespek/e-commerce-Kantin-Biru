@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran — Kantin Biru')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back + Title --}}
    <div class="flex items-center gap-3 mb-7">
        <a href="{{ route('pesanan.detail', $order->id_order) }}"
           class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-primary-600 hover:border-primary-300 transition-all duration-150 shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Upload Bukti Bayar</h1>
            <p class="text-slate-500 text-sm">Order #{{ $order->id_order }}</p>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-primary-50 border border-primary-200 rounded-2xl p-5 mb-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shrink-0">
                <i class="fas fa-info text-white text-sm"></i>
            </div>
            <p class="font-bold text-primary-800 text-sm">Informasi Pembayaran</p>
        </div>
        <div class="space-y-2 text-sm text-primary-700">
            <div class="flex justify-between">
                <span class="text-primary-500">Order:</span>
                <span class="font-semibold">#{{ $order->id_order }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-primary-500">Total:</span>
                <span class="font-bold text-primary-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-primary-500">Metode:</span>
                <span class="font-semibold">{{ $order->pembayaran->metode }}</span>
            </div>
        </div>

        @if($order->pembayaran->metode === 'Transfer Bank')
            <div class="mt-4 bg-white rounded-xl p-4 border border-primary-200">
                <p class="text-xs text-primary-400 mb-1 font-medium uppercase tracking-wide">Rekening Tujuan</p>
                <p class="font-bold text-slate-800">BSI 7284671238</p>
                <p class="text-sm text-slate-500">a/n Kantin Biru</p>
            </div>
        @elseif($order->pembayaran->metode === 'QRIS')
            <div class="mt-4 bg-white rounded-xl p-4 border border-primary-200 flex items-start gap-3">
                <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-qrcode text-violet-600"></i>
                </div>
                <p class="text-sm text-slate-600">Scan QR Code yang tersedia di kasir atau tampilkan ke petugas Kantin Biru.</p>
            </div>
        @endif
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl mb-5 text-sm">
            <div class="flex items-center gap-2 mb-2 font-semibold">
                <i class="fas fa-circle-exclamation text-red-500"></i>Terdapat kesalahan:
            </div>
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5 text-sm">
                    <i class="fas fa-circle text-red-400 text-[6px]"></i>{{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

        {{-- Current Proof --}}
        @if($order->pembayaran->bukti_pembayaran)
            <div class="mb-5 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-xs text-slate-400 font-medium mb-3 uppercase tracking-wide">Bukti Saat Ini</p>
                <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                     alt="Bukti lama"
                     class="max-w-full rounded-xl border border-slate-200 shadow-sm max-h-48 object-contain">
            </div>
        @endif

        <form method="POST" action="{{ route('pesanan.simpan-bukti', $order->id_order) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-3">
                    {{ $order->pembayaran->bukti_pembayaran ? 'Ganti Bukti Pembayaran' : 'Foto Bukti Pembayaran' }}
                </label>

                {{-- Upload Zone --}}
                <div id="drop-area"
                     class="border-2 border-dashed border-slate-300 hover:border-primary-400 rounded-2xl p-8 text-center transition-all duration-200 cursor-pointer bg-slate-50 hover:bg-primary-50"
                     onclick="document.getElementById('bukti_input').click()">
                    <div id="upload-placeholder">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-200">
                            <i class="fas fa-cloud-arrow-up text-3xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 mb-1">Klik atau seret file ke sini</p>
                        <p class="text-xs text-slate-400">JPG, JPEG, PNG — Maksimal 2MB</p>
                    </div>
                    <div id="upload-preview" class="hidden">
                        <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
                        <p class="text-xs text-slate-400 mt-3">
                            <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                            <span id="file-name-label">File dipilih</span>
                        </p>
                    </div>
                </div>

                <input type="file" name="bukti_pembayaran" id="bukti_input"
                       accept="image/jpg,image/jpeg,image/png"
                       required
                       class="hidden"
                       onchange="previewImage(this)">

                {{-- Or click button --}}
                <button type="button"
                        onclick="document.getElementById('bukti_input').click()"
                        class="w-full mt-3 flex items-center justify-center gap-2 text-sm text-primary-600 bg-primary-50 hover:bg-primary-100 border border-primary-200 font-semibold py-2.5 rounded-xl transition-all duration-150">
                    <i class="fas fa-folder-open"></i>Pilih dari Galeri
                </button>
            </div>

            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('upload-preview').classList.remove('hidden');
            document.getElementById('file-name-label').textContent = input.files[0].name;
            document.getElementById('drop-area').classList.add('border-primary-400','bg-primary-50');
            document.getElementById('drop-area').classList.remove('border-slate-300','bg-slate-50');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const dropArea = document.getElementById('drop-area');
dropArea.addEventListener('dragover',  (e) => { e.preventDefault(); dropArea.classList.add('border-primary-400','bg-primary-50'); });
dropArea.addEventListener('dragleave', () => { if (!document.getElementById('upload-preview').classList.contains('hidden')) return; dropArea.classList.remove('border-primary-400','bg-primary-50'); });
dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    const input = document.getElementById('bukti_input');
    input.files = e.dataTransfer.files;
    previewImage(input);
});
</script>
@endpush
@endsection
