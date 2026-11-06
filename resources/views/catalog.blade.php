@extends('layouts.base')

@section('title', 'My Library - Rak Buku Koleksi Saya')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]" x-data="{
    activeTab: '{{ $activeTab ?? 'shelves' }}',
    selectedBook: null,
    modalOpen: false,
    openBook(book) {
        this.selectedBook = book;
        this.modalOpen = true;
    }
}">
    <!-- Top Unified Navbar -->
    @include('partials.navbar')

    <!-- MAIN BOOKCASE CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div>
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-2xl flex items-center gap-3 shadow-xs">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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

            <!-- Top Controls: Header and Search Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <!-- Info Label -->
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#5F3D27]">My Library (Rak Koleksi Buku)</h1>
                        @auth
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#EFE5D7] text-[#7A5034] font-semibold">
                                {{ $myBooks->count() }} Buku Resmi Dibeli
                            </span>
                        @endauth
                    </div>
                    <p class="text-xs sm:text-sm text-[#8C7A6D] mt-1">Daftar buku fisik yang telah Anda beli dan tersusun rapi di rak 3D berdasarkan genrenya.</p>
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
                        <span>Beli Buku Baru</span>
                    </a>
                </div>
            </div>

            <!-- SHELVES VIEW (GROUPED BY GENRE / KATEGORI BUKU) -->
            <div class="space-y-12">
                
                @if($genresWithBooks->count() > 0)
                    <!-- TAMPILAN RAK PER GENRE BUKU YANG SUDAH DIBELI -->
                    @foreach($genresWithBooks as $genre => $books)
                        <section class="shelf-container bg-white/60 border border-[#EBE1D3] rounded-3xl p-6 sm:p-8 shadow-xs">
                            <div class="flex items-center justify-between mb-4 px-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#B0825B]"></span>
                                    <h3 class="font-serif text-lg md:text-xl font-bold text-[#5F3D27] tracking-tight">
                                        Genre: {{ $genre }}
                                    </h3>
                                </div>
                                <span class="text-xs text-[#9C8B7E] font-medium">{{ $books->count() }} buku dikoleksi</span>
                            </div>

                            <!-- Books On Genre Shelf -->
                            <div class="relative pt-6 pb-0 px-4 md:px-8 flex items-end gap-6 sm:gap-8 md:gap-12 overflow-x-auto min-h-[210px]">
                                @foreach($books as $book)
                                    <div class="book-wrapper cursor-pointer shrink-0 group" @click="openBook({{ json_encode($book) }})">
                                        <div class="w-24 sm:w-28 md:w-32 aspect-[2/3] book-cover bg-[#E9DECD] shadow-md group-hover:shadow-xl transition duration-300">
                                            <img src="{{ $book->cover_url }}" 
                                                 alt="{{ $book->title }}" 
                                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-center mt-2.5 w-24 sm:w-28 md:w-32">
                                            <p class="text-[11px] font-semibold text-[#4A3222] truncate group-hover:text-[#7A5034] transition">{{ $book->title }}</p>
                                            <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 3D Shelf Ledge -->
                            <div class="shelf-surface mt-2"></div>
                            <div class="shelf-face"></div>
                        </section>
                    @endforeach
                @else
                    <!-- EMPTY STATE: RAK AWAL KOSONG SEBELUM MEMBELI BUKU -->
                    <div class="py-12 text-center max-w-xl mx-auto space-y-6">
                        
                        <!-- Empty Shelf Graphic -->
                        <div class="shelf-container px-4">
                            <div class="h-28 flex items-center justify-center text-[#A8988B] italic text-xs sm:text-sm">
                                <span>~ Rak buku pribadi Anda masih kosong ~</span>
                            </div>
                            <div class="shelf-surface"></div>
                            <div class="shelf-face"></div>
                        </div>

                        <div class="bg-white border border-[#E7DCCE] rounded-3xl p-8 sm:p-10 shadow-xs">
                            <div class="w-14 h-14 rounded-full bg-[#EFE5D7] text-[#7A5034] mx-auto flex items-center justify-center mb-4">
                                <i data-lucide="book-marked" class="w-7 h-7"></i>
                            </div>
                            <h3 class="font-serif text-2xl font-bold text-[#4A3222]">Rak Koleksi Buku Masih Kosong</h3>
                            <p class="text-xs sm:text-sm text-[#7A6B60] mt-2 mb-6 leading-relaxed">
                                Rak ini berfungsi sebagai daftar koleksi buku fisik yang telah Anda beli. Setelah pesanan dikonfirmasi dan disetujui (ACC / Sukses) oleh Admin, buku akan otomatis tersusun rapi di rak ini sesuai genrenya.
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
            <p>© 2026 Readowl Bookstore • Toko Buku Resmi & Pengiriman ke Rumah</p>
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
         style="display: none;"
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

                        <div class="mt-6 pt-4 border-t border-[#E8DDCE] flex flex-col sm:flex-row items-center justify-between gap-3">
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-xs">
                                <i data-lucide="package-check" class="w-4 h-4 text-emerald-700"></i>
                                Buku Fisik Resmi Dimiliki
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-[#EFE4D6] hover:bg-[#E5D7C5] text-[#5F3D27] text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                                    <span>Bukti Pembelian</span>
                                </a>
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
