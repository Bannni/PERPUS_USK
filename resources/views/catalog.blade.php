@extends('layouts.base')

@section('title', 'My Library - Rak Buku Koleksi Saya')

@section('content')
<div class="min-h-screen md:h-screen md:overflow-hidden bg-[#F4ECE1]/60 flex flex-col md:flex-row p-3 md:p-6 lg:p-8 gap-5" x-data="{
    activeTab: '{{ $activeTab ?? 'shelves' }}',
    selectedBook: null,
    modalOpen: false,
    readerOpen: false,
    readerTheme: 'sepia',
    currentPage: 1,
    fontSize: 16,
    openBook(book) {
        this.selectedBook = book;
        this.modalOpen = true;
    },
    startReading() {
        this.modalOpen = false;
        this.currentPage = 1;
        this.readerOpen = true;
    }
}">
    <!-- SIDEBAR (FIXED ON DESKTOP) -->
    <aside class="w-full md:w-64 lg:w-72 flex flex-col justify-between shrink-0 md:h-full md:overflow-y-auto bg-[#FAF7F2] md:bg-transparent rounded-2xl p-4 md:p-0 select-none">
        <div>
            <!-- Readowl Logo -->
            <div class="flex items-center gap-3 px-2 py-4 mb-4">
                <div class="w-10 h-10 rounded-full bg-[#E8DCB9]/60 flex items-center justify-center text-[#7A5034]">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8 2 4 5 4 10c0 4 2 8 8 12 6-4 8-8 8-12 0-5-4-8-8-8z"/>
                        <circle cx="9" cy="9" r="2.5" fill="#7A5034"/>
                        <circle cx="15" cy="9" r="2.5" fill="#7A5034"/>
                        <path d="m12 12 1 2-2 0 1-2"/>
                    </svg>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight text-[#5F3D27] hover:text-[#7A5034] transition">
                        Readowl
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#EFE5D7]/50 transition group">
                    <i data-lucide="home" class="w-5 h-5 text-[#9C8B7E] group-hover:text-[#7A5034] transition"></i>
                    <span class="font-medium text-sm">Home</span>
                </a>

                <a href="{{ route('catalog') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-[#F0E6D8] border border-[#E4D5C2] text-[#4F3320] font-semibold text-sm shadow-sm transition">
                    <i data-lucide="book-open" class="w-5 h-5 text-[#7A5034]"></i>
                    <span>My library</span>
                </a>

                <!-- Shop (Where all books are purchased!) -->
                <a href="{{ route('shop') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#EFE5D7]/50 transition group">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#9C8B7E] group-hover:text-[#7A5034] transition"></i>
                    <span class="font-medium text-sm">Shop (Toko Buku)</span>
                </a>

                @auth
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#EFE5D7]/50 transition group">
                        <i data-lucide="receipt" class="w-5 h-5 text-[#9C8B7E] group-hover:text-[#7A5034] transition"></i>
                        <span class="font-medium text-sm">Pesanan Saya</span>
                    </a>

                    @if(Auth::user()->isAdmin())
                        <div class="pt-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-[#1C1815] text-amber-300 font-semibold text-xs shadow-md hover:bg-black transition border border-amber-400/20">
                                <i data-lucide="shield-check" class="w-4 h-4 text-amber-400"></i>
                                <span>Panel Admin Kurator</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>
        </div>

        <!-- Bottom Widgets -->
        <div class="mt-8 space-y-4">
            <!-- Continue Reading Card (Only if user has bought books) -->
            @if($continueReadingBook)
            <div class="bg-[#F2E8DC] border border-[#E3D6C5] rounded-2xl p-3.5 shadow-sm">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-[#8A7463] mb-2.5 flex items-center justify-between">
                    <span>Terakhir Dibeli</span>
                    <span class="text-[#7A5034] font-bold">Koleksi Anda</span>
                </div>
                <div class="flex items-center gap-3 cursor-pointer group" @click="openBook({{ json_encode($continueReadingBook) }})">
                    <div class="w-12 h-16 shrink-0 rounded overflow-hidden shadow-sm border border-[#DACBB8]">
                        <img src="{{ $continueReadingBook->cover_url }}" alt="{{ $continueReadingBook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    </div>
                    <div class="overflow-hidden flex-1">
                        <h4 class="text-xs font-bold text-[#4A3222] truncate group-hover:text-[#7A5034] transition">{{ $continueReadingBook->title }}</h4>
                        <p class="text-[11px] text-[#8C7A6D] truncate mt-0.5">{{ $continueReadingBook->author }}</p>
                        <span class="inline-block mt-1.5 text-[9px] uppercase px-1.5 py-0.5 rounded bg-[#E4D5C2] text-[#5F3D27] font-semibold">
                            {{ $continueReadingBook->category }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- User Auth Profile Widget -->
            <div class="pt-2 border-t border-[#E8DDCE]">
                @auth
                    <div class="flex items-center justify-between bg-[#F8F3EC] p-3 rounded-xl border border-[#E8DEC0]">
                        <div class="flex items-center gap-2.5 overflow-hidden">
                            <div class="w-8 h-8 rounded-full bg-[#7A5034] text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-[#4A3222] truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-[#8C7A6D] truncate">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pembaca' }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Keluar" class="p-1.5 text-[#9C8B7E] hover:text-red-600 hover:bg-white rounded-lg transition">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="flex-1 text-center py-2 px-3 text-xs font-medium text-[#5F3D27] bg-[#EFE4D6] hover:bg-[#E7D9C7] rounded-xl transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex-1 text-center py-2 px-3 text-xs font-semibold text-white bg-[#7A5034] hover:bg-[#5F3D27] rounded-xl transition shadow-sm">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </aside>

    <!-- MAIN BOOKCASE CONTAINER (SCROLLABLE CANVAS) -->
    <main class="flex-1 md:h-full md:overflow-y-auto bg-[#FAF7F2] border border-[#EADECF] rounded-[32px] p-5 sm:p-7 md:p-8 lg:p-10 shadow-sm flex flex-col justify-between scroll-smooth">
        <div>
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <a href="{{ route('orders.index') }}" class="text-xs font-bold text-emerald-900 underline hover:no-underline">
                        Lihat Pesanan Saya →
                    </a>
                </div>
            @endif

            <!-- Top Controls: Tabs and Search Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <!-- Info Label & Quick Shop Link -->
                <div class="flex items-center gap-2">
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[#5F3D27]">My Library (Rak Buku Pribadi)</h2>
                    @auth
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#EFE5D7] text-[#7A5034] font-semibold">
                            {{ $myBooks->count() }} Buku Dimiliki
                        </span>
                    @endauth
                </div>

                <!-- Search & Shop Action -->
                <div class="flex items-center gap-3">
                    <form action="{{ route('catalog') }}" method="GET" class="relative max-w-xs w-full">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[#A8988B]"></i>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari di rak saya..." 
                               class="w-full pl-10 pr-4 py-2 bg-[#F1E8DC]/80 hover:bg-[#F1E8DC] focus:bg-white text-xs md:text-sm text-[#4A3222] placeholder-[#A8988B] rounded-xl border border-[#E4D7C5] focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    </form>

                    <a href="{{ route('shop') }}" class="px-4 py-2 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Beli Buku di Shop</span>
                    </a>
                </div>
            </div>

            <!-- SHELVES VIEW (GROUPED BY GENRE / KATEGORI BUKU) -->
            <div class="space-y-12">
                
                @if($genresWithBooks->count() > 0)
                    <!-- TAMPILAN RAK PER GENRE BUKU YANG SUDAH DIBELI -->
                    @foreach($genresWithBooks as $genre => $books)
                        <section class="shelf-container">
                            <div class="flex items-center justify-between mb-4 px-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#B0825B]"></span>
                                    <h3 class="font-serif text-lg md:text-xl font-bold text-[#5F3D27] tracking-tight">
                                        Genre: {{ $genre }}
                                    </h3>
                                </div>
                                <span class="text-xs text-[#9C8B7E] font-medium">{{ $books->count() }} buku dikoleksi</span>
                            </div>

                            <!-- Books On Genre Shelf -->
                            <div class="relative pt-4 pb-0 px-4 md:px-8 flex items-end gap-6 sm:gap-8 md:gap-12 overflow-x-auto min-h-[190px]">
                                @foreach($books as $book)
                                    <div class="book-wrapper cursor-pointer shrink-0" @click="openBook({{ json_encode($book) }})">
                                        <div class="w-24 sm:w-28 md:w-32 aspect-[2/3] book-cover bg-[#E9DECD]">
                                            <img src="{{ $book->cover_url }}" 
                                                 alt="{{ $book->title }}" 
                                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-center mt-2 w-24 sm:w-28 md:w-32">
                                            <p class="text-[11px] font-semibold text-[#4A3222] truncate">{{ $book->title }}</p>
                                            <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 3D Shelf Ledge -->
                            <div class="shelf-surface mt-1"></div>
                            <div class="shelf-face"></div>
                        </section>
                    @endforeach
                @else
                    <!-- EMPTY STATE: RAK AWAL KOSONG SEBELUM MEMBELI BUKU -->
                    <div class="py-10 text-center max-w-xl mx-auto space-y-6">
                        
                        <!-- Empty Shelf Graphic -->
                        <div class="shelf-container px-4">
                            <div class="h-28 flex items-center justify-center text-[#A8988B] italic text-xs sm:text-sm">
                                <span>~ Rak buku pribadi Anda masih kosong ~</span>
                            </div>
                            <div class="shelf-surface"></div>
                            <div class="shelf-face"></div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#E7DCCE] rounded-3xl p-8 shadow-xs">
                            <div class="w-14 h-14 rounded-full bg-[#EFE5D7] text-[#7A5034] mx-auto flex items-center justify-center mb-4">
                                <i data-lucide="book-marked" class="w-7 h-7"></i>
                            </div>
                            <h3 class="font-serif text-2xl font-bold text-[#4A3222]">Mulai Koleksi Buku Impianmu</h3>
                            <p class="text-xs sm:text-sm text-[#7A6B60] mt-2 mb-6 leading-relaxed">
                                Buku yang Anda beli di <strong>Shop (Toko Buku)</strong> akan otomatis tertata di sini dan dikelompokkan rapi ke rak berdasarkan masing-masing genrenya.
                            </p>
                            
                            <a href="{{ route('shop') }}" class="px-6 py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md transition inline-flex items-center gap-2 group">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Kunjungi Shop untuk Beli Buku</span>
                                <span class="group-hover:translate-x-1 transition">→</span>
                            </a>
                        </div>
                    </div>
                @endif

            </div>

        </div>

        <!-- Footer Notice -->
        <div class="pt-8 mt-12 border-t border-[#EFE5D7] flex flex-col sm:flex-row items-center justify-between text-xs text-[#9C8B7E] gap-2">
            <p>© 2026 Readowl Bookstore • Terintegrasi dengan MySQL XAMPP (<span class="font-mono text-[#7A5034]">perpus_db</span>)</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('shop') }}" class="hover:text-[#5F3D27] font-semibold text-[#7A5034]">Toko Buku (Shop)</a>
                <span>•</span>
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27] underline">Halaman Utama</a>
            </div>
        </div>
    </main>

    <!-- BOOK DETAIL MODAL (UNTUK BUKU DI RAK KOLEKSI SENDIRI) -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs"
         @keydown.escape.window="modalOpen = false">
        
        <div class="bg-[#FAF7F2] border border-[#E7DBCB] rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative overflow-hidden"
             @click.away="modalOpen = false">
            
            <button @click="modalOpen = false" class="absolute top-4 right-4 p-2 text-[#9C8B7E] hover:text-[#4A3222] hover:bg-[#EFE5D7] rounded-full transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <template x-if="selectedBook">
                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="w-36 sm:w-44 aspect-[2/3] shrink-0 mx-auto sm:mx-0 rounded-xl overflow-hidden shadow-lg border border-[#DACBB8] bg-[#E9DECD]">
                        <img :src="selectedBook.cover_url" 
                             :alt="selectedBook.title" 
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-[#9C6D47] bg-[#EFE4D6] px-2.5 py-0.5 rounded-full" x-text="'Genre: ' + selectedBook.category"></span>
                            
                            <h3 class="font-serif text-2xl font-bold text-[#4A3222] mt-2 leading-tight" x-text="selectedBook.title"></h3>
                            <p class="text-sm text-[#7A6B60] mt-0.5 font-medium" x-text="'Penulis: ' + selectedBook.author"></p>

                            <div class="flex items-center gap-4 mt-3 text-xs text-[#8C7A6D]">
                                <div class="flex items-center text-amber-600 font-bold gap-1">
                                    <span>★</span>
                                    <span x-text="selectedBook.rating"></span>
                                </div>
                                <span>•</span>
                                <span x-text="selectedBook.pages + ' Halaman'"></span>
                                <span>•</span>
                                <span x-text="'Tahun: ' + (selectedBook.published_year || '-')"></span>
                            </div>

                            <div class="mt-4">
                                <h4 class="text-xs font-bold uppercase text-[#8C7A6D] tracking-wider mb-1">Sinopsis</h4>
                                <p class="text-xs text-[#5D4F46] leading-relaxed line-clamp-4" x-text="selectedBook.synopsis || 'Tidak ada sinopsis tersedia.'"></p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-[#E8DDCE] flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                Buku Telah Dimiliki
                            </span>
                            <button type="button" @click="startReading()" class="px-5 py-2.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-2 hover:scale-105 active:scale-95">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                                <span>Buka Pembaca Digital</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DIGITAL E-BOOK READER MODAL -->
    <div x-show="readerOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex flex-col backdrop-blur-md transition-colors duration-300"
         :class="{
            'bg-[#FDFBF7] text-[#2C241E]': readerTheme === 'sepia',
            'bg-white text-slate-800': readerTheme === 'light',
            'bg-[#181614] text-[#E0D8D0]': readerTheme === 'dark'
         }"
         style="display: none;"
         @keydown.escape.window="readerOpen = false"
         @keydown.arrow-right.window="if(currentPage < 3) currentPage++"
         @keydown.arrow-left.window="if(currentPage > 1) currentPage--">

        <!-- Top Reader Toolbar -->
        <header class="h-16 border-b px-6 flex items-center justify-between shrink-0 transition-colors"
                :class="{
                    'border-[#EAE1D5] bg-[#F7F2E8]/90': readerTheme === 'sepia',
                    'border-slate-200 bg-slate-50/90': readerTheme === 'light',
                    'border-[#2D2824] bg-[#1F1C19]/90': readerTheme === 'dark'
                }">
            <div class="flex items-center gap-3">
                <button type="button" @click="readerOpen = false" class="p-2 rounded-xl transition hover:bg-black/5 dark:hover:bg-white/10" title="Tutup Pembaca">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <div class="border-l pl-3" :class="readerTheme === 'dark' ? 'border-[#3D352E]' : 'border-slate-300'">
                    <h2 class="font-serif font-bold text-sm truncate max-w-xs md:max-w-md" x-text="selectedBook?.title"></h2>
                    <p class="text-[11px] opacity-70" x-text="selectedBook?.author"></p>
                </div>
            </div>

            <!-- Reader Controls -->
            <div class="flex items-center gap-3">
                <!-- Font Size Controls -->
                <div class="flex items-center gap-1 rounded-xl p-1 border"
                     :class="readerTheme === 'dark' ? 'border-[#3D352E] bg-[#26211C]' : 'border-[#E0D6C8] bg-white/70'">
                    <button type="button" @click="fontSize = Math.max(13, fontSize - 2)" class="px-2 py-1 text-xs font-bold rounded-lg hover:bg-black/10">A-</button>
                    <span class="text-[11px] px-1 font-mono font-semibold" x-text="fontSize + 'px'"></span>
                    <button type="button" @click="fontSize = Math.min(24, fontSize + 2)" class="px-2 py-1 text-xs font-bold rounded-lg hover:bg-black/10">A+</button>
                </div>

                <!-- Theme Switcher -->
                <div class="flex items-center gap-1 rounded-xl p-1 border"
                     :class="readerTheme === 'dark' ? 'border-[#3D352E] bg-[#26211C]' : 'border-[#E0D6C8] bg-white/70'">
                    <button type="button" @click="readerTheme = 'sepia'" :class="{'ring-2 ring-amber-600 font-bold': readerTheme === 'sepia'}" class="w-7 h-7 rounded-lg bg-[#F7F2E8] border border-[#D9CDBF] text-[11px] flex items-center justify-center text-[#5C4530]" title="Sepia">📖</button>
                    <button type="button" @click="readerTheme = 'light'" :class="{'ring-2 ring-sky-600 font-bold': readerTheme === 'light'}" class="w-7 h-7 rounded-lg bg-white border border-slate-300 text-[11px] flex items-center justify-center text-slate-800" title="Light">☀️</button>
                    <button type="button" @click="readerTheme = 'dark'" :class="{'ring-2 ring-amber-400 font-bold': readerTheme === 'dark'}" class="w-7 h-7 rounded-lg bg-[#141210] border border-[#3E3832] text-[11px] flex items-center justify-center text-amber-300" title="Dark">🌙</button>
                </div>

                <button type="button" @click="readerOpen = false" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-[#7A5034] text-white hover:bg-[#5F3D27] transition">
                    Selesai
                </button>
            </div>
        </header>

        <!-- Reading Canvas / Scrollable Body -->
        <main class="flex-1 overflow-y-auto px-6 py-10 flex justify-center">
            <article class="w-full max-w-2xl mx-auto font-serif leading-relaxed" :style="'font-size: ' + fontSize + 'px; line-height: 1.8;'">
                
                <!-- Page 1: Chapter 1 -->
                <div x-show="currentPage === 1" class="space-y-6 animate-fade-in">
                    <div class="text-center pb-8 border-b border-dashed" :class="readerTheme === 'dark' ? 'border-[#3A332C]' : 'border-[#E4D9CC]'">
                        <span class="text-xs uppercase tracking-widest font-sans font-bold opacity-60" x-text="selectedBook?.category"></span>
                        <h1 class="font-serif text-3xl sm:text-4xl font-bold mt-2 mb-2" x-text="selectedBook?.title"></h1>
                        <p class="font-sans text-xs italic opacity-80" x-text="'Karya: ' + selectedBook?.author"></p>
                    </div>

                    <div class="text-center my-6">
                        <span class="font-sans text-xs uppercase tracking-widest font-semibold px-4 py-1 rounded-full border inline-block"
                              :class="readerTheme === 'dark' ? 'border-[#3D352E] text-amber-400' : 'border-[#E0D6C8] text-[#8C5D37]'">
                            Bab I — Permulaan Kisah
                        </span>
                    </div>

                    <p class="first-letter:text-5xl first-letter:font-bold first-letter:font-serif first-letter:float-left first-letter:mr-3 first-letter:leading-none text-justify">
                        <span x-text="selectedBook?.synopsis || 'Setiap lembar buku adalah sebuah jendela menuju semesta baru yang belum pernah terjamah oleh pandangan kita sebelumnya.'"></span>
                    </p>

                    <p class="text-justify">
                        Hening menyelimuti ruangan ketika kata-kata mulai membentuk bayangannya sendiri di dalam benak. Tidak ada yang lebih berharga daripada momen intim saat pikiran kita berdialog langsung dengan buah karya pemikiran sang penulis beratus-ratus kilometer atau bahkan berabad-abad jaraknya.
                    </p>

                    <blockquote class="my-6 pl-4 border-l-4 italic font-sans text-sm py-1"
                                :class="readerTheme === 'dark' ? 'border-amber-500 bg-[#241F1A] text-amber-200' : 'border-[#7A5034] bg-[#F2E8DC] text-[#4A3222]'">
                        "Buku tidak mengubah dunia dengan sendirinya, melainkan mengubah pikiran orang-orang yang kelak akan mengubah dunia."
                    </blockquote>

                    <p class="text-justify">
                        Kita membuka halaman demi halaman dengan rasa ingin tahu yang tak kunjung padam. Detail-detail kecil yang dirajut oleh <span class="font-semibold" x-text="selectedBook?.author"></span> menghidupkan kembali imajinasi dan memperluas cakrawala pemahaman kita.
                    </p>
                </div>

                <!-- Page 2: Chapter 2 -->
                <div x-show="currentPage === 2" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="text-center my-6">
                        <span class="font-sans text-xs uppercase tracking-widest font-semibold px-4 py-1 rounded-full border inline-block"
                              :class="readerTheme === 'dark' ? 'border-[#3D352E] text-amber-400' : 'border-[#E0D6C8] text-[#8C5D37]'">
                            Bab II — Menyelami Kedalaman
                        </span>
                    </div>

                    <p class="text-justify">
                        Melangkah lebih dalam ke dalam inti bahasan, kita menjumpai gagasan-gagasan yang menantang prasangka lama kita. Melalui sudut pandang <span class="font-semibold" x-text="selectedBook?.title"></span>, realitas tampak lebih berdimensi dan kaya akan makna yang berlapis.
                    </p>

                    <p class="text-justify">
                        Pemberdayaan diri sesungguhnya bermula dari kesediaan untuk mengakui bahwa apa yang kita ketahui hanyalah sebutir pasir di tepi samudra ketidaktahuan. Ketika membaca dengan sungguh-sungguh, kita sedang merawat taman kebijaksanaan dalam jiwa kita sendiri.
                    </p>

                    <div class="p-5 rounded-2xl border text-center my-6"
                         :class="readerTheme === 'dark' ? 'border-[#3D352E] bg-[#211D19]' : 'border-[#E2D7C7] bg-[#F5ECE0]'">
                        <p class="text-xs font-sans uppercase tracking-wider font-bold mb-1 opacity-70">Catatan Pembaca</p>
                        <p class="italic text-sm">"Garis bawahi pemikiran yang menggetarkan hatimu, renungkan kembali saat fajar menyingsing."</p>
                    </div>

                    <p class="text-justify">
                        Perjalanan masih berlanjut, dan setiap langkah yang diambil menguatkan tekad untuk terus mengeksplorasi hingga halaman paling penghabisan.
                    </p>
                </div>

                <!-- Page 3: Chapter 3 & Conclusion -->
                <div x-show="currentPage === 3" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="text-center my-6">
                        <span class="font-sans text-xs uppercase tracking-widest font-semibold px-4 py-1 rounded-full border inline-block"
                              :class="readerTheme === 'dark' ? 'border-[#3D352E] text-amber-400' : 'border-[#E0D6C8] text-[#8C5D37]'">
                            Bab III — Refleksi & Penutup
                        </span>
                    </div>

                    <p class="text-justify">
                        Akhir dari sebuah bacaan bukanlah garis finish sebuah petualangan, melainkan batu loncatan untuk memahami dunia nyata dengan kacamata yang lebih jernih dan penuh empati.
                    </p>

                    <div class="py-8 my-6 border-y text-center space-y-3"
                         :class="readerTheme === 'dark' ? 'border-[#3D352E]' : 'border-[#E2D7C7]'">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-600 mb-2">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-serif text-xl font-bold">Selamat! Anda Telah Membaca Buku Ini</h3>
                        <p class="text-xs font-sans max-w-md mx-auto opacity-80">
                            Buku <span class="font-bold" x-text="selectedBook?.title"></span> kini tersimpan abadi di rak koleksi pribadi Readowl Anda.
                        </p>
                        <div class="pt-3">
                            <button type="button" @click="readerOpen = false" class="px-5 py-2.5 rounded-xl font-sans text-xs font-bold bg-[#7A5034] text-white hover:bg-[#5F3D27] transition shadow-md">
                                Kembali ke Rak Buku
                            </button>
                        </div>
                    </div>
                </div>

            </article>
        </main>

        <!-- Bottom Page Pagination Footer -->
        <footer class="h-14 border-t px-6 flex items-center justify-between shrink-0 transition-colors"
                :class="{
                    'border-[#EAE1D5] bg-[#F7F2E8]/90': readerTheme === 'sepia',
                    'border-slate-200 bg-slate-50/90': readerTheme === 'light',
                    'border-[#2D2824] bg-[#1F1C19]/90': readerTheme === 'dark'
                }">
            <button type="button" 
                    @click="if(currentPage > 1) currentPage--" 
                    :disabled="currentPage <= 1"
                    :class="currentPage <= 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-black/5 dark:hover:bg-white/10'"
                    class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                <span>Halaman Sebelumnya</span>
            </button>

            <!-- Page Indicator & Progress Bar -->
            <div class="flex flex-col items-center gap-1">
                <span class="text-xs font-mono font-bold" x-text="'Halaman ' + currentPage + ' dari 3'"></span>
                <div class="w-32 h-1.5 bg-black/10 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-[#7A5034] dark:bg-amber-500 rounded-full transition-all duration-300"
                         :style="'width: ' + (currentPage / 3 * 100) + '%'"></div>
                </div>
            </div>

            <button type="button" 
                    @click="if(currentPage < 3) currentPage++" 
                    :disabled="currentPage >= 3"
                    :class="currentPage >= 3 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-black/5 dark:hover:bg-white/10'"
                    class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition">
                <span>Halaman Selanjutnya</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </footer>
    </div>
</div>
@endsection
