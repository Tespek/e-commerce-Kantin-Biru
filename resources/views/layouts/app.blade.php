<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kantin Biru')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .bg-biru { background-color: #1e40af; }
        .text-biru { color: #1e40af; }
        .border-biru { border-color: #1e40af; }
        .hover\:bg-biru-dark:hover { background-color: #1e3a8a; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-biru text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('pelanggan.home') }}" class="flex items-center gap-2">
                    <div class="bg-white rounded-lg p-1 flex items-center justify-center" style="width:44px;height:44px;">
                        <img src="{{ asset('images/logokantinbiru.jpeg') }}"
                             alt="Kantin Biru"
                             class="w-full h-full object-contain rounded">
                    </div>
                    <span class="text-xl font-bold">Kantin Biru</span>
                </a>

                {{-- Navigasi --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('pelanggan.home') }}" class="hover:text-blue-200 transition">Beranda</a>
                    <a href="{{ route('pelanggan.home') }}?kategori=" class="hover:text-blue-200 transition">Produk</a>

                    @auth
                        @if(auth()->user()->isPelanggan())
                            {{-- Keranjang --}}
                            <a href="{{ route('keranjang.index') }}" class="relative hover:text-blue-200 transition">
                                <i class="fas fa-shopping-cart text-lg"></i>
                                @php
                                    $jumlahKeranjang = \App\Models\Keranjang::where('id_user', auth()->id())->count();
                                @endphp
                                @if($jumlahKeranjang > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $jumlahKeranjang }}
                                    </span>
                                @endif
                            </a>

                            {{-- Dropdown User --}}
                            <div class="relative group">
                                <button class="flex items-center gap-1 hover:text-blue-200 transition">
                                    <i class="fas fa-user-circle text-lg"></i>
                                    <span>{{ auth()->user()->nama }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div class="absolute right-0 mt-1 w-48 bg-white text-gray-800 rounded-lg shadow-lg py-1 hidden group-hover:block">
                                    <a href="{{ route('pesanan.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                        <i class="fas fa-box-open mr-2 text-biru"></i>Pesanan Saya
                                    </a>
                                    <a href="{{ route('profil.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                        <i class="fas fa-user mr-2 text-biru"></i>Profil
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-500">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-200 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-white text-biru px-4 py-1.5 rounded-lg font-semibold hover:bg-blue-50 transition text-sm">Daftar</a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" class="md:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-blue-900 px-4 pb-4">
            <a href="{{ route('pelanggan.home') }}" class="block py-2 hover:text-blue-200">Beranda</a>
            @auth
                @if(auth()->user()->isPelanggan())
                    <a href="{{ route('keranjang.index') }}" class="block py-2 hover:text-blue-200">Keranjang</a>
                    <a href="{{ route('pesanan.index') }}" class="block py-2 hover:text-blue-200">Pesanan Saya</a>
                    <a href="{{ route('profil.index') }}" class="block py-2 hover:text-blue-200">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block py-2 text-red-300 hover:text-red-200 w-full text-left">Logout</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="block py-2 hover:text-blue-200">Masuk</a>
                <a href="{{ route('register') }}" class="block py-2 hover:text-blue-200">Daftar</a>
            @endauth
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded relative" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 mt-4 rounded relative" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 mx-4 mt-4 rounded relative" role="alert">
            <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
        </div>
    @endif

    {{-- Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-biru text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-3 mb-2">
                <div class="bg-white rounded-lg p-1 flex items-center justify-center" style="width:40px;height:40px;">
                    <img src="{{ asset('images/logokantinbiru.jpeg') }}"
                         alt="Kantin Biru"
                         class="w-full h-full object-contain rounded">
                </div>
                <p class="font-bold text-lg">Kantin Biru</p>
            </div>
            <p class="text-blue-200 text-sm">Temukan makanan & minuman terbaik di kantin kami</p>
            <p class="text-blue-300 text-xs mt-4">&copy; {{ date('Y') }} Kantin Biru. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
