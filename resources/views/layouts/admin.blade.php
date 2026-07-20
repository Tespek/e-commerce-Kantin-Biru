<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Kantin Biru</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        },
                        accent: { 400: '#facc15', 500: '#eab308' }
                    }
                }
            }
        }
    </script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #1e3a8a; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }

        /* Sidebar active glow */
        .nav-active {
            background: linear-gradient(90deg, rgba(37,99,235,.25) 0%, rgba(37,99,235,.08) 100%);
            border-left: 3px solid #facc15;
        }

        /* Toast */
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

        /* Sidebar overlay for mobile */
        #sidebar-overlay {
            transition: opacity .25s ease;
        }

        /* Animate page content */
        #main-content { animation: fadeIn .2s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900" style="font-family:'Inter',system-ui,sans-serif">

{{-- ===== MOBILE SIDEBAR OVERLAY ===== --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="sidebar"
           class="w-64 bg-primary-900 text-white flex flex-col flex-shrink-0 fixed lg:relative inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

        {{-- Logo / Brand --}}
        <div class="px-5 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl overflow-hidden shrink-0 shadow-md">
                    <img src="{{ asset('images/logokantinbiru.jpeg') }}" alt="Kantin Biru" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-base text-white leading-tight">Kantin Biru</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[10px] bg-accent-400 text-slate-900 font-bold px-2 py-0.5 rounded-full">ADMIN</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-blue-400">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.dashboard') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-chart-pie text-sm"></i>
                </div>
                Dashboard
            </a>

            <a href="{{ route('admin.produk.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.produk.*') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.produk.*') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-box text-sm"></i>
                </div>
                Produk
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.kategori.*') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-tags text-sm"></i>
                </div>
                Kategori
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.pesanan.*') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.pesanan.*') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-shopping-bag text-sm"></i>
                </div>
                Pesanan
            </a>

            <a href="{{ route('admin.user.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.user.*') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.user.*') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-users text-sm"></i>
                </div>
                Kelola User
            </a>

            <div class="pt-3 pb-1">
                <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-blue-400">Laporan</p>
            </div>

            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.laporan.*') ? 'nav-active text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.laporan.*') ? 'bg-primary-600' : 'bg-white/5' }} flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-chart-bar text-sm"></i>
                </div>
                Laporan Penjualan
            </a>
        </nav>

        {{-- User Profile Footer --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center font-bold text-sm shadow-md shrink-0">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-sm font-semibold truncate text-white">{{ auth()->user()->nama }}</p>
                    <p class="text-xs text-blue-300 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 text-xs bg-red-500/15 hover:bg-red-500/25 text-red-300 hover:text-red-200 py-2 rounded-xl transition-all duration-150 font-medium">
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar dari Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white shadow-sm border-b border-slate-100 px-5 py-3.5 flex items-center justify-between shrink-0 z-20">
            <div class="flex items-center gap-4">
                {{-- Mobile sidebar toggle --}}
                <button onclick="toggleSidebar()"
                        class="text-slate-500 hover:text-slate-700 lg:hidden p-1.5 rounded-lg hover:bg-slate-100 transition-colors duration-150">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                {{-- Breadcrumb / Page Title --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Admin Panel</p>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date --}}
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                    <i class="fas fa-calendar text-primary-600"></i>
                    <span>{{ now()->isoFormat('D MMM YYYY') }}</span>
                </div>
                {{-- Home Link --}}
                <a href="{{ route('pelanggan.home') }}"
                   target="_blank"
                   class="flex items-center gap-2 text-xs text-primary-600 bg-primary-50 hover:bg-primary-100 border border-primary-200 px-3 py-1.5 rounded-xl transition-colors duration-150 font-medium">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="hidden sm:inline">Lihat Toko</span>
                </a>
            </div>
        </header>

        {{-- Toast Container --}}
        <div id="toast-container" class="fixed top-5 right-4 z-[100] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

        {{-- Page Content --}}
        <main id="main-content" class="flex-1 overflow-y-auto p-5 lg:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
// ── Sidebar toggle (mobile) ───────────────────────────────────────────
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const isHidden = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !isHidden);
    overlay.classList.toggle('hidden', !isHidden);
}

// ── Toast System ──────────────────────────────────────────────────────
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
        </button>`;
    container.appendChild(toast);
    setTimeout(() => dismissToast(toast), 4500);
}

function dismissToast(el) {
    if (!el || !el.parentElement) return;
    el.classList.remove('toast-enter');
    el.classList.add('toast-exit');
    setTimeout(() => el.remove(), 350);
}

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
});
</script>

@stack('scripts')
</body>
</html>
