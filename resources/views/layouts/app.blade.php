<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Kantin Biru — UMKM Kuliner Lhokseumawe, camilan lezat dengan harga terjangkau">
    <title>@yield('title', 'Kantin Biru')</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
                            400: '#facc15',
                            500: '#eab308',
                        }
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0,0,0,.06), 0 1px 2px -1px rgba(0,0,0,.06)',
                        'card-hover': '0 4px 12px 0 rgba(37,99,235,.12)',
                    }
                }
            }
        }
    </script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }

        /* Scrollbar Custom */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }

        /* Navbar glassmorphism saat scroll */
        .navbar-scrolled {
            background: rgba(37, 99, 235, 0.95) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Toast animations */
        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(120%); opacity: 0; }
        }
        .toast-enter { animation: slideInRight 0.35s cubic-bezier(.21,1.02,.73,1) forwards; }
        .toast-exit  { animation: slideOutRight 0.35s ease-in forwards; }

        /* Dropdown animation */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-8px) scale(.97); }
            to   { opacity: 1; transform: translateY(0)   scale(1);    }
        }
        .dropdown-menu { animation: fadeDown 0.2s ease forwards; }

        /* Mobile menu slide */
        @keyframes slideDown {
            from { opacity: 0; max-height: 0; }
            to   { opacity: 1; max-height: 500px; }
        }
        .mobile-menu-open { animation: slideDown 0.3s ease forwards; }

        /* Floating cart pulse */
        @keyframes pulse-ring {
            0%   { transform: scale(.9);  opacity: .8; }
            70%  { transform: scale(1.1); opacity: 0;  }
            100% { transform: scale(.9);  opacity: 0;  }
        }
        .cart-pulse::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: #2563eb;
            animation: pulse-ring 1.8s ease-out infinite;
        }

        /* Smooth page transitions */
        main { animation: fadeIn .25s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen flex flex-col text-slate-900">

    {{-- ===== NAVBAR ===== --}}
    <nav id="navbar" class="bg-primary-600 text-white shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="{{ route('pelanggan.home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm overflow-hidden shrink-0 group-hover:scale-105 transition-transform duration-200">
                        <img src="{{ asset('images/logokantinbiru.jpeg') }}" alt="Kantin Biru" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <span class="text-base font-bold tracking-tight block">Kantin Biru</span>
                        <span class="text-blue-200 text-[10px] font-medium block -mt-0.5">UMKM Kuliner</span>
                    </div>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('pelanggan.home') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition-colors duration-150 {{ request()->routeIs('pelanggan.home') ? 'bg-white/15' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('pelanggan.home') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition-colors duration-150">
                        Produk
                    </a>
                </div>

                {{-- Desktop Right Actions --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isPelanggan())

                            {{-- Cart Icon --}}
                            @php
                                $jumlahKeranjang = \App\Models\Keranjang::where('id_user', auth()->id())->count();
                            @endphp
                            <a href="{{ route('keranjang.index') }}"
                               class="relative p-2 rounded-xl hover:bg-white/10 transition-colors duration-150 group"
                               aria-label="Keranjang Belanja">
                                <i class="fas fa-shopping-cart text-lg"></i>
                                @if($jumlahKeranjang > 0)
                                    <span class="absolute -top-0.5 -right-0.5 bg-accent-400 text-slate-900 text-[10px] font-bold rounded-full w-4.5 h-4.5 flex items-center justify-center leading-none px-1 min-w-[18px] min-h-[18px]">
                                        {{ $jumlahKeranjang > 9 ? '9+' : $jumlahKeranjang }}
                                    </span>
                                @endif
                            </a>

                            {{-- User Dropdown --}}
                            <div class="relative" id="user-dropdown-wrapper">
                                <button id="user-dropdown-btn"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-white/10 transition-colors duration-150 text-sm font-medium">
                                    <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                    </div>
                                    <span class="max-w-24 truncate">{{ auth()->user()->nama }}</span>
                                    <i class="fas fa-chevron-down text-xs text-blue-200 transition-transform duration-200" id="dropdown-chevron"></i>
                                </button>

                                <div id="user-dropdown-menu"
                                     class="dropdown-menu absolute right-0 mt-2 w-52 bg-white text-slate-800 rounded-2xl shadow-xl border border-slate-100 py-1.5 hidden overflow-hidden"
                                     role="menu">
                                    <div class="px-4 py-2.5 border-b border-slate-100 mb-1">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Akun Saya</p>
                                        <p class="text-sm font-semibold text-slate-800 truncate mt-0.5">{{ auth()->user()->nama }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('pesanan.index') }}"
                                       class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-50 transition-colors duration-150" role="menuitem">
                                        <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box-open text-primary-600 text-xs"></i>
                                        </div>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('profil.index') }}"
                                       class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-50 transition-colors duration-150" role="menuitem">
                                        <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user text-primary-600 text-xs"></i>
                                        </div>
                                        Profil
                                    </a>
                                    <div class="border-t border-slate-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 w-full text-left transition-colors duration-150" role="menuitem">
                                                <div class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-sign-out-alt text-red-500 text-xs"></i>
                                                </div>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-sm font-medium text-white hover:text-blue-200 transition-colors duration-150">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-accent-400 hover:bg-accent-500 text-slate-900 text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow">
                            Daftar
                        </a>
                    @endauth
                </div>

                {{-- Mobile Hamburger --}}
                <button id="mobile-menu-btn"
                        aria-label="Buka menu"
                        aria-expanded="false"
                        class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors duration-150">
                    <i class="fas fa-bars text-lg" id="hamburger-icon"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('pelanggan.home') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-sm font-medium transition-colors duration-150">
                    <i class="fas fa-home w-4 text-center text-blue-200"></i> Beranda
                </a>
                <a href="{{ route('pelanggan.home') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-sm font-medium transition-colors duration-150">
                    <i class="fas fa-store w-4 text-center text-blue-200"></i> Produk
                </a>

                @auth
                    @if(auth()->user()->isPelanggan())
                        <a href="{{ route('keranjang.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-sm font-medium transition-colors duration-150">
                            <i class="fas fa-shopping-cart w-4 text-center text-blue-200"></i>
                            Keranjang
                            @if(isset($jumlahKeranjang) && $jumlahKeranjang > 0)
                                <span class="ml-auto bg-accent-400 text-slate-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $jumlahKeranjang }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pesanan.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-sm font-medium transition-colors duration-150">
                            <i class="fas fa-box-open w-4 text-center text-blue-200"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('profil.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 text-sm font-medium transition-colors duration-150">
                            <i class="fas fa-user w-4 text-center text-blue-200"></i> Profil
                        </a>
                        <div class="border-t border-white/10 pt-2 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-500/20 text-sm font-medium text-red-300 transition-colors duration-150 w-full">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/10 mt-1">
                        <a href="{{ route('login') }}"
                           class="text-center py-2.5 rounded-xl border border-white/30 text-sm font-medium hover:bg-white/10 transition-colors duration-150">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-center py-2.5 rounded-xl bg-accent-400 hover:bg-accent-500 text-slate-900 text-sm font-semibold transition-colors duration-150">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ===== TOAST NOTIFICATIONS ===== --}}
    <div id="toast-container" class="fixed top-5 right-4 z-[100] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===== FLOATING CART BUTTON (Mobile Only) ===== --}}
    @auth
        @if(auth()->user()->isPelanggan())
            @php $fcJumlah = \App\Models\Keranjang::where('id_user', auth()->id())->count(); @endphp
            @if($fcJumlah > 0)
                <a href="{{ route('keranjang.index') }}"
                   class="fixed bottom-6 right-5 md:hidden z-40 w-14 h-14 bg-primary-600 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-primary-700 transition-all duration-200 relative cart-pulse"
                   aria-label="Buka keranjang">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-accent-400 text-slate-900 text-[10px] font-bold rounded-full min-w-[18px] min-h-[18px] flex items-center justify-center px-1">
                        {{ $fcJumlah > 9 ? '9+' : $fcJumlah }}
                    </span>
                </a>
            @endif
        @endif
    @endauth

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-primary-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">

                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 bg-white rounded-xl overflow-hidden shrink-0">
                            <img src="{{ asset('images/logokantinbiru.jpeg') }}" alt="Kantin Biru" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-lg leading-tight">Kantin Biru</p>
                            <p class="text-blue-300 text-xs">UMKM Kuliner Sejak 2019</p>
                        </div>
                    </div>
                    <p class="text-blue-200 text-sm leading-relaxed max-w-md mb-3">
                        Kantin Biru hadir sebagai pilihan utama bagi pecinta camilan dengan menawarkan berbagai menu berkualitas — perpaduan cita rasa manis, asin, dan gurih yang lezat, cocok dinikmati semua kalangan.
                    </p>
                    <p class="text-blue-300 text-sm">
                        Dengan harga terjangkau, kami berkomitmen menyajikan camilan nikmat dan berkualitas, terutama untuk mahasiswa.
                    </p>
                </div>

                {{-- Info --}}
                <div>
                    <p class="font-semibold text-white mb-4 text-xs uppercase tracking-widest text-blue-300">Informasi</p>
                    <ul class="space-y-3 text-blue-200 text-sm">
                        <li class="flex items-start gap-2.5">
                            <i class="fas fa-map-marker-alt mt-0.5 text-accent-400 shrink-0"></i>
                            <a href="https://maps.app.goo.gl/t8jw781hDgQT2PMGA" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors duration-150">
                                Jl. Kampus Bukit Indah, Blang Pulo, Lhokseumawe
                            </a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="fas fa-user text-accent-400 shrink-0"></i>
                            <span>Pemilik: Chairina</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="fas fa-clock text-accent-400 shrink-0"></i>
                            <span>Senin – Sabtu, 09.00 – 18.00 WIB</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="fab fa-instagram text-accent-400 shrink-0"></i>
                            <a href="https://www.instagram.com/kantinbiru.lsm?igsh=MWN0d3JqaGhnaWRlMw=="
                               target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors duration-150">
                                @kantinbiru.lsm
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-5 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-blue-400 text-xs">
                    &copy; {{ date('Y') }} <span class="font-semibold text-blue-300">Kantin Biru</span>. All rights reserved.
                </p>
                <p class="text-blue-500 text-xs">
                    UMKM Kuliner Lhokseumawe, Aceh
                </p>
            </div>
        </div>
    </footer>

    {{-- ===== SCRIPTS ===== --}}
    <script>
    // ── Navbar scroll effect ──────────────────────────────────────────
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
    });

    // ── Mobile menu toggle ────────────────────────────────────────────
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu    = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');

    mobileMenuBtn?.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden');
        mobileMenuBtn.setAttribute('aria-expanded', String(!isOpen));
        hamburgerIcon.className = isOpen ? 'fas fa-bars text-lg' : 'fas fa-times text-lg';
        if (!isOpen) mobileMenu.classList.add('mobile-menu-open');
    });

    // ── User dropdown toggle ──────────────────────────────────────────
    const dropdownBtn  = document.getElementById('user-dropdown-btn');
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    const chevron      = document.getElementById('dropdown-chevron');

    function closeDropdown() {
        dropdownMenu?.classList.add('hidden');
        dropdownBtn?.setAttribute('aria-expanded', 'false');
        chevron?.classList.remove('rotate-180');
    }

    dropdownBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = dropdownMenu.classList.contains('hidden');
        dropdownMenu.classList.toggle('hidden');
        dropdownBtn.setAttribute('aria-expanded', String(isHidden));
        chevron.classList.toggle('rotate-180', isHidden);
    });

    document.addEventListener('click', closeDropdown);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDropdown(); });

    // ── Toast Notification System ─────────────────────────────────────
    function createToast(message, type = 'success') {
        const colors = {
            success: { bg: 'bg-emerald-50', border: 'border-emerald-200', text: 'text-emerald-800', icon: 'fa-circle-check text-emerald-500' },
            error:   { bg: 'bg-red-50',     border: 'border-red-200',     text: 'text-red-800',     icon: 'fa-circle-xmark text-red-500'     },
            info:    { bg: 'bg-blue-50',     border: 'border-blue-200',    text: 'text-blue-800',    icon: 'fa-circle-info text-blue-500'     },
            warning: { bg: 'bg-amber-50',    border: 'border-amber-200',   text: 'text-amber-800',   icon: 'fa-triangle-exclamation text-amber-500' },
        };
        const c = colors[type] || colors.success;
        const container = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = `toast-enter pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-2xl border shadow-lg max-w-sm w-full ${c.bg} ${c.border}`;
        toast.innerHTML = `
            <i class="fas ${c.icon} text-lg mt-0.5 shrink-0"></i>
            <p class="text-sm font-medium ${c.text} flex-1 leading-snug">${message}</p>
            <button onclick="dismissToast(this.parentElement)" class="shrink-0 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-xmark text-xs"></i>
            </button>
        `;
        container.appendChild(toast);

        // Auto dismiss
        setTimeout(() => dismissToast(toast), 4500);
    }

    function dismissToast(el) {
        if (!el || !el.parentElement) return;
        el.classList.remove('toast-enter');
        el.classList.add('toast-exit');
        setTimeout(() => el.remove(), 350);
    }

    // Fire toasts from session
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            createToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            createToast(@json(session('error')), 'error');
        @endif
        @if(session('info'))
            createToast(@json(session('info')), 'info');
        @endif
        @if(session('warning'))
            createToast(@json(session('warning')), 'warning');
        @endif
    });
    </script>

    @stack('scripts')
</body>
</html>
