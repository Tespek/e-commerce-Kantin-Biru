<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Kantin Biru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        primary: { 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        accent:  { 400: '#facc15', 500: '#eab308' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.45s ease forwards; }
        .input-field {
            width: 100%; padding: 0.625rem 0.75rem 0.625rem 2.5rem;
            border: 1.5px solid #e2e8f0; border-radius: 0.75rem;
            font-size: 0.875rem; outline: none; transition: all .15s;
            background: #f8fafc; color: #0f172a;
        }
        .input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            background: #fff;
        }
        .input-field::placeholder { color: #94a3b8; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex" style="font-family:'Inter',system-ui,sans-serif">

    {{-- Left Panel (brand) — desktop only --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-primary-900 relative overflow-hidden flex-col justify-between p-10">
        {{-- Decorative circles --}}
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary-700/40 rounded-full"></div>
        <div class="absolute top-1/3 -right-16 w-64 h-64 bg-primary-600/30 rounded-full"></div>
        <div class="absolute -bottom-16 left-10 w-56 h-56 bg-primary-800/50 rounded-full"></div>
        <div class="absolute bottom-24 right-0 w-40 h-40 bg-accent-400/10 rounded-full"></div>

        {{-- Brand top --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-white rounded-xl overflow-hidden shadow-lg">
                    <img src="{{ asset('images/logokantinbiru.jpeg') }}" alt="Kantin Biru" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-lg text-white leading-tight">Kantin Biru</p>
                    <p class="text-blue-300 text-xs">UMKM Kuliner Lhokseumawe</p>
                </div>
            </div>
        </div>

        {{-- Center text --}}
        <div class="relative z-10">
            <div class="w-16 h-16 bg-accent-400 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                <i class="fas fa-utensils text-2xl text-slate-900"></i>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold text-white leading-tight mb-4">
                Camilan Lezat,<br>
                <span class="text-accent-400">Harga Terjangkau</span>
            </h2>
            <p class="text-blue-200 text-sm leading-relaxed max-w-sm">
                Temukan berbagai pilihan camilan berkualitas dengan cita rasa manis, asin, dan gurih yang cocok untuk semua kalangan.
            </p>

            {{-- Stats --}}
            <div class="flex gap-6 mt-8">
                <div>
                    <p class="text-2xl font-bold text-white">200+</p>
                    <p class="text-blue-300 text-xs">Pelanggan</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-2xl font-bold text-white">50+</p>
                    <p class="text-blue-300 text-xs">Produk</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-2xl font-bold text-accent-400">2019</p>
                    <p class="text-blue-300 text-xs">Berdiri</p>
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="relative z-10">
            <p class="text-blue-400 text-xs">© {{ date('Y') }} Kantin Biru. All rights reserved.</p>
        </div>
    </div>

    {{-- Right Panel (form) --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-10">
        <div class="w-full max-w-md animate-fade-up">

            {{-- Mobile logo --}}
            <div class="flex justify-center mb-8 lg:hidden">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary-600 rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('images/logokantinbiru.jpeg') }}" alt="Kantin Biru" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-xl text-slate-800">Kantin Biru</p>
                        <p class="text-slate-400 text-xs">UMKM Kuliner</p>
                    </div>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali! 👋</h1>
                <p class="text-slate-500 text-sm mt-1.5">Masuk ke akun Kantin Biru kamu</p>
            </div>

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl mb-6 text-sm">
                    <i class="fas fa-circle-exclamation text-red-500 mt-0.5 shrink-0"></i>
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="input-field" placeholder="email@example.com" autocomplete="email">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="input-field pr-10" placeholder="Masukkan password" autocomplete="current-password">
                        <button type="button" onclick="togglePassword('password', 'eye-password')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-password"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm mt-2">
                    <i class="fas fa-arrow-right-to-bracket mr-2"></i>
                    Masuk ke Akun
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-medium">ATAU</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            {{-- Register link --}}
            <p class="text-center text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-primary-600 font-semibold hover:text-primary-700 hover:underline transition-colors ml-1">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
