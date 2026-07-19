@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pesanan.detail', $order->id_order) }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Upload Bukti Pembayaran</h1>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5 text-sm text-blue-700">
        <p class="font-semibold mb-1"><i class="fas fa-info-circle mr-1"></i>Informasi Pembayaran</p>
        <p>Order #{{ $order->id_order }} • Total: <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></p>
        <p class="mt-1">Metode: <strong>{{ $order->pembayaran->metode }}</strong></p>
        @if($order->pembayaran->metode === 'Transfer Bank')
            <p class="mt-2">Rekening tujuan: <strong>BSI 7284671238 a/n Kantin Biru</strong></p>
        @elseif($order->pembayaran->metode === 'QRIS')
            <p class="mt-2">Scan QR Code yang tersedia di kasir atau tampilkan ke petugas.</p>
        @endif
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-5 text-sm">
            @foreach($errors->all() as $error)<p><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('pesanan.simpan-bukti', $order->id_order) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti Pembayaran</label>

                {{-- Bukti lama --}}
                @if($order->pembayaran->bukti_pembayaran)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Bukti saat ini:</p>
                        <img src="{{ asset('storage/' . $order->pembayaran->bukti_pembayaran) }}"
                             alt="Bukti lama" class="max-w-xs rounded-lg border">
                    </div>
                @endif

                {{-- Upload area --}}
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition" id="drop-area">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500 mb-2">Drag & drop atau klik untuk memilih file</p>
                    <p class="text-xs text-gray-400">JPG, JPEG, PNG • Maksimal 2MB</p>
                    <input type="file" name="bukti_pembayaran" id="bukti_input" accept="image/jpg,image/jpeg,image/png" required
                        class="hidden" onchange="previewImage(this)">
                    <label for="bukti_input" class="mt-3 inline-block bg-blue-700 text-white px-4 py-2 rounded-lg text-sm cursor-pointer hover:bg-blue-800 transition">
                        Pilih Gambar
                    </label>
                </div>

                {{-- Preview --}}
                <div id="preview-container" class="mt-3 hidden">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    <img id="preview-img" src="" alt="Preview" class="max-w-xs rounded-lg border">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-700 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition">
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
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const dropArea = document.getElementById('drop-area');
dropArea.addEventListener('click', () => document.getElementById('bukti_input').click());
dropArea.addEventListener('dragover', (e) => { e.preventDefault(); dropArea.classList.add('border-blue-400'); });
dropArea.addEventListener('dragleave', () => dropArea.classList.remove('border-blue-400'));
dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('border-blue-400');
    const input = document.getElementById('bukti_input');
    input.files = e.dataTransfer.files;
    previewImage(input);
});
</script>
@endpush
@endsection
