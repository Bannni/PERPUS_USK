@extends('layouts.base')

@section('title', 'My Library - Readowl Bookstore')

@section('content')
<div class="min-h-screen md:h-screen md:overflow-hidden bg-[#F4ECE1]/60 flex flex-col md:flex-row p-3 md:p-6 lg:p-8 gap-5" x-data="{
    activeTab: '{{ $activeTab ?? 'shelves' }}',
    selectedBook: null,
    modalOpen: false,
    openBook(book) {
        this.selectedBook = book;
        this.modalOpen = true;
    }
}">
    <!-- SIDEBAR (FIXED ON DESKTOP) -->
    <aside class="w-full md:w-64 lg:w-72 flex flex-col justify-between shrink-0 md:h-full md:overflow-y-auto bg-[#FAF7F2] md:bg-transparent rounded-2xl p-4 md:p-0 select-none">
        <div>
            <!-- Readowl Logo -->
            <div class="flex items-center gap-3 px-2 py-4 mb-4">
                <div class="w-10 h-10 rounded-full bg-[#E8DCB9]/60 flex items-center justify-center text-[#7A5034]">
                    <!-- Owl icon SVG -->
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

                <a href="{{ route('catalog', ['tab' => 'all']) }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#EFE5D7]/50 transition group">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#9C8B7E] group-hover:text-[#7A5034] transition"></i>
                    <span class="font-medium text-sm">Shop</span>
                </a>

                <a href="{{ route('home') }}#features" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#EFE5D7]/50 transition group">
                    <i data-lucide="newspaper" class="w-5 h-5 text-[#9C8B7E] group-hover:text-[#7A5034] transition"></i>
                    <span class="font-medium text-sm">News</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Widgets -->
        <div class="mt-8 space-y-4">
            <!-- Continue Reading Card (Matching Screenshot bottom left) -->
            @if($continueReadingBook)
            <div class="bg-[#F2E8DC] border border-[#E3D6C5] rounded-2xl p-3.5 shadow-sm">
                <div class="text-[11px] font-semibold uppercase tracking-wider text-[#8A7463] mb-2.5 flex items-center justify-between">
                    <span>Continue reading</span>
                    <span class="text-[#7A5034]">{{ $continueReadingBook->reading_progress }}%</span>
                </div>
                <div class="flex items-center gap-3 cursor-pointer group" @click="openBook({{ json_encode($continueReadingBook) }})">
                    <div class="w-12 h-16 shrink-0 rounded overflow-hidden shadow-sm border border-[#DACBB8]">
                        <img src="{{ $continueReadingBook->cover_url }}" alt="{{ $continueReadingBook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    </div>
                    <div class="overflow-hidden flex-1">
                        <h4 class="text-xs font-bold text-[#4A3222] truncate group-hover:text-[#7A5034] transition">{{ $continueReadingBook->title }}</h4>
                        <p class="text-[11px] text-[#8C7A6D] truncate mt-0.5">{{ $continueReadingBook->author }}</p>
                        
                        <!-- Mini Progress Bar -->
                        <div class="w-full bg-[#DFD0BD] h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-[#B0825B] h-full rounded-full" style="width: {{ $continueReadingBook->reading_progress }}%"></div>
                        </div>
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
                                <p class="text-[10px] text-[#8C7A6D] truncate">{{ Auth::user()->email }}</p>
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
            <!-- Top Controls: Tabs and Search Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <!-- Tabs: Shelves vs All Books -->
                <div class="inline-flex items-center gap-1.5 p-1 bg-[#F1E8DC] rounded-xl border border-[#E4D7C5]">
                    <button type="button" 
                            @click="activeTab = 'shelves'" 
                            :class="activeTab === 'shelves' ? 'bg-[#FAF7F2] text-[#5F3D27] font-semibold shadow-xs' : 'text-[#8C7B6E] hover:text-[#5F3D27]'"
                            class="px-4 py-1.5 rounded-lg text-xs md:text-sm transition">
                        Shelves
                    </button>
                    <button type="button" 
                            @click="activeTab = 'all'" 
                            :class="activeTab === 'all' ? 'bg-[#FAF7F2] text-[#5F3D27] font-semibold shadow-xs' : 'text-[#8C7B6E] hover:text-[#5F3D27]'"
                            class="px-4 py-1.5 rounded-lg text-xs md:text-sm transition">
                        All Books
                    </button>
                </div>

                <!-- Search Input Bar -->
                <form action="{{ route('catalog') }}" method="GET" class="relative max-w-md w-full">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[#A8988B]"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Search in My library..." 
                           class="w-full pl-10 pr-4 py-2 bg-[#F1E8DC]/80 hover:bg-[#F1E8DC] focus:bg-white text-xs md:text-sm text-[#4A3222] placeholder-[#A8988B] rounded-xl border border-[#E4D7C5] focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    @if(request('q'))
                        <a href="{{ route('catalog') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-[#A8988B] hover:text-[#5F3D27]">Reset</a>
                    @endif
                </form>
            </div>

            <!-- TAB 1: SHELVES VIEW (THE SKEUOMORPHIC 3D BOOKSHELF) -->
            <div x-show="activeTab === 'shelves'" class="space-y-12">
                
                <!-- SHELF 1: Currently reading -->
                <section class="shelf-container">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-serif text-lg md:text-xl font-bold text-[#5F3D27] tracking-tight">Currently reading</h3>
                        @if($currentlyReading->count() > 0)
                            <span class="text-xs text-[#9C8B7E] font-medium">{{ $currentlyReading->count() }} buku</span>
                        @endif
                    </div>

                    <!-- Books On Shelf -->
                    <div class="relative pt-4 pb-0 px-4 md:px-8 flex items-end gap-6 sm:gap-8 md:gap-12 overflow-x-auto min-h-[190px]">
                        @forelse($currentlyReading as $book)
                            <div class="book-wrapper cursor-pointer shrink-0" @click="openBook({{ json_encode($book) }})">
                                <div class="w-24 sm:w-28 md:w-32 aspect-[2/3] book-cover bg-[#E9DECD]">
                                    <img src="{{ $book->cover_url }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="text-center mt-2 w-24 sm:w-28 md:w-32">
                                    <p class="text-[11px] font-semibold text-[#4A3222] truncate">{{ $book->title }}</p>
                                    <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center w-full text-xs text-[#9C8B7E] italic">
                                Belum ada buku yang sedang dibaca.
                            </div>
                        @endforelse
                    </div>

                    <!-- 3D Shelf Ledge -->
                    <div class="shelf-surface mt-1"></div>
                    <div class="shelf-face"></div>
                </section>

                <!-- SHELF 2: Next up -->
                <section class="shelf-container">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-serif text-lg md:text-xl font-bold text-[#5F3D27] tracking-tight">Next up</h3>
                        <a href="{{ route('catalog', ['tab' => 'all']) }}" class="text-xs font-semibold text-[#7A5034] hover:text-[#5F3D27] flex items-center gap-1 group">
                            <span>Full shelf</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Books On Shelf -->
                    <div class="relative pt-4 pb-0 px-4 md:px-8 flex items-end gap-5 sm:gap-7 md:gap-10 overflow-x-auto min-h-[190px]">
                        @forelse($nextUp as $book)
                            <div class="book-wrapper cursor-pointer shrink-0" @click="openBook({{ json_encode($book) }})">
                                <div class="w-20 sm:w-24 md:w-28 aspect-[2/3] book-cover bg-[#E9DECD]">
                                    <img src="{{ $book->cover_url }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="text-center mt-2 w-20 sm:w-24 md:w-28">
                                    <p class="text-[11px] font-semibold text-[#4A3222] truncate">{{ $book->title }}</p>
                                    <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center w-full text-xs text-[#9C8B7E] italic">
                                Belum ada buku di antrean bacaan.
                            </div>
                        @endforelse
                    </div>

                    <!-- 3D Shelf Ledge -->
                    <div class="shelf-surface mt-1"></div>
                    <div class="shelf-face"></div>
                </section>

                <!-- SHELF 3: Finished -->
                <section class="shelf-container">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h3 class="font-serif text-lg md:text-xl font-bold text-[#5F3D27] tracking-tight">Finished</h3>
                        <a href="{{ route('catalog', ['tab' => 'all']) }}" class="text-xs font-semibold text-[#7A5034] hover:text-[#5F3D27] flex items-center gap-1 group">
                            <span>Full shelf</span>
                            <span class="group-hover:translate-x-0.5 transition">→</span>
                        </a>
                    </div>

                    <!-- Books On Shelf -->
                    <div class="relative pt-4 pb-0 px-4 md:px-8 flex items-end gap-5 sm:gap-7 md:gap-10 overflow-x-auto min-h-[190px]">
                        @forelse($finished as $book)
                            <div class="book-wrapper cursor-pointer shrink-0" @click="openBook({{ json_encode($book) }})">
                                <div class="w-20 sm:w-24 md:w-28 aspect-[2/3] book-cover bg-[#E9DECD]">
                                    <img src="{{ $book->cover_url }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="text-center mt-2 w-20 sm:w-24 md:w-28">
                                    <p class="text-[11px] font-semibold text-[#4A3222] truncate">{{ $book->title }}</p>
                                    <p class="text-[9px] text-[#8C7A6D] truncate">{{ $book->author }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center w-full text-xs text-[#9C8B7E] italic">
                                Belum ada buku yang selesai dibaca.
                            </div>
                        @endforelse
                    </div>

                    <!-- 3D Shelf Ledge -->
                    <div class="shelf-surface mt-1"></div>
                    <div class="shelf-face"></div>
                </section>

            </div>

            <!-- TAB 2: ALL BOOKS GRID VIEW -->
            <div x-show="activeTab === 'all'" x-cloak class="mt-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-serif text-xl font-bold text-[#5F3D27]">Semua Koleksi Buku ({{ $allBooks->count() }})</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach($allBooks as $book)
                        <div class="group cursor-pointer flex flex-col" @click="openBook({{ json_encode($book) }})">
                            <div class="aspect-[2/3] w-full rounded-xl overflow-hidden shadow-md group-hover:shadow-xl group-hover:-translate-y-1.5 transition-all duration-300 relative border border-[#E2D5C4]">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-xs text-[#7A5034] text-[10px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                    ★ {{ $book->rating }}
                                </div>
                            </div>
                            <div class="mt-2.5">
                                <h4 class="font-bold text-xs text-[#4A3222] group-hover:text-[#7A5034] transition line-clamp-1">{{ $book->title }}</h4>
                                <p class="text-[11px] text-[#8C7A6D] line-clamp-1">{{ $book->author }}</p>
                                <p class="text-xs font-semibold text-[#7A5034] mt-1">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <div class="pt-8 mt-12 border-t border-[#EFE5D7] flex flex-col sm:flex-row items-center justify-between text-xs text-[#9C8B7E] gap-2">
            <p>© 2026 Readowl Bookstore • Terintegrasi dengan MySQL XAMPP (<span class="font-mono text-[#7A5034]">perpus_db</span>)</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27] underline">Halaman Utama</a>
                <span>•</span>
                <span>Mode Rak Klasik</span>
            </div>
        </div>
    </main>

    <!-- BOOK DETAIL MODAL -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs"
         @keydown.escape.window="modalOpen = false">
        
        <div class="bg-[#FAF7F2] border border-[#E7DBCB] rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative overflow-hidden"
             @click.away="modalOpen = false">
            
            <!-- Close Button -->
            <button @click="modalOpen = false" class="absolute top-4 right-4 p-2 text-[#9C8B7E] hover:text-[#4A3222] hover:bg-[#EFE5D7] rounded-full transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <template x-if="selectedBook">
                <div class="flex flex-col sm:flex-row gap-6">
                    <!-- Book Cover -->
                    <div class="w-36 sm:w-44 aspect-[2/3] shrink-0 mx-auto sm:mx-0 rounded-xl overflow-hidden shadow-lg border border-[#DACBB8]">
                        <img :src="selectedBook.cover_url" :alt="selectedBook.title" class="w-full h-full object-cover">
                    </div>

                    <!-- Details -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-[#9C6D47] bg-[#EFE4D6] px-2.5 py-0.5 rounded-full" x-text="selectedBook.category"></span>
                            
                            <h3 class="font-serif text-2xl font-bold text-[#4A3222] mt-2 leading-tight" x-text="selectedBook.title"></h3>
                            <p class="text-sm text-[#7A6B60] mt-0.5 font-medium" x-text="'Penulis: ' + selectedBook.author"></p>

                            <!-- Rating & Pages -->
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

                            <!-- Progress (if available) -->
                            <template x-if="selectedBook.reading_progress > 0">
                                <div class="mt-4 bg-[#F2E8DC] p-2.5 rounded-xl border border-[#E3D6C5]">
                                    <div class="flex justify-between text-xs font-semibold text-[#6E4F39] mb-1">
                                        <span>Progres Membaca</span>
                                        <span x-text="selectedBook.reading_progress + '%'"></span>
                                    </div>
                                    <div class="w-full bg-[#DFD0BD] h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#A07047] h-full rounded-full" :style="'width: ' + selectedBook.reading_progress + '%'"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Synopsis -->
                            <div class="mt-4">
                                <h4 class="text-xs font-bold uppercase text-[#8C7A6D] tracking-wider mb-1">Sinopsis</h4>
                                <p class="text-xs text-[#5D4F46] leading-relaxed line-clamp-4" x-text="selectedBook.synopsis || 'Tidak ada sinopsis tersedia.'"></p>
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div class="mt-6 pt-4 border-t border-[#E8DDCE] flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-[#8C7A6D] block">Harga Buku</span>
                                <span class="text-lg font-bold text-[#7A5034]" x-text="'Rp ' + (selectedBook.price ? selectedBook.price.toLocaleString('id-ID') : '75.000')"></span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="alert('Fitur baca buku digital segera hadir!');" class="px-4 py-2 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                    Baca Sekarang
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
