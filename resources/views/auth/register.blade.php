@extends('layouts.base')

@section('title', 'Registrasi / Masuk - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#181614] flex items-center justify-center p-3 sm:p-6 lg:p-8 select-none" x-data="{
    mode: 'register'
}">
    <!-- TABLET / SLEEK SCREEN MOCKUP (Matching Reference Image) -->
    <div class="w-full max-w-5xl bg-[#F6F4EE] rounded-[30px] sm:rounded-[42px] border-[8px] sm:border-[12px] border-[#221F1C] shadow-2xl relative overflow-hidden p-6 sm:p-10 lg:p-12 min-h-[620px] flex flex-col justify-between">
        
        <!-- Tablet Camera Notch -->
        <div class="w-24 sm:w-32 h-3.5 bg-[#221F1C] mx-auto rounded-b-xl absolute top-0 left-1/2 -translate-y-px -translate-x-1/2 flex items-center justify-center z-30">
            <div class="w-2.5 h-2.5 rounded-full bg-[#11100E] border border-[#2D2A26]"></div>
        </div>

        <!-- Top Header with Minimalist Logo -->
        <div class="flex items-center justify-between mb-6 z-20">
            <a href="{{ route('home') }}" class="font-bold text-2xl sm:text-3xl tracking-tight text-[#1C1815] flex items-center gap-1 group">
                <span>readowl</span>
                <span class="w-2 h-2 rounded-full bg-[#7A5034] inline-block group-hover:scale-125 transition"></span>
            </a>

            <a href="{{ route('home') }}" class="text-xs font-semibold uppercase tracking-wider text-[#7A6E63] hover:text-[#1C1815] transition flex items-center gap-1">
                <span>Halaman Utama</span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center my-auto z-10">
            
            <!-- LEFT COLUMN: FLOATING WHITE CARD (Masuk / Registrasi) -->
            <div class="lg:col-span-6 max-w-md w-full mx-auto lg:mx-0">
                <div class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xl shadow-black/5 border border-[#ECE7DE]">
                    
                    <!-- Card Heading & Switcher Tabs -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#1C1815] tracking-tight">
                            Masuk / Registrasi
                        </h2>

                        <!-- Interactive Pills -->
                        <div class="flex items-center bg-[#F4F1EA] p-1 rounded-full text-xs font-semibold">
                            <button type="button" 
                                    @click="mode = 'login'" 
                                    :class="mode === 'login' ? 'bg-white text-[#1C1815] shadow-xs' : 'text-[#8C8075] hover:text-[#1C1815]'"
                                    class="px-3.5 py-1 rounded-full transition duration-150">
                                Masuk
                            </button>
                            <button type="button" 
                                    @click="mode = 'register'" 
                                    :class="mode === 'register' ? 'bg-white text-[#1C1815] shadow-xs' : 'text-[#8C8075] hover:text-[#1C1815]'"
                                    class="px-3.5 py-1 rounded-full transition duration-150">
                                Daftar
                            </button>
                        </div>
                    </div>

                    <!-- Validation Alerts -->
                    @if (isset($errors) && $errors->any())
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0 text-red-500"></i>
                                    <span>{{ $error }}</span>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    <!-- FORM 2: REGISTER (Default on /register) -->
                    <form x-show="mode === 'register'" action="{{ route('register') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <div>
                            <label for="reg-name" class="block text-xs font-semibold text-[#5C534B] mb-1">Nama Lengkap</label>
                            <input id="reg-name" 
                                   name="name" 
                                   type="text" 
                                   required 
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Assya Pratama" 
                                   class="w-full px-4 py-2.5 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                        </div>

                        <div>
                            <label for="reg-email" class="block text-xs font-semibold text-[#5C534B] mb-1">Alamat Email</label>
                            <input id="reg-email" 
                                   name="email" 
                                   type="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com" 
                                   class="w-full px-4 py-2.5 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label for="reg-password" class="block text-xs font-semibold text-[#5C534B] mb-1">Kata Sandi</label>
                                <input id="reg-password" 
                                       name="password" 
                                       type="password" 
                                       required 
                                       placeholder="Min 6 char" 
                                       class="w-full px-3 py-2.5 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                            </div>

                            <div>
                                <label for="reg-password-confirm" class="block text-xs font-semibold text-[#5C534B] mb-1">Konfirmasi</label>
                                <input id="reg-password-confirm" 
                                       name="password_confirmation" 
                                       type="password" 
                                       required 
                                       placeholder="Ulangi" 
                                       class="w-full px-3 py-2.5 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-[#1C1815] hover:bg-black text-white font-semibold text-xs sm:text-sm rounded-full shadow-md transition duration-200 flex items-center justify-center gap-2">
                                <span>Buat Akun</span>
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <button type="button" @click="mode = 'login'" class="text-xs text-[#8C8075] hover:text-[#1C1815] transition">
                                Sudah punya akun? <span class="font-bold underline">Masuk di sini</span>
                            </button>
                        </div>
                    </form>

                    <!-- FORM 1: LOGIN -->
                    <form x-show="mode === 'login'" x-cloak action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="login-email" class="block text-xs font-semibold text-[#5C534B] mb-1.5">Masuk (Email)</label>
                            <input id="login-email" 
                                   name="email" 
                                   type="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com" 
                                   class="w-full px-4 py-3 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="login-password" class="block text-xs font-semibold text-[#5C534B]">Kata Sandi</label>
                            </div>
                            <input id="login-password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   placeholder="••••••••" 
                                   class="w-full px-4 py-3 bg-[#FAF9F5] border border-[#E5E0D6] rounded-2xl text-xs sm:text-sm text-[#1C1815] placeholder-[#B5ABA0] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1C1815] focus:border-transparent transition">
                        </div>

                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-[#7A6E63]">
                                <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-[#1C1815] focus:ring-0">
                                <span>Ingat saya</span>
                            </label>
                            <span class="text-[11px] text-[#A39688]">Demo: pembaca@readowl.com</span>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-[#1C1815] hover:bg-black text-white font-semibold text-xs sm:text-sm rounded-full shadow-md transition duration-200 flex items-center justify-center gap-2">
                                <span>Masuk</span>
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <button type="button" @click="mode = 'register'" class="text-xs text-[#8C8075] hover:text-[#1C1815] transition">
                                Belum punya akun? <span class="font-bold underline">Registrasi di sini</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- RIGHT COLUMN: 3D FLOATING DIGITAL LIBRARY PASS & 3D READER (BOOK THEME INSTEAD OF KJP) -->
            <div class="lg:col-span-6 flex items-center justify-center relative py-6">
                
                <!-- 3D Card Container with Perspective -->
                <div class="relative w-full max-w-sm sm:max-w-md" style="perspective: 1200px;">
                    
                    <!-- Under Card Drop Shadow -->
                    <div class="absolute -bottom-8 left-10 right-10 h-10 bg-black/15 blur-xl rounded-full transform rotate-[-4deg]"></div>

                    <!-- THE 3D LIBRARY MEMBER CARD (BOOK THEME) -->
                    <div class="relative bg-gradient-to-br from-[#2C485C] via-[#1E3342] to-[#12202A] text-white rounded-3xl p-6 sm:p-7 shadow-2xl border border-white/20 transform rotate-[-8deg] hover:rotate-[-4deg] hover:scale-105 transition-all duration-500 overflow-hidden group">
                        
                        <!-- Holographic / Subtle Pattern Glow -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/15 pointer-events-none"></div>
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-sky-400/10 rounded-full blur-2xl"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-white/15 pb-4 mb-5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-amber-300">
                                    <i data-lucide="book-open" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-serif font-bold text-sm tracking-wide text-white flex items-center gap-1.5">
                                        <span>READOWL PASS</span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-400/20 text-amber-300 border border-amber-400/30 uppercase font-sans font-semibold">VIP</span>
                                    </h3>
                                    <p class="text-[10px] text-white/70">Kartu Akses Perpustakaan & Toko Buku</p>
                                </div>
                            </div>

                            <!-- Chip Icon -->
                            <div class="w-8 h-6 bg-gradient-to-r from-amber-200 to-amber-400 rounded-md border border-amber-500/40 shadow-xs flex items-center justify-center">
                                <div class="w-5 h-3 border border-amber-700/30 rounded-xs"></div>
                            </div>
                        </div>

                        <!-- Card Body (Member Photo & Details) -->
                        <div class="flex items-center gap-4">
                            <!-- Member Avatar Portrait -->
                            <div class="w-16 h-20 sm:w-20 sm:h-24 rounded-2xl bg-gradient-to-t from-[#0D161D] to-[#203646] border-2 border-white/30 overflow-hidden shrink-0 shadow-inner flex items-center justify-center relative">
                                <!-- Reader Silhouette Illustration -->
                                <svg class="w-12 h-16 text-white/80" viewBox="0 0 100 120" fill="none" stroke="currentColor">
                                    <circle cx="50" cy="35" r="20" fill="#E2E8F0"/>
                                    <path d="M20 100 C20 70, 35 60, 50 60 C65 60, 80 70, 80 100 Z" fill="#94A3B8"/>
                                    <!-- Book in hands -->
                                    <path d="M35 85 L50 80 L65 85 L65 105 L50 100 L35 105 Z" fill="#F8FAFC" stroke="#0F172A" stroke-width="2"/>
                                </svg>
                                <span class="absolute bottom-1 bg-black/60 text-[8px] px-1 rounded text-white/90">READER</span>
                            </div>

                            <!-- Details Column -->
                            <div class="flex-1 space-y-1 text-xs">
                                <div>
                                    <span class="text-[9px] text-white/60 block uppercase tracking-wider">Identitas Anggota</span>
                                    <span class="font-bold text-white text-xs sm:text-sm">Assya Pembaca</span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-white/60 block uppercase tracking-wider">Nomor Anggota</span>
                                    <span class="font-mono text-[11px] text-amber-200">#RW-2026-9842</span>
                                </div>
                                <div class="flex items-center gap-3 pt-0.5">
                                    <div>
                                        <span class="text-[8px] text-white/50 block">Status</span>
                                        <span class="text-[10px] text-emerald-300 font-bold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-[8px] text-white/50 block">Koleksi</span>
                                        <span class="text-[10px] text-white/90 font-medium">Rak 3D Unlocked</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer: Barcode & Database Status -->
                        <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[9px] text-white/60">
                            <span class="font-mono tracking-widest text-white/70">||| |||| || ||||| |||</span>
                            <span>DB: perpus_db</span>
                        </div>

                    </div>

                    <!-- 3D Sitting Reader Figure (Sitting on the right edge of the card) -->
                    <div class="absolute -right-4 sm:-right-8 top-16 sm:top-14 pointer-events-none drop-shadow-2xl z-20">
                        <!-- Stylized 3D Character Reading a Book -->
                        <svg class="w-24 sm:w-32 h-32 sm:h-40" viewBox="0 0 160 200" fill="none">
                            <!-- Character Head -->
                            <circle cx="85" cy="50" r="18" fill="#F8C8A0"/>
                            <!-- Hair -->
                            <path d="M68 45 C68 30, 80 25, 95 30 C102 33, 105 45, 100 52 C95 48, 85 45, 75 48 Z" fill="#2D231E"/>
                            <!-- Torso / Shirt -->
                            <path d="M70 70 C70 65, 100 65, 100 70 L108 120 L62 120 Z" fill="#FFFFFF"/>
                            <!-- Arms holding open book -->
                            <path d="M68 78 L52 105 L62 115" stroke="#F8C8A0" stroke-width="10" stroke-linecap="round"/>
                            <path d="M98 78 L116 105 L106 115" stroke="#F8C8A0" stroke-width="10" stroke-linecap="round"/>
                            <!-- Open Book held by character -->
                            <path d="M40 105 L58 100 L76 105 L76 128 L58 123 L40 128 Z" fill="#FFEAA7" stroke="#2D231E" stroke-width="2"/>
                            <!-- Legs sitting -->
                            <path d="M66 120 L62 155 L90 155" stroke="#2B3A4A" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M85 120 L80 155 L108 155" stroke="#1E2A36" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Shoes -->
                            <ellipse cx="95" cy="158" rx="8" ry="4" fill="#1C1815"/>
                            <ellipse cx="113" cy="158" rx="8" ry="4" fill="#1C1815"/>
                        </svg>
                    </div>

                </div>

            </div>

        </div>

        <!-- Footer Notice Inside Frame -->
        <div class="flex items-center justify-between text-[11px] text-[#8C8075] pt-4 border-t border-[#E8E3D8] z-10">
            <span>© 2026 Readowl Bookstore • Sistem Keanggotaan Terintegrasi</span>
            <span class="font-mono text-[#5C534B]">MySQL perpus_db</span>
        </div>

    </div>
</div>
@endsection
