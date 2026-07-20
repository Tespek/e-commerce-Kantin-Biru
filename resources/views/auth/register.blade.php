<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Kantin Biru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter','system-ui','sans-serif'] },
                    colors: {
                        primary: { 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' },
                        accent:  { 400:'#facc15', 500:'#eab308' }
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
        .input-no-icon {
            padding-left: 0.875rem;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex" style="font-family:'Inter',system-ui,sans-serif">

    {{-- Left Panel — brand (desktop only) --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-[42%] bg-primary-900 relative overflow-hidden flex-col justify-between p-10">
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary-700/40 rounded-full"></div>
        <div class="absolute top-1/3 -right-16 w-64 h-64 bg-primary-600/30 rounded-full"></div>
        <div class="absolute -bottom-16 left-10 w-56 h-56 bg-primary-800/50 rounded-full"></div>

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

        <div class="relative z-10">
            <div class="w-16 h-16 bg-accent-400 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                <i class="fas fa-user-plus text-2xl text-slate-900"></i>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold text-white leading-tight mb-4">
                Bergabung &<br>
                <span class="text-accent-400">Mulai Belanja</span>
            </h2>
            <p class="text-blue-200 text-sm leading-relaxed max-w-sm">
                Daftar sekarang dan nikmati kemudahan berbelanja camilan lezat langsung dari Kantin Biru.
            </p>

            <div class="mt-8 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent-400/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-accent-400 text-xs"></i>
                    </div>
                    <span class="text-blue-200 text-sm">Gratis daftar, tanpa biaya apapun</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent-400/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-accent-400 text-xs"></i>
                    </div>
                    <span class="text-blue-200 text-sm">Lacak pesanan secara real-time</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent-400/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-accent-400 text-xs"></i>
                    </div>
                    <span class="text-blue-200 text-sm">Harga terjangkau untuk mahasiswa</span>
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <p class="text-blue-400 text-xs">© {{ date('Y') }} Kantin Biru. All rights reserved.</p>
        </div>
    </div>

    {{-- Right Panel (form) --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-10 overflow-y-auto">
        <div class="w-full max-w-md py-4 animate-fade-up">

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

            <div class="mb-7">
                <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
                <p class="text-slate-500 text-sm mt-1.5">Isi data di bawah untuk mendaftar</p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl mb-6 text-sm">
                    <div class="flex items-center gap-2 mb-2 font-semibold">
                        <i class="fas fa-circle-exclamation text-red-500"></i>
                        Terdapat kesalahan:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                               class="input-field" placeholder="Nama lengkap kamu" autocomplete="name">
                    </div>
                </div>

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

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No. HP <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-phone text-sm"></i>
                        </span>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                               class="input-field" placeholder="08xxxxxxxxxx" autocomplete="tel">
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
                               class="input-field pr-10" placeholder="Minimal 8 karakter" autocomplete="new-password">
                        <button type="button" onclick="togglePassword('password','eye-pw')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-pw"></i>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="input-field pr-10" placeholder="Ulangi password" autocomplete="new-password">
                        <button type="button" onclick="togglePassword('password_confirmation','eye-pw2')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-pw2"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-sm mt-2">
                    <i class="fas fa-user-plus mr-2"></i>
                    Buat Akun Sekarang
                </button>
            </form>

            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-medium">ATAU</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <p class="text-center text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                   class="text-primary-600 font-semibold hover:text-primary-700 hover:underline transition-colors ml-1">
                    Masuk di sini
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
