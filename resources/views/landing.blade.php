@extends('layouts.base')

@section('title', 'Readowl Bookstore - Jelajahi Ribuan Buku Pilihan')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]">

    <!-- NAVBAR -->
    <header class="border-b border-[#EFE5D7] bg-[#FAF7F2]/90 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#EFE4D6] flex items-center justify-center text-[#7A5034]">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8 2 4 5 4 10c0 4 2 8 8 12 6-4 8-8 8-12 0-5-4-8-8-8z"/>
                        <circle cx="9" cy="9" r="2.5" fill="#7A5034"/>
                        <circle cx="15" cy="9" r="2.5" fill="#7A5034"/>
                        <path d="m12 12 1 2-2 0 1-2"/>
                    </svg>
                </div>
                <span class="font-serif text-2xl font-bold tracking-tight text-[#5F3D27]">Readowl</span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-[#7A6B60]">
                <a href="{{ route('catalog') }}" class="hover:text-[#5F3D27] transition">Katalog Rak Buku</a>
                <a href="#featured" class="hover:text-[#5F3D27] transition">Koleksi Terpopuler</a>
                <a href="#features" class="hover:text-[#5F3D27] transition">Fitur Unggulan</a>
                <a href="#about" class="hover:text-[#5F3D27] transition">Tentang Kami</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('catalog') }}" class="px-4 py-2 text-xs md:text-sm font-semibold text-[#5F3D27] bg-[#F1E8DC] hover:bg-[#E7DBCB] rounded-xl transition flex items-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4 text-[#7A5034]"></i>
                        <span>Perpustakaan Saya</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-[#9C8B7E] hover:text-red-600 rounded-xl transition" title="Keluar">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs md:text-sm font-medium text-[#5F3D27] hover:text-[#3D2719] transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-xs md:text-sm font-semibold text-white bg-[#7A5034] hover:bg-[#5F3D27] rounded-xl shadow-sm transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-xl flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- HERO SECTION -->
    <section class="py-16 md:py-24 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Column: Copywriting -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EFE5D7] text-[#7A5034] text-xs font-semibold tracking-wide uppercase">
                        <span>✨ Toko Buku & Perpustakaan Digital</span>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-[#4A3222] leading-[1.15]">
                        Ruang Nyaman untuk Menjelajah <span class="text-[#8C603E] italic">Ribuan Kisah</span> & Pengetahuan.
                    </h1>

                    <p class="text-base sm:text-lg text-[#7A6B60] max-w-xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        Rasakan kehangatan perpustakaan klasik dalam balutan teknologi modern. Kelola rak buku virtual pribadi, pantau progres membaca, dan dapatkan inspirasi setiap hari.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('catalog') }}" class="w-full sm:w-auto px-7 py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 group">
                            <span>Buka Rak Buku Saya</span>
                            <span class="group-hover:translate-x-1 transition">→</span>
                        </a>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-7 py-3.5 bg-[#EFE4D6] hover:bg-[#E5D7C5] text-[#5F3D27] font-semibold text-sm rounded-xl transition text-center">
                            Gabung Menjadi Pembaca
                        </a>
                    </div>

                    <!-- Micro Stats -->
                    <div class="pt-6 border-t border-[#EFE5D7] grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0 text-center lg:text-left">
                        <div>
                            <span class="block font-serif text-2xl font-bold text-[#5F3D27]">{{ $totalBooks }}+</span>
                            <span class="text-xs text-[#8C7A6D]">Koleksi Terkurasi</span>
                        </div>
                        <div>
                            <span class="block font-serif text-2xl font-bold text-[#5F3D27]">100%</span>
                            <span class="text-xs text-[#8C7A6D]">Visual Rak 3D</span>
                        </div>
                        <div>
                            <span class="block font-serif text-2xl font-bold text-[#5F3D27]">XAMPP</span>
                            <span class="text-xs text-[#8C7A6D]">perpus_db Siap</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive 3D Shelf Preview -->
                <div class="lg:col-span-6">
                    <div class="bg-[#FAF7F2] border border-[#E9DDD0] rounded-3xl p-6 sm:p-8 shadow-xl relative">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#A8988B]">Pratinjau Rak Buku</span>
                                <h3 class="font-serif text-xl font-bold text-[#5F3D27]">Koleksi Pilihan Hari Ini</h3>
                            </div>
                            <a href="{{ route('catalog') }}" class="text-xs font-semibold text-[#7A5034] hover:underline flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <span>→</span>
                            </a>
                        </div>

                        <!-- 3D Shelf Preview Container -->
                        <div class="relative pt-6 pb-0 px-2 sm:px-4 flex items-end justify-around">
                            @foreach($featuredBooks->take(4) as $book)
                                <a href="{{ route('catalog') }}" class="book-wrapper shrink-0 group">
                                    <div class="w-20 sm:w-24 aspect-[2/3] book-cover">
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="mt-2 text-center w-20 sm:w-24">
                                        <p class="text-[10px] font-bold text-[#4A3222] truncate">{{ $book->title }}</p>
                                        <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- 3D Shelf Ledge -->
                        <div class="shelf-surface mt-1"></div>
                        <div class="shelf-face"></div>

                        <div class="mt-6 text-center">
                            <p class="text-xs text-[#8C7A6D]">
                                Klik buku di atas atau <a href="{{ route('catalog') }}" class="font-bold text-[#7A5034] underline">kunjungi halaman katalog</a> untuk melihat rak buku lengkap (Currently reading, Next up, Finished).
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="py-16 bg-[#F5EFEB] border-y border-[#EAE0D3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-[#8A7463]">Keunggulan Readowl</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#4A3222] mt-2">Dibuat Khusus untuk Pecinta Buku</h2>
                <p class="text-sm sm:text-base text-[#7A6B60] mt-3">Nikmati kemudahan mengatur rak bacaan tanpa kehilangan estetika buku fisik yang menenangkan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#FAF7F2] p-7 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#4A3222] mb-2">Visual Rak 3D Skeuomorfik</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Tampilan rak kayu realistis dengan pencahayaan dan bayangan alami, menjadikan koleksi digital Anda terasa nyata.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#FAF7F2] p-7 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="bookmark" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#4A3222] mb-2">Manajemen Status Membaca</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Kategorikan buku ke dalam <em>Currently reading</em>, <em>Next up</em>, atau <em>Finished</em> lengkap dengan persentase halaman.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#FAF7F2] p-7 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#4A3222] mb-2">Integrasi Database MySQL</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Tersambung langsung dengan MySQL di XAMPP melalui database <strong>perpus_db</strong> dengan skema tabel terstruktur.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED BOOKS LIST -->
    <section id="featured" class="py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#8A7463]">Koleksi Unggulan</span>
                    <h2 class="font-serif text-3xl font-bold text-[#4A3222] mt-1">Buku Terpopuler di Toko</h2>
                </div>
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#7A5034] hover:text-[#5F3D27]">
                    <span>Buka Katalog Lengkap</span>
                    <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6">
                @foreach($featuredBooks as $book)
                    <a href="{{ route('catalog') }}" class="group block">
                        <div class="aspect-[2/3] w-full rounded-xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300 border border-[#E5D7C5]">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="mt-3">
                            <span class="text-[10px] uppercase font-bold text-[#8C7A6D] block">{{ $book->category }}</span>
                            <h4 class="font-bold text-xs text-[#4A3222] group-hover:text-[#7A5034] transition truncate mt-0.5">{{ $book->title }}</h4>
                            <p class="text-[11px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-xs font-bold text-[#7A5034]">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-amber-700 font-bold">★ {{ $book->rating }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION (CTA) -->
    <section class="py-16 bg-[#EFE4D6] border-t border-[#DFD1BF]">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#4A3222]">Mulai Buat Rak Buku Impianmu Hari Ini</h2>
            <p class="text-sm sm:text-base text-[#7A6B60] max-w-xl mx-auto mt-3">
                Daftar sekarang untuk menyimpan daftar bacaan, memberi rating pada buku favorit, dan menjelajahi koleksi terlengkap.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-semibold text-sm rounded-xl shadow-md transition">
                    Daftar Akun Baru
                </a>
                <a href="{{ route('catalog') }}" class="px-8 py-3.5 bg-white hover:bg-[#FAF7F2] text-[#5F3D27] border border-[#D8C7B4] font-semibold text-sm rounded-xl transition">
                    Lihat Rak Buku Langsung
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#FAF7F2] border-t border-[#EAE0D3] py-8 text-center text-xs text-[#8C7A6D]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-serif font-bold text-sm text-[#5F3D27]">Readowl</span>
                <span>• Toko Buku & Perpustakaan Digital</span>
            </div>
            <p>Database: <span class="font-mono text-[#7A5034]">perpus_db</span> (MySQL XAMPP) • Laravel 12</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('catalog') }}" class="hover:underline">Katalog</a>
                <a href="{{ route('login') }}" class="hover:underline">Masuk</a>
                <a href="{{ route('register') }}" class="hover:underline">Daftar</a>
            </div>
        </div>
    </footer>

</div>
@endsection
