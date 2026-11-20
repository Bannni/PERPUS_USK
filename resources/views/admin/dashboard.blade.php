@extends('layouts.base')

@section('title', 'Admin Dashboard - Readowl Studio (Standar BNSP)')

@section('content')
<div class="min-h-screen bg-[#151311] text-[#EBE5DB]" x-data="{
    activeTab: '{{ $activeTab ?? 'books' }}',
    createModalOpen: false,
    editModalOpen: false,
    selectedBook: null,
    deleteModalOpen: false,
    bookToDelete: null,
    categoryModalOpen: false,
    selectedCategory: null,
    createCoverPreview: null,
    editCoverPreview: null,
    openEdit(book) {
        this.selectedBook = JSON.parse(JSON.stringify(book));
        this.selectedBook.stock_quantity = book.stock ? book.stock.quantity : 10;
        this.selectedBook.description = book.description || book.synopsis || '';
        this.editCoverPreview = book.cover_image || book.cover_url || null;
        this.editModalOpen = true;
    },
    confirmDelete(book) {
        this.bookToDelete = book;
        this.deleteModalOpen = true;
    },
    openCategoryCreate() {
        this.selectedCategory = null;
        this.categoryModalOpen = true;
    },
    openCategoryEdit(category) {
        this.selectedCategory = JSON.parse(JSON.stringify(category));
        this.categoryModalOpen = true;
    }
}">

    <!-- ADMIN TOP NAVIGATION -->
    <header class="border-b border-[#2C2621] bg-[#1A1815]/90 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center text-[#151311] font-bold shadow-md">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="font-serif text-xl font-bold tracking-tight text-white block">READOWL STUDIO</span>
                    <span class="text-[10px] tracking-widest uppercase text-amber-400 font-semibold">Panel Administrator • BNSP JWD</span>
                </div>
            </div>

            <!-- Tab Switchers Center/Right -->
            <div class="flex items-center gap-2 sm:gap-3">
                <nav class="flex items-center bg-[#231E19] border border-[#3A3228] p-1 rounded-2xl overflow-x-auto max-w-full">
                    <!-- Tab 1: Books -->
                    <button type="button" 
                            @click="activeTab = 'books'" 
                            :class="activeTab === 'books' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 shrink-0">
                        <i data-lucide="book" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Koleksi Buku</span>
                        <span class="text-[10px] opacity-80">({{ $totalBooks }})</span>
                    </button>

                    <!-- Tab 2: Categories (BNSP Spec) -->
                    <button type="button" 
                            @click="activeTab = 'categories'" 
                            :class="activeTab === 'categories' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 shrink-0">
                        <i data-lucide="tags" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Kategori</span>
                        <span class="text-[10px] opacity-80">({{ $categories->count() }})</span>
                    </button>

                    <!-- Tab 3: Orders (BNSP Spec: update status) -->
                    <button type="button" 
                            @click="activeTab = 'orders'" 
                            :class="activeTab === 'orders' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 shrink-0">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Pesanan Masuk</span>
                        <span class="text-[10px] opacity-80">({{ $totalOrders }})</span>
                    </button>

                    <!-- Tab 4: Users -->
                    <button type="button" 
                            @click="activeTab = 'users'" 
                            :class="activeTab === 'users' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 shrink-0">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Pengguna</span>
                        <span class="text-[10px] opacity-80">({{ $totalUsers }})</span>
                    </button>

                    <!-- Tab 5: Contacts (BNSP Spec: incoming messages) -->
                    <button type="button" 
                            @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5 shrink-0 relative">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Pesan Kontak</span>
                        @if($unreadContacts > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-red-500 text-white animate-pulse">
                                {{ $unreadContacts }}
                            </span>
                        @endif
                    </button>
                </nav>

                <button @click="createModalOpen = true; activeTab = 'books'" class="px-3 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5 shrink-0">
                    <i data-lucide="plus" class="w-4 h-4 stroke-[3]"></i>
                    <span class="hidden md:inline">Tambah Buku</span>
                </button>

                <button type="button" 
                        @click="$dispatch('open-logout-modal')"
                        onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))"
                        title="Keluar dari Panel Admin" 
                        class="p-2 text-[#9E9081] hover:text-red-400 hover:bg-[#26211C] rounded-xl transition cursor-pointer">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN BODY -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs sm:text-sm rounded-2xl flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-950/60 border border-red-500/40 text-red-300 text-xs sm:text-sm rounded-2xl flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-400 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-red-950/60 border border-red-500/40 text-red-300 text-xs rounded-2xl space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-400 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- 4 TOP OVERVIEW STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Card 1: Books -->
            <div @click="activeTab = 'books'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-amber-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Total Koleksi Buku</span>
                    <i data-lucide="book" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalBooks }}</div>
                <span class="text-[10px] text-amber-400/90 mt-1 block">Total Stok Fisik: {{ $totalStockQty }} eks</span>
            </div>

            <!-- Card 2: Revenue -->
            <div @click="activeTab = 'orders'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-emerald-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Total Nilai Pesanan</span>
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[10px] text-emerald-400/90 mt-1 block">{{ $totalOrders }} transaksi terdata</span>
            </div>

            <!-- Card 3: Users -->
            <div @click="activeTab = 'users'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-sky-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Pengguna Terdaftar</span>
                    <i data-lucide="users" class="w-4 h-4 text-sky-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalUsers }}</div>
                <span class="text-[10px] text-sky-400/90 mt-1 block">{{ $totalMembers }} pembaca • {{ $totalAdmins }} admin</span>
            </div>

            <!-- Card 4: Categories & Unread Messages -->
            <div @click="activeTab = 'contacts'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-amber-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Pesan Kontak Baru</span>
                    <i data-lucide="mail" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $unreadContacts }}</div>
                <span class="text-[10px] text-amber-400/90 mt-1 block">{{ $categories->count() }} kategori genre buku</span>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 1: MANAJEMEN & KURASI BUKU (CRUD + STOK TERPISAH)          -->
        <!-- ============================================================== -->
        <section x-show="activeTab === 'books'">
            
            <!-- Filter & Search Bar for Books -->
            <div class="bg-[#1D1A16] border border-[#332C24] rounded-2xl p-4 sm:p-5 mb-8 space-y-4">
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Category Filter Pills -->
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'stock_filter' => $stockFilter]) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($category) ? 'bg-amber-400 text-[#151311] shadow-xs' : 'text-[#A69785] bg-[#26211B] hover:bg-[#332C24]' }}">
                            Semua Kategori
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('admin.dashboard', ['tab' => 'books', 'category' => $cat->name, 'stock_filter' => $stockFilter, 'q' => request('q')]) }}" 
                               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $category === $cat->name ? 'bg-amber-400 text-[#151311] shadow-xs' : 'text-[#A69785] bg-[#26211B] hover:bg-[#332C24]' }}">
                                {{ $cat->name }} ({{ $cat->books_count }})
                            </a>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full md:w-72">
                        <input type="hidden" name="tab" value="books">
                        @if($category)
                            <input type="hidden" name="category" value="{{ $category }}">
                        @endif
                        @if($stockFilter)
                            <input type="hidden" name="stock_filter" value="{{ $stockFilter }}">
                        @endif
                        <i data-lucide="search" class="w-4 h-4 text-[#8E8070] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari judul, penulis, penerbit..." 
                               class="w-full pl-10 pr-4 py-2 bg-[#26211B] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </form>
                </div>

                <!-- Stock Condition Filters (Point 3: Low Stock Alerts) -->
                <div class="pt-3 border-t border-[#2C2620] flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-[#8E8070] font-medium text-[11px] uppercase tracking-wider">Kondisi Stok:</span>
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'category' => $category, 'q' => request('q')]) }}" 
                           class="px-2.5 py-1 rounded-lg font-semibold transition {{ empty($stockFilter) ? 'bg-[#3D352B] text-amber-300 border border-amber-400/40' : 'text-[#8E8070] hover:text-white bg-[#211E1A]' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'stock_filter' => 'out', 'category' => $category, 'q' => request('q')]) }}" 
                           class="px-2.5 py-1 rounded-lg font-semibold transition flex items-center gap-1.5 {{ $stockFilter === 'out' ? 'bg-red-950 text-red-300 border border-red-500' : 'text-red-400/80 bg-red-950/30 hover:bg-red-950/60 border border-red-500/20' }}">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <span>Habis ({{ $outOfStockCount }})</span>
                        </a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'stock_filter' => 'low', 'category' => $category, 'q' => request('q')]) }}" 
                           class="px-2.5 py-1 rounded-lg font-semibold transition flex items-center gap-1.5 {{ $stockFilter === 'low' ? 'bg-amber-950 text-amber-300 border border-amber-500' : 'text-amber-400/80 bg-amber-950/30 hover:bg-amber-950/60 border border-amber-500/20' }}">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span>Menipis ≤ 3 ({{ $lowStockCount }})</span>
                        </a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'stock_filter' => 'safe', 'category' => $category, 'q' => request('q')]) }}" 
                           class="px-2.5 py-1 rounded-lg font-semibold transition flex items-center gap-1.5 {{ $stockFilter === 'safe' ? 'bg-emerald-950 text-emerald-300 border border-emerald-500' : 'text-emerald-400/80 bg-emerald-950/30 hover:bg-emerald-950/60 border border-emerald-500/20' }}">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Aman (> 3)</span>
                        </a>
                    </div>

                    <div class="text-[11px] text-[#A69785]">
                        Total Fisik Buku: <strong class="text-amber-300">{{ number_format($totalStockQty) }}</strong> unit
                    </div>
                </div>
            </div>

            <!-- Books Grid Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Inventaris Master Buku (books) & Stok Terpisah (stocks)</h2>
                <span class="text-xs text-[#8E8070]">Menampilkan {{ $books->total() }} koleksi</span>
            </div>

            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        @php
                            $bStock = $book->stock ? $book->stock->quantity : 0;
                        @endphp
                        <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-5 shadow-lg flex flex-col justify-between hover:border-amber-400/40 transition group">
                            
                            <!-- Top Info & Cover -->
                            <div class="flex gap-4">
                                <div class="w-24 sm:w-28 aspect-[2/3] shrink-0 rounded-xl overflow-hidden shadow-md border border-[#42392E] bg-[#2E2822]">
                                    <img src="{{ $book->cover_url }}" 
                                         alt="{{ $book->title }}" 
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                         class="w-full h-full object-cover group-hover:scale-105 transition">
                                </div>

                                <div class="flex-1 overflow-hidden flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="text-[9px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-amber-400/10 text-amber-300 border border-amber-400/20 truncate">
                                                {{ $book->categoryRelation?->name ?? $book->category }}
                                            </span>
                                            <span class="text-xs text-amber-400 font-bold shrink-0">★ {{ $book->rating }}</span>
                                        </div>

                                        <h3 class="font-serif font-bold text-base text-white truncate mt-1" title="{{ $book->title }}">
                                            {{ $book->title }}
                                        </h3>
                                        <p class="text-xs text-[#9E9081] truncate">{{ $book->author }}</p>
                                        <p class="text-[10px] text-[#786C5E] truncate">Penerbit: {{ $book->publisher ?? 'Readowl Publishing' }}</p>
                                    </div>

                                    <!-- Price & Stock Info (BNSP: stocks table) -->
                                    <div class="pt-2 border-t border-[#332C24] space-y-1.5">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-[#8E8070]">Harga:</span>
                                            <span class="font-bold text-amber-300">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-[#8E8070]">Kondisi Stok:</span>
                                            @if($bStock <= 0)
                                                <span class="font-bold text-[11px] font-mono px-2 py-0.5 rounded-lg bg-red-950/90 text-red-300 border border-red-500/50 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                                                    <span>Habis (0 pcs)</span>
                                                </span>
                                            @elseif($bStock <= 3)
                                                <span class="font-bold text-[11px] font-mono px-2 py-0.5 rounded-lg bg-amber-950/90 text-amber-300 border border-amber-500/50 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                    <span>Menipis ({{ $bStock }} pcs)</span>
                                                </span>
                                            @else
                                                <span class="font-bold text-[11px] font-mono px-2 py-0.5 rounded-lg bg-emerald-950/80 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                    <span>Aman ({{ $bStock }} pcs)</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Action Buttons (Edit & Delete) -->
                            <div class="mt-4 pt-3 border-t border-[#2E2821] flex items-center justify-end gap-2">
                                <button type="button" 
                                        @click="openEdit({{ json_encode($book) }})" 
                                        class="px-3.5 py-1.5 bg-[#2C2620] hover:bg-[#3D352B] text-amber-300 text-xs font-semibold rounded-xl border border-amber-400/30 transition flex items-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </button>

                                <button type="button" 
                                        @click="confirmDelete({{ json_encode($book) }})" 
                                        class="px-3.5 py-1.5 bg-red-950/40 hover:bg-red-900/60 text-red-400 text-xs font-semibold rounded-xl border border-red-500/30 transition flex items-center gap-1.5">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Books -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <div class="py-16 text-center text-[#8E8070] text-xs sm:text-sm bg-[#1D1A16] border border-[#332C24] rounded-3xl">
                    Tidak ada buku yang cocok dengan filter atau kata kunci tersebut.
                </div>
            @endif

        </section>

        <!-- ============================================================== -->
        <!-- TAB 2: CRUD KATEGORI BUKU (BNSP Spec: categories table)         -->
        <!-- ============================================================== -->
        <section x-show="activeTab === 'categories'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                
                <div class="flex flex-col sm:flex-row items-center justify-between pb-5 border-b border-[#2E2820] gap-4 mb-6">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">CRUD Kategori Buku (categories)</h2>
                        <p class="text-xs text-[#8E8070] mt-0.5">Kelola pengelompokan buku dengan relasi referensial 1:N ke tabel master buku.</p>
                    </div>

                    <button type="button" 
                            @click="openCategoryCreate()" 
                            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-4 h-4 stroke-[3]"></i>
                        <span>Tambah Kategori Baru</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#2E2820] text-[#8E8070] uppercase tracking-wider text-[10px]">
                                <th class="pb-3 px-3">ID</th>
                                <th class="pb-3 px-3">Nama Kategori</th>
                                <th class="pb-3 px-3">Slug URL</th>
                                <th class="pb-3 px-3">Jumlah Buku (hasMany)</th>
                                <th class="pb-3 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#26211B]">
                            @forelse($categories as $cat)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-4 px-3 font-mono text-[#D4C7B8]">{{ $cat->id }}</td>
                                    <td class="py-4 px-3 font-semibold text-white">{{ $cat->name }}</td>
                                    <td class="py-4 px-3 font-mono text-amber-300">{{ $cat->slug }}</td>
                                    <td class="py-4 px-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-400/10 text-amber-300 border border-amber-400/20">
                                            {{ $cat->books_count }} buku terhubung
                                        </span>
                                    </td>
                                    <td class="py-4 px-3 text-right space-x-2">
                                        <button type="button" 
                                                @click="openCategoryEdit({{ json_encode($cat) }})" 
                                                class="px-3 py-1 bg-[#2C2620] hover:bg-[#3D352B] text-amber-300 rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-950/40 hover:bg-red-900/60 text-red-400 rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-[#8E8070]">
                                        Belum ada kategori yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

        <!-- ============================================================== -->
        <!-- TAB 3: LOG PESANAN & UPDATE STATUS PENGIRIMAN (BNSP Spec)      -->
        <!-- ============================================================== -->
        <section x-show="activeTab === 'orders'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 pb-4 border-b border-[#2E2820] gap-4">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Log Transaksi & Pengubahan Status Pesanan</h2>
                        <p class="text-xs text-[#8E8070] mt-0.5">Pantau pesanan dari user (order_code, shipping_address, metode pembayaran) dan ubah status pengiriman.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-[#8E8070] block uppercase">Total Omzet Transaksi</span>
                        <span class="font-bold text-emerald-400 text-sm sm:text-base">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-[#2E2820] text-[#8E8070] uppercase tracking-wider text-[10px]">
                                    <th class="pb-3 px-3">Kode Pesanan</th>
                                    <th class="pb-3 px-3">Pembeli & No. Telepon</th>
                                    <th class="pb-3 px-3">Rincian Buku</th>
                                    <th class="pb-3 px-3">Alamat Kirim</th>
                                    <th class="pb-3 px-3">Total Harga</th>
                                    <th class="pb-3 px-3">Ubah Status Pengiriman (BNSP)</th>
                                    <th class="pb-3 px-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#26211B]">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="py-4 px-3 font-mono text-[#D4C7B8] font-bold">
                                            {{ $order->order_code ?? $order->order_number }}
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="font-semibold text-white">{{ $order->user->name ?? 'User' }}</div>
                                            <div class="text-[10px] text-[#8E8070] font-mono">{{ $order->user->phone ?? '-' }}</div>
                                            <div class="text-[10px] text-[#786C5E]">{{ $order->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-3">
                                            @if($order->items->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($order->items as $it)
                                                        <div class="text-[11px] text-white">
                                                            • {{ $it->book->title ?? 'Buku' }} <span class="text-amber-300">({{ $it->quantity }}x)</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="font-semibold text-white max-w-xs truncate">{{ $order->book->title ?? 'Buku' }}</div>
                                                <div class="text-[10px] text-amber-300 font-semibold">{{ $order->quantity }} eksemplar</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-3 max-w-xs">
                                            <div class="text-[11px] text-[#D4C7B8] line-clamp-2" title="{{ $order->shipping_address }}">
                                                {{ $order->shipping_address ?? 'Alamat pengiriman terdaftar' }}
                                            </div>
                                            <span class="text-[9px] uppercase font-bold text-amber-300/80 mt-0.5 block">
                                                Metode: {{ $order->payment_method }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 font-bold text-amber-300">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        
                                        <!-- Form Ubah Status Pengiriman (BNSP PRD Spec + Stock ACC Management) -->
                                        <td class="py-4 px-3">
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-1.5">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="px-2.5 py-1.5 bg-[#26211A] border border-[#3D352A] rounded-lg text-xs font-semibold text-white focus:outline-none focus:border-amber-400">
                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Dikirim)</option>
                                                    <option value="completed" {{ in_array($order->status, ['completed', 'success']) ? 'selected' : '' }}>Success / Selesai (ACC)</option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                                                </select>
                                                <button type="submit" class="px-2.5 py-1.5 bg-[#3D352A] hover:bg-amber-500 hover:text-[#151311] text-amber-300 text-xs font-bold rounded-lg transition" title="Simpan Status & Update Stok">
                                                    ✓
                                                </button>
                                            </form>

                                            <!-- Status Pemotongan Stok -->
                                            @if($order->stock_deducted)
                                                <span class="inline-flex items-center gap-1 text-[9px] font-semibold text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-1.5 py-0.5 rounded mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                    <span>Stok Telah Dipotong</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[9px] font-semibold text-amber-300 bg-amber-950/60 border border-amber-500/30 px-1.5 py-0.5 rounded mt-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                                    <span>Belum Di-ACC (Stok Utuh)</span>
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-3 text-[#8E8070] text-[10px]">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Orders -->
                    <div class="mt-6 pt-4 border-t border-[#2E2820]">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="py-12 text-center text-[#8E8070] text-xs">
                        Belum ada riwayat transaksi pesanan buku.
                    </div>
                @endif
            </div>

        </section>

        <!-- ============================================================== -->
        <!-- TAB 4: DATA PENGGUNA & MEMBER                                  -->
        <!-- ============================================================== -->
        <section x-show="activeTab === 'users'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                
                <div class="flex flex-col sm:flex-row items-center justify-between pb-5 border-b border-[#2E2820] gap-4 mb-6">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Monitoring Data Pengguna Terdaftar</h2>
                        <p class="text-xs text-[#8E8070] mt-0.5">Daftar pengguna terdaftar, nomor WhatsApp/telepon, peran akun, dan total transaksi pembelian.</p>
                    </div>

                    <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full sm:w-72">
                        <input type="hidden" name="tab" value="users">
                        <i data-lucide="search" class="w-4 h-4 text-[#8E8070] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari nama, email, nomor HP..." 
                               class="w-full pl-10 pr-4 py-2 bg-[#28231E] border border-[#3D352C] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#2E2820] text-[#8E8070] uppercase tracking-wider text-[10px]">
                                <th class="pb-3 px-3">Pengguna</th>
                                <th class="pb-3 px-3">Nomor Telepon/WA</th>
                                <th class="pb-3 px-3">Peran (Role)</th>
                                <th class="pb-3 px-3">Buku Dibeli</th>
                                <th class="pb-3 px-3">Total Belanja</th>
                                <th class="pb-3 px-3">Tanggal Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#26211B]">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-4 px-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-[#383128] text-amber-300 font-bold flex items-center justify-center text-xs uppercase">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <span class="font-semibold text-white block">{{ $user->name }}</span>
                                                <span class="text-[10px] text-[#8E8070]">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-3 font-mono text-[#D4C7B8]">
                                        {{ $user->phone ?? '-' }}
                                    </td>
                                    <td class="py-4 px-3">
                                        @if($user->role === 'admin')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-950 text-amber-300 border border-amber-500/30">
                                                ADMINISTRATOR
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-950 text-sky-300 border border-sky-500/30">
                                                PEMBACA
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-3">
                                        <span class="font-bold text-white">{{ $user->orders_count }}</span>
                                        <span class="text-[10px] text-[#8E8070]">pesanan</span>
                                    </td>
                                    <td class="py-4 px-3 font-semibold text-emerald-400">
                                        Rp {{ number_format($user->orders_sum_total_price ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-3 text-[#8E8070]">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#8E8070]">
                                        Tidak ada pengguna yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Users -->
                <div class="mt-6 pt-4 border-t border-[#2E2820]">
                    {{ $users->links() }}
                </div>
            </div>

        </section>

        <!-- ============================================================== -->
        <!-- TAB 5: PESAN MASUK FORM KONTAK (BNSP Requirement)               -->
        <!-- ============================================================== -->
        <section x-show="activeTab === 'contacts'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#2E2820]">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Kotak Masuk Pesan Pengunjung (contacts)</h2>
                        <p class="text-xs text-[#8E8070] mt-0.5">Pesan, pertanyaan, dan aduan yang dikirim pengunjung melalui form Kontak Publik.</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 bg-amber-400/10 text-amber-300 rounded-full text-xs font-semibold border border-amber-400/20">
                            {{ $unreadContacts }} Belum Dibaca
                        </span>
                    </div>
                </div>

                @if($contacts->count() > 0)
                    <div class="space-y-4">
                        @foreach($contacts as $msg)
                            <div class="p-5 rounded-2xl border transition {{ $msg->is_read ? 'bg-[#1C1915] border-[#2E2820] opacity-80' : 'bg-[#26211B] border-amber-500/40 shadow-md' }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#332C23]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-400/20 text-amber-300 font-bold flex items-center justify-center text-xs">
                                            {{ substr($msg->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-white text-sm">{{ $msg->name }}</span>
                                            <span class="text-xs text-[#8E8070] ml-2 font-mono">{{ $msg->email }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="text-[#786C5E]">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                        @if(!$msg->is_read)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-400 text-[#151311]">
                                                BARU
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-[#D4C7B8] py-3 leading-relaxed">
                                    {{ $msg->message }}
                                </p>

                                <div class="pt-2 flex items-center justify-end gap-3 text-xs">
                                    @if(!$msg->is_read)
                                        <form action="{{ route('admin.contacts.read', $msg->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-[#332C24] hover:bg-emerald-800 text-emerald-300 rounded-lg transition font-semibold">
                                                Tandai Sudah Dibaca
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-950/40 hover:bg-red-900/60 text-red-400 rounded-lg transition">
                                            Hapus Pesan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Contacts -->
                    <div class="mt-6 pt-4 border-t border-[#2E2820]">
                        {{ $contacts->links() }}
                    </div>
                @else
                    <div class="py-12 text-center text-[#8E8070] text-xs">
                        Belum ada pesan aduan atau pertanyaan dari pengunjung.
                    </div>
                @endif
            </div>

        </section>

    </main>

    <!-- ============================================================== -->
    <!-- MODAL: TAMBAH BUKU (CREATE BOOK + STOK)                       -->
    <!-- ============================================================== -->
    <div x-show="createModalOpen" 
         x-cloak 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="createModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D352A] rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto"
             @click.away="createModalOpen = false">
            
            <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-white">Tambah Koleksi Buku Baru</h3>
                    <p class="text-xs text-[#A69785] mt-1">Data master buku & pengelolaan kuantitas stok terpisah (stocks).</p>
                </div>
                <button @click="createModalOpen = false" class="p-2 text-[#8E8070] hover:text-white rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku (Harus Unik) *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: The Psychology of Money" class="w-full px-4 py-2.5 bg-[#26211A] border @error('title') border-red-500 @else border-[#3D352A] @enderror rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    <span class="text-[10px] text-[#8E8070] mt-1 block">Judul buku tidak boleh sama dengan buku yang sudah ada di katalog.</span>
                    @error('title')
                        <span class="text-[10px] text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Penulis (Author) *</label>
                        <input type="text" name="author" required placeholder="Contoh: Morgan Housel" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Penerbit (Publisher)</label>
                        <input type="text" name="publisher" placeholder="Contoh: Gramedia Pustaka Utama" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori Buku *</label>
                        <select name="category_id" required class="w-full px-3 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rupiah) *</label>
                        <input type="number" name="price" required placeholder="Contoh: 95000" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-emerald-400 mb-1">Jumlah Stok Fisik *</label>
                        <input type="number" name="stock_quantity" required value="20" min="0" class="w-full px-4 py-2.5 bg-[#26211A] border border-emerald-500/40 rounded-xl text-xs font-bold text-emerald-300 focus:outline-none focus:border-emerald-400">
                    </div>
                </div>

                <!-- Cover Image (Upload or URL) with Live Preview -->
                <div class="bg-[#26211A] p-3.5 rounded-2xl border border-[#3D352A] space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-amber-300 mb-1">Upload Berkas Sampul (Storage)</label>
                            <input type="file" 
                                   name="cover_file" 
                                   accept="image/*" 
                                   @change="const f = $event.target.files[0]; if (f) { createCoverPreview = URL.createObjectURL(f); }"
                                   class="w-full text-xs text-[#A69785] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#3D352A] file:text-amber-300 hover:file:bg-[#4D4336] cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-amber-300 mb-1">Atau Gunakan URL Gambar</label>
                            <input type="url" 
                                   name="cover_url" 
                                   @input="createCoverPreview = $event.target.value"
                                   placeholder="https://images.unsplash.com/..." 
                                   class="w-full px-3 py-1.5 bg-[#1C1915] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                        </div>
                    </div>
                    <div x-show="createCoverPreview" class="pt-2 border-t border-[#332C23] flex items-center gap-3">
                        <img :src="createCoverPreview" class="w-12 h-16 object-cover rounded-lg border border-[#4D4336] bg-[#151311] shadow-xs" alt="Preview Sampul">
                        <div>
                            <span class="text-xs font-bold text-amber-300 block">Preview Sampul Terpilih</span>
                            <span class="text-[10px] text-[#8E8070]">Gambar ini akan ditampilkan pada etalase katalog & rak 3D.</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Rating (1-5)</label>
                        <input type="number" step="0.1" name="rating" value="4.8" min="1" max="5" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Jumlah Halaman</label>
                        <input type="number" name="pages" value="250" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Tahun Terbit</label>
                        <input type="number" name="published_year" value="2023" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Deskripsi / Sinopsis Buku (description)</label>
                    <textarea name="description" rows="3" placeholder="Tuliskan ulasan ringkas mengenai isi atau alur cerita buku..." class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#332C23]">
                    <button type="button" @click="createModalOpen = false" class="px-5 py-2.5 text-xs font-semibold text-[#A69785] hover:text-white transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-lg transition">
                        Simpan Buku & Stok
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: EDIT BUKU (UPDATE BOOK + STOK)                         -->
    <!-- ============================================================== -->
    <div x-show="editModalOpen" 
         x-cloak 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="editModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D352A] rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto"
             @click.away="editModalOpen = false">
            
            <template x-if="selectedBook">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                        <div>
                            <h3 class="font-serif text-2xl font-bold text-white">Edit Informasi Buku & Stok</h3>
                            <p class="text-xs text-[#A69785] mt-1" x-text="'Perbarui rincian untuk \'' + selectedBook.title + '\''"></p>
                        </div>
                        <button @click="editModalOpen = false" class="p-2 text-[#8E8070] hover:text-white rounded-full">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('/admin/books') }}/' + selectedBook.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku (Harus Unik) *</label>
                            <input type="text" name="title" x-model="selectedBook.title" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            <span class="text-[10px] text-[#8E8070] mt-1 block">Judul buku tidak boleh sama dengan buku lain yang sudah ada di katalog.</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Penulis *</label>
                                <input type="text" name="author" x-model="selectedBook.author" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Penerbit (Publisher)</label>
                                <input type="text" name="publisher" x-model="selectedBook.publisher" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori Buku *</label>
                                <select name="category_id" x-model="selectedBook.category_id" required class="w-full px-3 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rupiah) *</label>
                                <input type="number" name="price" x-model="selectedBook.price" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-emerald-400 mb-1">Jumlah Stok Fisik *</label>
                                <input type="number" name="stock_quantity" x-model="selectedBook.stock_quantity" required min="0" class="w-full px-4 py-2.5 bg-[#26211A] border border-emerald-500/40 rounded-xl text-xs font-bold text-emerald-300 focus:outline-none focus:border-emerald-400">
                            </div>
                        </div>

                        <!-- Cover Image (Upload or URL) with Live Preview -->
                        <div class="bg-[#26211A] p-3.5 rounded-2xl border border-[#3D352A] space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-amber-300 mb-1">Ganti Berkas Sampul</label>
                                    <input type="file" 
                                           name="cover_file" 
                                           accept="image/*" 
                                           @change="const f = $event.target.files[0]; if (f) { editCoverPreview = URL.createObjectURL(f); }"
                                           class="w-full text-xs text-[#A69785] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#3D352A] file:text-amber-300 hover:file:bg-[#4D4336] cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-amber-300 mb-1">Atau Ubah URL Gambar</label>
                                    <input type="url" 
                                           name="cover_url" 
                                           x-model="selectedBook.cover_url" 
                                           @input="editCoverPreview = $event.target.value"
                                           class="w-full px-3 py-1.5 bg-[#1C1915] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                                </div>
                            </div>
                            <div x-show="editCoverPreview" class="pt-2 border-t border-[#332C23] flex items-center gap-3">
                                <img :src="editCoverPreview" class="w-12 h-16 object-cover rounded-lg border border-[#4D4336] bg-[#151311] shadow-xs" alt="Preview Sampul">
                                <div>
                                    <span class="text-xs font-bold text-amber-300 block">Preview Sampul Buku</span>
                                    <span class="text-[10px] text-[#8E8070]">Tampilan terkini sampul buku yang tersimpan.</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Rating</label>
                                <input type="number" step="0.1" name="rating" x-model="selectedBook.rating" min="1" max="5" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Halaman</label>
                                <input type="number" name="pages" x-model="selectedBook.pages" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Tahun Terbit</label>
                                <input type="number" name="published_year" x-model="selectedBook.published_year" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Deskripsi / Sinopsis (description)</label>
                            <textarea name="description" x-model="selectedBook.description" rows="3" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400"></textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#332C23]">
                            <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 text-xs font-semibold text-[#A69785] hover:text-white transition">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-lg transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </template>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: CRUD KATEGORI (CREATE / EDIT CATEGORY)                  -->
    <!-- ============================================================== -->
    <div x-show="categoryModalOpen" 
         x-cloak 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="categoryModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D352A] rounded-3xl max-w-md w-full p-6 shadow-2xl relative"
             @click.away="categoryModalOpen = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-[#332C23] mb-4">
                <h3 class="font-serif text-xl font-bold text-white" x-text="selectedCategory ? 'Edit Kategori Buku' : 'Tambah Kategori Buku'"></h3>
                <button @click="categoryModalOpen = false" class="text-[#8E8070] hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="selectedCategory ? '{{ url('/admin/categories') }}/' + selectedCategory.id : '{{ route('admin.categories.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="selectedCategory">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Kategori *</label>
                    <input type="text" 
                           name="name" 
                           :value="selectedCategory ? selectedCategory.name : ''" 
                           required 
                           placeholder="Contoh: Fiksi Ilmiah, Psikologi" 
                           class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                    <span class="text-[10px] text-[#8E8070] mt-1 block">Slug URL akan terbuat otomatis dari nama kategori.</span>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-[#332C23]">
                    <button type="button" @click="categoryModalOpen = false" class="px-4 py-2 text-xs text-[#A69785] hover:text-white">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-amber-400 hover:bg-amber-500 text-[#151311] font-bold text-xs rounded-xl shadow-md transition">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL: HAPUS BUKU (DELETE CONFIRMATION)                        -->
    <!-- ============================================================== -->
    <div x-show="deleteModalOpen" 
         x-cloak 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         @keydown.escape.window="deleteModalOpen = false">
        
        <div class="bg-[#1C1915] border border-red-500/30 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center"
             @click.away="deleteModalOpen = false">
            
            <div class="w-14 h-14 rounded-full bg-red-950/70 border border-red-500/40 mx-auto flex items-center justify-center text-red-400 mb-4">
                <i data-lucide="alert-triangle" class="w-7 h-7"></i>
            </div>

            <h3 class="font-serif text-xl font-bold text-white">Hapus Buku Ini?</h3>
            <p class="text-xs text-[#8E8070] mt-2 mb-6" x-text="'Buku \'' + (bookToDelete ? bookToDelete.title : '') + '\' dan data stok inventarisnya akan dihapus permanen.'"></p>

            <template x-if="bookToDelete">
                <form :action="'{{ url('/admin/books') }}/' + bookToDelete.id" method="POST" class="flex gap-3 justify-center">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteModalOpen = false" class="px-5 py-2.5 text-xs font-semibold text-[#A69785] bg-[#26211C] hover:bg-[#332C24] rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Ya, Hapus Buku
                    </button>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
