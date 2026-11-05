@extends('layouts.base')

@section('title', 'Readowl Bookstore - Find Your Book')

@section('content')
<div class="min-h-screen bg-[#181614] text-[#2D231E]">

    <!-- HERO SECTION (EDITORIAL CURATION / FIND YOUR BOOK - MATCHING REFERENCE IMAGE) -->
    <section class="p-3 sm:p-6 lg:p-8">
        <div class="bg-[#F0EDE6] rounded-[24px] sm:rounded-[36px] overflow-hidden border border-[#DDD7CD] shadow-2xl min-h-[92vh] flex flex-col justify-between p-6 sm:p-10 lg:p-14 relative">
            
            <!-- Top Header Inside Editorial Frame -->
            <header class="flex items-center justify-between text-xs tracking-widest uppercase font-semibold text-[#5C534B]">
                <!-- Brand Left -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="font-serif text-xl sm:text-2xl font-bold tracking-tighter text-[#1C1815] hover:opacity-80 transition">
                        READOWL
                    </a>
                </div>

                <!-- Center Subtitle / Tag -->
                <div class="hidden sm:block text-[11px] text-[#8C8277] tracking-[0.25em]">
                    THE EDITORIAL ARCHIVE • VOL. 01
                </div>

                <!-- Right Nav Links -->
                <div class="flex items-center gap-5 sm:gap-7">
                    <a href="{{ route('shop') }}" class="hover:text-[#1C1815] transition font-semibold text-[#7A5034]">Shop (Toko Buku)</a>
                    <a href="{{ route('catalog') }}" class="hover:text-[#1C1815] transition hidden md:inline">My Library (Rak 3D)</a>
                    <a href="#featured" class="hover:text-[#1C1815] transition hidden md:inline">Featured</a>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 bg-amber-600 text-white rounded-full text-xs font-semibold hover:bg-amber-700 transition flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                <span>Studio Admin</span>
                            </a>
                        @endif
                        <a href="{{ route('catalog') }}" class="px-4 py-1.5 bg-[#1C1815] text-[#F0EDE6] rounded-full text-xs font-semibold hover:bg-[#3D332B] transition">
                            My Library
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-[#1C1815] transition">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-1.5 bg-[#1C1815] text-[#F0EDE6] rounded-full text-xs font-semibold hover:bg-[#3D332B] transition">
                            Register
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Main Big Editorial Headline (FIND YOUR BOOK) -->
            <div class="text-center my-8 sm:my-12">
                <span class="block text-[11px] sm:text-xs tracking-[0.3em] uppercase text-[#8C8277] font-medium mb-3">
                    PART 1
                </span>
                <h1 class="font-serif text-4xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight text-[#1C1815] uppercase font-light leading-[1.05]">
                    FIND YOUR BOOK
                </h1>
                <p class="text-xs sm:text-sm text-[#73685E] max-w-lg mx-auto mt-4 font-normal tracking-wide">
                    Temukan inspirasi melalui kurasi buku pilihan, tata rak bacaan impianmu, dan nikmati setiap halaman cerita.
                </p>
            </div>

            <!-- THE BOOK SPINES & ARTWORK ROW (Direct Match to Reference) -->
            <div class="w-full overflow-x-auto pb-4 pt-6">
                <div class="min-w-[860px] lg:min-w-0 flex items-end justify-center gap-1.5 sm:gap-2.5 px-4 h-[330px] sm:h-[380px] border-b-2 border-[#1C1815]">
                    
                    <!-- Book 1: Pink Spine -->
                    <a href="{{ route('catalog') }}" class="group w-9 sm:w-11 h-[270px] sm:h-[310px] bg-[#EFA4B8] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1.5 text-center cursor-pointer relative" title="The Year of Less">
                        <span class="text-[9px] [writing-mode:vertical-rl] rotate-180 tracking-widest text-[#6E2E40] uppercase truncate font-medium">ESSAYS & LIFE</span>
                        <span class="w-1.5 h-1.5 bg-[#6E2E40] rounded-full mx-auto mb-1"></span>
                    </a>

                    <!-- Book 2: Green Spine -->
                    <a href="{{ route('catalog') }}" class="group w-8 sm:w-10 h-[210px] sm:h-[240px] bg-[#2E5E43] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1.5 text-center cursor-pointer relative" title="Nature Chronicles">
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-[#A2CDB5] uppercase truncate">BOTANICA</span>
                        <span class="text-[8px] text-[#A2CDB5] font-mono">02</span>
                    </a>

                    <!-- Book 3: Bold Orange "PRINTABLE" -->
                    <a href="{{ route('catalog') }}" class="group w-10 sm:w-13 h-[280px] sm:h-[330px] bg-[#FF4F1A] rounded-t-sm shadow-lg hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between items-center py-3 text-center cursor-pointer relative" title="Printable Design">
                        <span class="font-bold text-white text-xs sm:text-sm tracking-[0.25em] [writing-mode:vertical-rl] rotate-180 uppercase font-sans">
                            PRINTABLE
                        </span>
                        <span class="w-2 h-0.5 bg-white opacity-80"></span>
                    </a>

                    <!-- Book 4: Charcoal Slim Spine -->
                    <a href="{{ route('catalog') }}" class="group w-6 sm:w-7 h-[270px] sm:h-[310px] bg-[#252525] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1 text-center cursor-pointer relative" title="Minimal Architecture">
                        <span class="text-[7px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-[#CCCCCC] uppercase truncate">MONOLITH</span>
                        <span class="text-[7px] text-[#CCCCCC] font-mono">IV</span>
                    </a>

                    <!-- Book 5: Soft Pink "BOOKSHA" -->
                    <a href="{{ route('catalog') }}" class="group w-9 sm:w-11 h-[290px] sm:h-[340px] bg-[#F4A7C1] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-2 text-center cursor-pointer relative" title="Booksha Collection">
                        <span class="text-[9px] font-bold tracking-widest [writing-mode:vertical-rl] rotate-180 text-[#541E30] uppercase">BOOKSHA</span>
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 text-[#541E30]/70 truncate">PHOTOGRAPHIC</span>
                    </a>

                    <!-- Book 6: Leaning Mauve Spine -->
                    <a href="{{ route('catalog') }}" class="group w-6 sm:w-7 h-[280px] sm:h-[320px] bg-[#C1879B] rounded-t-sm shadow-md hover:rotate-3 transition-transform duration-300 flex flex-col justify-between p-1 text-center cursor-pointer origin-bottom-left rotate-[8deg] -mr-1 z-10" title="The Geometry of Holding Hands">
                        <span class="text-[7px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-white uppercase truncate">POETRY</span>
                        <span class="w-1 h-1 bg-white rounded-full mx-auto"></span>
                    </a>

                    <!-- Book 7: CENTERPIECE (Forward-Facing Front Cover Art Book) -->
                    <a href="{{ route('catalog') }}" class="group w-36 sm:w-44 md:w-48 h-[250px] sm:h-[290px] bg-[#1E2C3A] rounded-t-md shadow-2xl hover:-translate-y-2 transition-transform duration-300 flex flex-col justify-between p-3.5 sm:p-4 text-center cursor-pointer relative border-t-2 border-x-2 border-[#15202B] z-20 mx-1" title="The Art of Curation">
                        <!-- White Inner Frame with Classical Statue Illustration -->
                        <div class="w-full h-full border border-white/70 p-2 sm:p-2.5 flex flex-col justify-between items-center bg-[#18232F]">
                            <div class="w-full flex-1 flex items-center justify-center overflow-hidden py-1">
                                <!-- Classical Sculpture SVG / Graphic -->
                                <svg class="w-16 sm:w-20 h-28 sm:h-36 text-white/90 drop-shadow" viewBox="0 0 100 140" fill="none" stroke="currentColor">
                                    <!-- Stylized classical statue contour -->
                                    <path d="M50 15 C45 15, 38 22, 38 32 C38 42, 44 48, 48 52 C42 55, 35 62, 35 75 C35 90, 42 110, 42 125 L58 125 C58 110, 65 90, 65 75 C65 62, 58 55, 52 52 C56 48, 62 42, 62 32 C62 22, 55 15, 50 15 Z" fill="#E8EEF5" stroke="#0F1822" stroke-width="2"/>
                                    <ellipse cx="50" cy="30" rx="8" ry="11" fill="#C9D6E4"/>
                                    <path d="M44 58 C44 70, 40 85, 40 100" stroke="#0F1822" stroke-width="2"/>
                                    <path d="M56 58 C56 70, 60 85, 60 100" stroke="#0F1822" stroke-width="2"/>
                                </svg>
                            </div>
                            <span class="text-[9px] sm:text-[10px] tracking-[0.2em] uppercase font-serif text-white/90">
                                MONOGRAPH
                            </span>
                        </div>
                    </a>

                    <!-- Book 8: Slim Coral Red Spine -->
                    <a href="{{ route('catalog') }}" class="group w-5 sm:w-6 h-[290px] sm:h-[340px] bg-[#E84338] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1 text-center cursor-pointer relative" title="1984 - George Orwell">
                        <span class="text-[7px] [writing-mode:vertical-rl] rotate-180 tracking-widest text-white uppercase font-bold truncate">ORWELL</span>
                        <span class="text-[7px] text-white font-mono">84</span>
                    </a>

                    <!-- Book 9: Cobalt Blue Spine -->
                    <a href="{{ route('catalog') }}" class="group w-8 sm:w-9 h-[270px] sm:h-[315px] bg-[#1E5799] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1.5 text-center cursor-pointer relative" title="Clean Architecture">
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-white uppercase truncate">BLUE ARCHIVE</span>
                        <span class="w-1.5 h-1.5 bg-white/70 rounded-full mx-auto"></span>
                    </a>

                    <!-- Book 10: Wide Grey Spine "S,M,L,XL" -->
                    <a href="{{ route('catalog') }}" class="group w-12 sm:w-15 h-[300px] sm:h-[355px] bg-[#E1E4E8] rounded-t-sm shadow-lg hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between items-center py-3 text-center cursor-pointer relative border-l border-black/10" title="S,M,L,XL Architecture">
                        <div class="text-[7px] text-[#4F5660] font-sans">O.M.A.</div>
                        <span class="font-extrabold text-[#111827] text-xs sm:text-sm tracking-[0.2em] [writing-mode:vertical-rl] rotate-180 uppercase font-sans">
                            S,M,L,XL
                        </span>
                        <span class="w-2 h-0.5 bg-black/40"></span>
                    </a>

                    <!-- Book 11: Leaning Dark Indigo Spine -->
                    <a href="{{ route('catalog') }}" class="group w-6 sm:w-7 h-[250px] sm:h-[285px] bg-[#3B3054] rounded-t-sm shadow-md hover:rotate-0 transition-transform duration-300 flex flex-col justify-between p-1 text-center cursor-pointer origin-bottom-right rotate-[6deg] -ml-1 z-10" title="Visible Signs">
                        <span class="text-[7px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-[#D1C4E9] uppercase truncate font-medium">VISIBLE SIGNS</span>
                        <span class="w-1 h-1 bg-[#D1C4E9] rounded-full mx-auto"></span>
                    </a>

                    <!-- Book 12: Mustard Ochre Spine -->
                    <a href="{{ route('catalog') }}" class="group w-7 sm:w-8 h-[270px] sm:h-[310px] bg-[#EBB036] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1.5 text-center cursor-pointer relative" title="Design of Everyday Things">
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-[#4A3206] uppercase font-bold truncate">DESIGN</span>
                        <span class="text-[7px] text-[#4A3206] font-mono">01</span>
                    </a>

                    <!-- Book 13: Vintage Dark Brown Leather Spine -->
                    <a href="{{ route('catalog') }}" class="group w-11 sm:w-13 h-[250px] sm:h-[295px] bg-[#38261F] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-2 text-center cursor-pointer relative border-x border-[#2A1C16]" title="The Lord of the Rings">
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 tracking-widest text-[#E8D1B5] uppercase font-serif truncate">CLASSIC TALES</span>
                        <span class="text-[8px] text-[#E8D1B5] font-serif">I • II</span>
                    </a>

                    <!-- Book 14: Tall Orange Spine -->
                    <a href="{{ route('catalog') }}" class="group w-7 sm:w-8 h-[290px] sm:h-[340px] bg-[#FF5E1E] rounded-t-sm shadow-md hover:-translate-y-3 transition-transform duration-300 flex flex-col justify-between p-1.5 text-center cursor-pointer relative" title="Logo Design Love">
                        <span class="text-[8px] [writing-mode:vertical-rl] rotate-180 tracking-wider text-white uppercase font-bold truncate">IDENTITY</span>
                        <span class="w-1.5 h-1.5 bg-white rounded-full mx-auto"></span>
                    </a>

                </div>
            </div>

            <!-- Bottom Action & Indicator -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 text-xs text-[#7A6E63] gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#1C1815] animate-pulse"></span>
                    <span>Klik buku pada baris di atas untuk membuka katalog interaktif</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('catalog') }}" class="font-semibold text-[#1C1815] hover:underline flex items-center gap-1 group">
                        <span>Buka Rak Buku 3D Lengkap</span>
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- FEATURED BOOKS LIST -->
    <section id="featured" class="py-20 bg-[#FAF7F2] text-[#2D231E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#8A7463]">Koleksi Terpopuler</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#1C1815] mt-1">Buku Pilihan Pembaca</h2>
                </div>
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#7A5034] hover:text-[#5F3D27]">
                    <span>Lihat Semua di Rak 3D</span>
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

    <!-- FEATURES SECTION -->
    <section id="features" class="py-20 bg-[#F2EFE9] border-t border-[#E2DBD0] text-[#2D231E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-[#8A7463]">Keunggulan Readowl</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#1C1815] mt-2">Dibuat Khusus untuk Pecinta Buku</h2>
                <p class="text-sm sm:text-base text-[#7A6B60] mt-3">Nikmati kemudahan mengatur rak bacaan tanpa kehilangan estetika buku fisik yang menenangkan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#FAF7F2] p-8 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#1C1815] mb-2">Visual Rak 3D Skeuomorfik</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Tampilan rak kayu realistis dengan pencahayaan dan bayangan alami, menjadikan koleksi digital Anda terasa nyata.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#FAF7F2] p-8 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="bookmark" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#1C1815] mb-2">Manajemen Status Membaca</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Kategorikan buku ke dalam <em>Currently reading</em>, <em>Next up</em>, atau <em>Finished</em> lengkap dengan persentase halaman.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#FAF7F2] p-8 rounded-2xl border border-[#E7DCCE] shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-[#EFE4D6] flex items-center justify-center text-[#7A5034] mb-5">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[#1C1815] mb-2">Integrasi Database MySQL</h3>
                    <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                        Tersambung langsung dengan MySQL di XAMPP melalui database <strong>perpus_db</strong> dengan skema tabel terstruktur.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#181614] border-t border-[#2B2724] py-10 text-center text-xs text-[#8C8277]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-serif font-bold text-sm text-[#F0EDE6]">READOWL</span>
                <span>• Digital Bookstore & Library</span>
            </div>
            <p>Database: <span class="font-mono text-[#D7BEA2]">perpus_db</span> (MySQL XAMPP) • Laravel 12</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('catalog') }}" class="hover:text-white transition">Katalog</a>
                <a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a>
            </div>
        </div>
    </footer>

</div>
@endsection
