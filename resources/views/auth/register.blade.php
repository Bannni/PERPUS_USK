@extends('layouts.base')

@section('title', 'Registrasi / Masuk - Readowl Bookstore')

@section('content')
<div class="min-h-screen w-full bg-[#F6F4EE] flex flex-col justify-between p-6 sm:p-10 lg:p-12 relative overflow-x-hidden select-none" x-data="{
    mode: 'register'
}">
    <!-- Top Header Full Screen -->
    <header class="max-w-7xl mx-auto w-full flex items-center justify-between z-20">
        <a href="{{ route('home') }}" class="font-bold text-3xl sm:text-4xl tracking-tight text-[#1C1815] flex items-center gap-1 group">
            <span>readowl</span>
            <span class="w-2.5 h-2.5 rounded-full bg-[#7A5034] inline-block group-hover:scale-125 transition"></span>
        </a>

        <div class="flex items-center gap-6">
            <a href="{{ route('catalog') }}" class="text-xs sm:text-sm font-semibold text-[#7A6E63] hover:text-[#1C1815] transition hidden sm:inline">
                Katalog Rak 3D
            </a>
            <a href="{{ route('home') }}" class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-[#1C1815] hover:opacity-75 transition flex items-center gap-1">
                <span>Halaman Utama</span>
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
        </div>
    </header>

    <!-- Main Content Grid Full Screen -->
    <main class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center my-auto py-8 z-10">
        
        <!-- LEFT COLUMN: FLOATING WHITE CARD (Masuk / Registrasi) -->
        <div class="lg:col-span-5 max-w-md w-full mx-auto lg:mx-0">
            <div class="bg-white rounded-[32px] p-7 sm:p-9 shadow-xl shadow-black/5 border border-[#ECE7DE]">
                
                <!-- Card Heading & Switcher Tabs -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-[#1C1815] tracking-tight">
                        Masuk / Registrasi
                    </h2>

                    <!-- Interactive Pills -->
                    <div class="flex items-center bg-[#F4F1EA] p-1 rounded-full text-xs font-semibold">
                        <button type="button" 
                                @click="mode = 'login'" 
                                :class="mode === 'login' ? 'bg-white text-[#1C1815] shadow-xs' : 'text-[#8C8075] hover:text-[#1C1815]'"
                                class="px-4 py-1.5 rounded-full transition duration-150">
                            Masuk
                        </button>
                        <button type="button" 
                                @click="mode = 'register'" 
                                :class="mode === 'register' ? 'bg-white text-[#1C1815] shadow-xs' : 'text-[#8C8075] hover:text-[#1C1815]'"
                                class="px-4 py-1.5 rounded-full transition duration-150">
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

                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-1.5">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 shrink-0 text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
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
                        <button type="submit" class="w-full py-3.5 bg-[#1C1815] hover:bg-black text-white font-semibold text-xs sm:text-sm rounded-full shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
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
                        <button type="submit" class="w-full py-3.5 bg-[#1C1815] hover:bg-black text-white font-semibold text-xs sm:text-sm rounded-full shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
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

        <!-- RIGHT COLUMN: 3D BOOK & SITTING READER -->
        <div class="lg:col-span-7 flex items-center justify-center relative py-8">
            
            <!-- 3D Book Container with Perspective -->
            <div class="relative w-full max-w-lg lg:max-w-xl flex items-center justify-center" style="perspective: 1400px;">
                
                <!-- Floor Soft Shadow under 3D Book -->
                <div class="absolute -bottom-10 left-12 right-12 h-14 bg-black/15 blur-2xl rounded-full transform rotate-[-3deg]"></div>

                <!-- 3D HARDCOVER BOOK WRAPPER -->
                <div class="relative transform rotate-[-12deg] rotate-x-[8deg] rotate-y-[-14deg] hover:rotate-[-6deg] hover:scale-105 transition-all duration-500 cursor-pointer group">
                    
                    <!-- THE BOOK ITSELF (Cover + Spine + Thickness Pages) -->
                    <div class="relative flex">
                        
                        <!-- Left Spine of Book (Thick Bound Edge) -->
                        <div class="w-10 sm:w-12 h-[340px] sm:h-[400px] bg-gradient-to-r from-[#111A24] via-[#1A2836] to-[#0E1720] rounded-l-md border-y border-l border-white/20 shadow-2xl flex flex-col justify-between py-6 items-center relative overflow-hidden">
                            <!-- Spine decorative gold ridges -->
                            <div class="w-full h-1 bg-gradient-to-r from-amber-400/80 to-amber-200/40"></div>
                            <span class="font-serif text-[10px] sm:text-xs tracking-[0.25em] text-amber-200 [writing-mode:vertical-rl] rotate-180 uppercase font-semibold">
                                READOWL • VOL. 01
                            </span>
                            <div class="w-full h-1 bg-gradient-to-r from-amber-400/80 to-amber-200/40"></div>
                        </div>

                        <!-- Front Book Cover -->
                        <div class="w-[240px] sm:w-[290px] md:w-[320px] h-[340px] sm:h-[400px] bg-gradient-to-br from-[#1E2D3D] via-[#182431] to-[#0F1720] border-y border-r border-white/20 p-6 sm:p-8 flex flex-col justify-between shadow-2xl relative overflow-hidden">
                            
                            <!-- Gold Foil Ornate Border -->
                            <div class="absolute inset-3 sm:inset-4 border border-amber-300/40 rounded-sm pointer-events-none flex flex-col justify-between p-3">
                                <div class="flex justify-between text-amber-300/60 text-[10px]">
                                    <span>✦</span><span>✦</span>
                                </div>
                                <div class="flex justify-between text-amber-300/60 text-[10px]">
                                    <span>✦</span><span>✦</span>
                                </div>
                            </div>

                            <!-- Subtle Cover Texture & Light Sheen -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/10 pointer-events-none"></div>

                            <!-- Cover Header / Emblem -->
                            <div class="relative text-center pt-2">
                                <div class="w-12 h-12 rounded-full bg-amber-400/10 border border-amber-300/30 mx-auto flex items-center justify-center text-amber-300 shadow-inner mb-2">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 2C8 2 4 5 4 10c0 4 2 8 8 12 6-4 8-8 8-12 0-5-4-8-8-8z"/>
                                        <circle cx="9" cy="9" r="2.5" fill="#FCD34D"/>
                                        <circle cx="15" cy="9" r="2.5" fill="#FCD34D"/>
                                        <path d="m12 12 1 2-2 0 1-2"/>
                                    </svg>
                                </div>
                                <span class="text-[9px] tracking-[0.3em] uppercase text-amber-300/80 font-sans font-semibold block">
                                    COLLECTION ARCHIVE
                                </span>
                            </div>

                            <!-- Cover Title (Serif Gold Lettering) -->
                            <div class="relative text-center my-auto py-2">
                                <h3 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-white leading-tight">
                                    FIND YOUR<br>BOOK
                                </h3>
                                <p class="text-[10px] sm:text-[11px] text-amber-200/80 font-serif italic mt-2">
                                    "A reader lives a thousand lives before he dies."
                                </p>
                            </div>

                            <!-- Cover Footer -->
                            <div class="relative text-center border-t border-amber-300/20 pt-2 text-[9px] tracking-widest uppercase text-white/60">
                                <span>READOWL DIGITAL LIBRARY</span>
                            </div>

                            <!-- Red Satin Bookmark Ribbon Dangling Down -->
                            <div class="absolute -bottom-6 right-10 w-5 h-20 bg-gradient-to-b from-[#C92A2A] to-[#861212] shadow-lg rounded-b-xs transform rotate-3 flex items-end justify-center pb-1">
                                <div class="w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-b-[8px] border-b-[#F6F4EE] absolute -bottom-px"></div>
                            </div>

                        </div>

                        <!-- Right Edge: Realistic Thick Paper Pages (Page Block) -->
                        <div class="w-8 sm:w-10 h-[330px] sm:h-[390px] my-auto bg-gradient-to-r from-[#EDE4D5] via-[#FAF6ED] to-[#E2D6C3] border-y border-r border-[#D0C2AE] shadow-xl flex flex-col justify-around py-3 px-1 rounded-r-sm">
                            <!-- Page Lines Texture -->
                            <div class="space-y-1 opacity-60">
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                                <div class="h-px bg-[#BDB09E]"></div>
                            </div>
                        </div>

                    </div>

                    <!-- 3D Sitting Reader Character on the Book Edge -->
                    <div class="absolute -right-6 sm:-right-10 -top-10 sm:-top-14 pointer-events-none drop-shadow-2xl z-30">
                        <svg class="w-28 sm:w-36 h-36 sm:h-44" viewBox="0 0 160 200" fill="none">
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
                            <!-- Legs sitting on book -->
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

    </main>

    <!-- Footer Notice Full Screen -->
    <footer class="max-w-7xl mx-auto w-full flex flex-col sm:flex-row items-center justify-between text-xs text-[#8C8075] pt-6 border-t border-[#E8E3D8] gap-3 z-10">
        <span>© 2026 Readowl Bookstore • Sistem Keanggotaan Terintegrasi</span>
        <div class="flex items-center gap-4">
            <span class="font-mono text-[#5C534B]">MySQL perpus_db</span>
            <span>•</span>
            <a href="{{ route('home') }}" class="hover:text-[#1C1815] transition">Beranda</a>
            <span>•</span>
            <a href="{{ route('catalog') }}" class="hover:text-[#1C1815] transition">Katalog Rak 3D</a>
        </div>
    </footer>

</div>
@endsection
