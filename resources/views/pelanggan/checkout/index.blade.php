@extends('layouts.app')

@section('title', 'Checkout — Kantin Biru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-7">
        <h1 class="text-2xl font-bold text-slate-900">
            <i class="fas fa-credit-card text-primary-600 mr-2"></i>Checkout
        </h1>
        <p class="text-slate-500 text-sm mt-0.5">Lengkapi data pengiriman dan metode pembayaran</p>
    </div>

    {{-- Checkout Progress --}}
    <div class="flex items-center justify-center mb-8 gap-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span class="text-sm font-medium text-emerald-600 hidden sm:block">Keranjang</span>
        </div>
        <div class="flex-1 max-w-16 h-0.5 bg-primary-200 mx-2"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">2</div>
            <span class="text-sm font-semibold text-primary-600 hidden sm:block">Checkout</span>
        </div>
        <div class="flex-1 max-w-16 h-0.5 bg-slate-200 mx-2"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center font-bold text-sm">3</div>
            <span class="text-sm font-medium text-slate-400 hidden sm:block">Selesai</span>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 text-sm">
            <div class="flex items-center gap-2 mb-2 font-semibold">
                <i class="fas fa-circle-exclamation text-red-500"></i>
                Terdapat kesalahan:
            </div>
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5"><i class="fas fa-circle text-red-400 text-[6px]"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.proses') }}">
        @csrf
        <div class="grid md:grid-cols-3 gap-6">

            {{-- LEFT: Form --}}
            <div class="md:col-span-2 space-y-5">

                {{-- Shipping Address --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h2 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-primary-600 text-sm"></i>
                        </div>
                        Alamat Pengiriman
                    </h2>
                    <textarea name="alamat_pengiriman" rows="4" required
                              class="w-full border border-slate-200 rounded-xl p-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-slate-50 focus:bg-white transition-all duration-150 resize-none text-slate-700 placeholder-slate-400"
                              placeholder="Masukkan alamat lengkap — nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota...">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                    <p class="text-xs text-slate-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Pastikan alamat lengkap dan dapat dijangkau kurir
                    </p>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h2 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-primary-600 text-sm"></i>
                        </div>
                        Metode Pembayaran
                    </h2>

                    <div class="space-y-3">
                        @php
                            $methods = [
                                'Transfer Bank' => [
                                    'icon'  => 'fa-university',
                                    'color' => 'text-blue-600',
                                    'bg'    => 'bg-blue-50',
                                    'desc'  => 'Transfer ke rekening BSI 7284671238 a/n Kantin Biru'
                                ],
                                'QRIS' => [
                                    'icon'  => 'fa-qrcode',
                                    'color' => 'text-violet-600',
                                    'bg'    => 'bg-violet-50',
                                    'desc'  => 'Scan QR Code dengan GoPay, OVO, DANA, atau m-Banking'
                                ],
                                'COD' => [
                                    'icon'  => 'fa-money-bill-wave',
                                    'color' => 'text-emerald-600',
                                    'bg'    => 'bg-emerald-50',
                                    'desc'  => 'Bayar tunai saat pesanan diterima'
                                ],
                            ];
                        @endphp

                        @foreach($methods as $metode => $info)
                            <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-150 hover:border-primary-300
                                          {{ (old('metode_pembayaran', 'Transfer Bank') === $metode) ? 'border-primary-500 bg-primary-50' : 'border-slate-200 hover:bg-slate-50' }}"
                                   id="label-{{ Str::slug($metode) }}">
                                <input type="radio" name="metode_pembayaran" value="{{ $metode }}"
                                       {{ old('metode_pembayaran', 'Transfer Bank') === $metode ? 'checked' : '' }}
                                       class="text-primary-600 w-4 h-4 focus:ring-primary-500"
                                       onchange="updatePaymentStyle()">
                                <div class="w-10 h-10 {{ $info['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas {{ $info['icon'] }} {{ $info['color'] }} text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 text-sm">{{ $metode }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $info['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sticky top-24">
                    <h2 class="font-bold text-slate-800 text-base mb-4">Ringkasan Pesanan</h2>

                    {{-- Items --}}
                    <div class="space-y-2.5 mb-4 max-h-52 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            <div class="flex gap-3 items-start">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg overflow-hidden shrink-0">
                                    @if($item->produk->foto)
                                        <img src="{{ asset('storage/' . $item->produk->foto) }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-600 font-medium truncate">{{ $item->produk->nama_produk }}</p>
                                    <p class="text-xs text-slate-400">×{{ $item->jumlah }}</p>
                                </div>
                                <p class="text-xs font-bold text-slate-700 shrink-0">
                                    Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="h-px bg-slate-100 my-3"></div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-4">
                        <span class="text-slate-500">Biaya Pengiriman</span>
                        <span class="text-emerald-600 font-medium">Gratis</span>
                    </div>
                    <div class="h-px bg-slate-100 mb-4"></div>
                    <div class="flex justify-between mb-6">
                        <span class="font-bold text-slate-800">Total Pembayaran</span>
                        <span class="font-bold text-xl text-primary-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit"
                            class="w-full bg-accent-400 hover:bg-accent-500 text-slate-900 font-bold py-3.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                        <i class="fas fa-check-circle mr-2"></i>Buat Pesanan Sekarang
                    </button>
                    <a href="{{ route('keranjang.index') }}"
                       class="block w-full text-center text-slate-400 hover:text-slate-600 py-2.5 text-sm font-medium transition-colors duration-150 mt-2">
                        <i class="fas fa-arrow-left mr-1.5 text-xs"></i>Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function updatePaymentStyle() {
    const methods = ['Transfer Bank', 'QRIS', 'COD'];
    methods.forEach(m => {
        const slug  = m.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'');
        const label = document.getElementById('label-' + slug);
        const radio = label?.querySelector('input[type=radio]');
        if (!label || !radio) return;
        if (radio.checked) {
            label.classList.add('border-primary-500','bg-primary-50');
            label.classList.remove('border-slate-200','hover:bg-slate-50');
        } else {
            label.classList.remove('border-primary-500','bg-primary-50');
            label.classList.add('border-slate-200','hover:bg-slate-50');
        }
    });
}
</script>
@endpush
@endsection
