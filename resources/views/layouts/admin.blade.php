<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Kantin Biru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0 transition-all duration-300">
        {{-- Logo --}}
        <div class="flex items-center gap-3 p-5 border-b border-gray-700">
            <i class="fas fa-store text-blue-400 text-xl"></i>
            <span class="font-bold text-lg">Kantin Biru</span>
            <span class="text-xs bg-blue-700 px-2 py-0.5 rounded ml-auto">Admin</span>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.produk.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.produk.*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-box w-5 text-center"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.kategori.*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.pesanan.*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-shopping-bag w-5 text-center"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.user.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.user.*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Kelola User</span>
            </a>

            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.laporan.*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Laporan</span>
            </a>
        </nav>

        {{-- Footer Sidebar --}}
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->nama }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 text-sm text-red-400 hover:text-red-300 transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Topbar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
@stack('scripts')
</body>
</html>
