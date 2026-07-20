@extends('layouts.app')

@section('title', 'Profil Saya — Kantin Biru')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Profile Header Card --}}
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <div class="relative z-10 flex items-center gap-5">
            {{-- Avatar --}}
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-bold border-2 border-white/30 shadow-lg shrink-0">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $user->nama }}</h1>
                <p class="text-blue-200 text-sm mt-0.5">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="bg-white/15 border border-white/25 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-user mr-1.5"></i>Pelanggan
                    </span>
                    @if($user->no_hp)
                        <span class="bg-white/15 border border-white/25 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            <i class="fas fa-phone mr-1.5"></i>{{ $user->no_hp }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-white rounded-2xl shadow-sm border border-slate-100 p-1.5 mb-6" role="tablist">
        <button id="tab-info" role="tab" aria-selected="true"
                onclick="switchTab('info')"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-150 bg-primary-600 text-white shadow-sm">
            <i class="fas fa-user text-xs"></i>
            <span class="hidden sm:inline">Informasi Akun</span>
            <span class="sm:hidden">Info</span>
        </button>
        <button id="tab-password" role="tab" aria-selected="false"
                onclick="switchTab('password')"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-150 text-slate-500 hover:text-slate-700 hover:bg-slate-50">
            <i class="fas fa-lock text-xs"></i>
            <span class="hidden sm:inline">Ganti Password</span>
            <span class="sm:hidden">Password</span>
        </button>
    </div>

    {{-- Tab: Info Akun --}}
    <div id="panel-info">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="font-bold text-slate-800 text-base mb-5 flex items-center gap-2">
                <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-primary-600 text-xs"></i>
                </div>
                Informasi Akun
            </h2>

            @php
                $profileErrors = !$errors->hasBag('password') && $errors->any();
            @endphp

            @if($profileErrors)
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl mb-5 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-1.5">
                        <i class="fas fa-circle-exclamation text-red-500"></i>Terdapat kesalahan:
                    </div>
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1.5"><i class="fas fa-circle text-red-400 text-[6px]"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profil.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            No. HP <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-phone text-sm"></i>
                            </span>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                    <div class="relative">
                        <span class="absolute top-3 left-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                        </span>
                        <textarea name="alamat" rows="3"
                                  class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150 resize-none"
                                  placeholder="Alamat lengkap...">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-floppy-disk mr-2"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Tab: Ganti Password --}}
    <div id="panel-password" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="font-bold text-slate-800 text-base mb-5 flex items-center gap-2">
                <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-lock text-slate-600 text-xs"></i>
                </div>
                Ganti Password
            </h2>

            @if($errors->hasBag('password'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl mb-5 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-1.5">
                        <i class="fas fa-circle-exclamation text-red-500"></i>Terdapat kesalahan:
                    </div>
                    @foreach($errors->getBag('password')->all() as $error)
                        <p class="flex items-center gap-1.5"><i class="fas fa-circle text-red-400 text-[6px]"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5 text-sm text-amber-700 flex items-start gap-3">
                <i class="fas fa-shield-halved text-amber-500 mt-0.5 shrink-0"></i>
                <p>Gunakan password yang kuat dengan kombinasi huruf besar, kecil, angka, dan simbol.</p>
            </div>

            <form method="POST" action="{{ route('profil.password') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Lama</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password_lama" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                               placeholder="Password saat ini">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-key text-sm"></i>
                            </span>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                                   placeholder="Min. 8 karakter">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-key text-sm"></i>
                            </span>
                            <input type="password" name="password_confirmation" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-150"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="bg-slate-800 hover:bg-slate-900 text-white font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-shield-halved mr-2"></i>Perbarui Password
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    const tabs   = ['info', 'password'];
    const active = 'bg-primary-600 text-white shadow-sm';
    const inactive = 'text-slate-500 hover:text-slate-700 hover:bg-slate-50';

    tabs.forEach(t => {
        const btn   = document.getElementById('tab-' + t);
        const panel = document.getElementById('panel-' + t);
        if (t === tab) {
            btn.className = btn.className.replace(inactive, '').trim() + ' ' + active;
            btn.setAttribute('aria-selected', 'true');
            panel.classList.remove('hidden');
        } else {
            btn.className = btn.className.replace(active, '').trim() + ' ' + inactive;
            btn.setAttribute('aria-selected', 'false');
            panel.classList.add('hidden');
        }
    });
}

// Auto-switch to password tab if there are password errors
@if($errors->hasBag('password'))
    document.addEventListener('DOMContentLoaded', () => switchTab('password'));
@endif
</script>
@endpush
@endsection
