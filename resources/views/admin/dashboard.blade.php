@extends('layouts.base')

@section('title', 'Studio Kurator & Manajemen Buku - Admin Readowl')

@section('content')
<div class="min-h-screen bg-[#151311] text-[#EBE5DB]" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    selectedBook: null,
    deleteModalOpen: false,
    bookToDelete: null,
    openEdit(book) {
        this.selectedBook = JSON.parse(JSON.stringify(book));
        this.editModalOpen = true;
    },
    confirmDelete(book) {
        this.bookToDelete = book;
        this.deleteModalOpen = true;
    }
}">

    <!-- ADMIN TOP NAVIGATION -->
    <header class="border-b border-[#2C2621] bg-[#1A1815]/80 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center text-[#151311] font-bold shadow-md">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-serif text-xl font-bold tracking-tight text-white block">READOWL STUDIO</span>
                        <span class="text-[10px] tracking-widest uppercase text-amber-400 font-semibold">Panel Administrator Kurator</span>
                    </div>
                </a>
            </div>

            <!-- Actions Right -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('admin.orders') }}" class="px-3.5 py-2 text-xs font-semibold text-[#D4C7B8] hover:text-white bg-[#26211C] hover:bg-[#332D26] rounded-xl border border-[#3D352D] transition flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-amber-400"></i>
                    <span class="hidden sm:inline">Semua Pesanan</span>
                </a>

                <a href="{{ route('catalog') }}" class="px-3.5 py-2 text-xs font-semibold text-[#D4C7B8] hover:text-white bg-[#26211C] hover:bg-[#332D26] rounded-xl border border-[#3D352D] transition flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i>
                    <span class="hidden sm:inline">Pratinjau Toko</span>
                </a>

                <button @click="createModalOpen = true" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs sm:text-sm rounded-xl shadow-lg transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4 stroke-[3]"></i>
                    <span>Tambah Buku</span>
                </button>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar Admin" class="p-2 text-[#9E9081] hover:text-red-400 hover:bg-[#26211C] rounded-xl transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
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

        <!-- HERO STATS (Artistic Plaque Cards) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10">
            <!-- Card 1 -->
            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Total Koleksi</span>
                    <i data-lucide="book" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalBooks }}</div>
                <span class="text-[10px] text-amber-400/90 mt-1 block">Buku terdaftar di MySQL</span>
            </div>

            <!-- Card 2 -->
            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Pendapatan Buku</span>
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[10px] text-emerald-400/90 mt-1 block">{{ $totalOrders }} transaksi sukses</span>
            </div>

            <!-- Card 3 -->
            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Pembaca Terdaftar</span>
                    <i data-lucide="users" class="w-4 h-4 text-sky-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalUsers }}</div>
                <span class="text-[10px] text-sky-400/90 mt-1 block">Akun anggota aktif</span>
            </div>

            <!-- Card 4 -->
            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Database Terkoneksi</span>
                    <i data-lucide="database" class="w-4 h-4 text-amber-300"></i>
                </div>
                <div class="font-mono text-xl font-bold text-amber-300 tracking-tight">perpus_db</div>
                <span class="text-[10px] text-[#A69785] mt-1 block">XAMPP MySQL Port 3306</span>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="bg-[#1D1A16] border border-[#332C24] rounded-2xl p-4 sm:p-5 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Shelf Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($shelf) ? 'bg-amber-500 text-[#151311]' : 'text-[#A69785] hover:text-white bg-[#28231E]' }}">
                    Semua Rak
                </a>
                <a href="{{ route('admin.dashboard', ['shelf' => 'currently_reading', 'q' => request('q')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $shelf === 'currently_reading' ? 'bg-amber-500 text-[#151311]' : 'text-[#A69785] hover:text-white bg-[#28231E]' }}">
                    Currently reading
                </a>
                <a href="{{ route('admin.dashboard', ['shelf' => 'next_up', 'q' => request('q')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $shelf === 'next_up' ? 'bg-amber-500 text-[#151311]' : 'text-[#A69785] hover:text-white bg-[#28231E]' }}">
                    Next up
                </a>
                <a href="{{ route('admin.dashboard', ['shelf' => 'finished', 'q' => request('q')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $shelf === 'finished' ? 'bg-amber-500 text-[#151311]' : 'text-[#A69785] hover:text-white bg-[#28231E]' }}">
                    Finished
                </a>
            </div>

            <!-- Search Input -->
            <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full md:w-72">
                @if($shelf)
                    <input type="hidden" name="shelf" value="{{ $shelf }}">
                @endif
                <i data-lucide="search" class="w-4 h-4 text-[#8E8070] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari judul atau penulis..." 
                       class="w-full pl-10 pr-4 py-2 bg-[#28231E] border border-[#3D352C] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400 transition">
            </form>
        </div>

        <!-- BOOKS CURATED GALLERY GRID (CRUD CARDS) -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Daftar Buku Kurasi Perpustakaan</h2>
                <span class="text-xs text-[#8E8070]">Menampilkan {{ $books->total() }} koleksi</span>
            </div>

            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-5 shadow-lg flex flex-col justify-between hover:border-amber-400/40 transition group">
                            
                            <!-- Top Info & Cover -->
                            <div class="flex gap-4">
                                <div class="w-24 sm:w-28 aspect-[2/3] shrink-0 rounded-xl overflow-hidden shadow-md border border-[#42392E] bg-[#2E2822]">
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                </div>

                                <div class="flex-1 overflow-hidden flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="text-[9px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-amber-400/10 text-amber-300 border border-amber-400/20">
                                                {{ $book->category }}
                                            </span>
                                            <span class="text-xs text-amber-400 font-bold">★ {{ $book->rating }}</span>
                                        </div>

                                        <h3 class="font-serif font-bold text-base text-white truncate mt-1" title="{{ $book->title }}">
                                            {{ $book->title }}
                                        </h3>
                                        <p class="text-xs text-[#9E9081] truncate">{{ $book->author }}</p>
                                    </div>

                                    <!-- Shelf & Price info -->
                                    <div class="pt-2 border-t border-[#332C24] space-y-1">
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="text-[#8E8070]">Rak:</span>
                                            <span class="font-medium text-[#D4C7B8]">{{ ucfirst(str_replace('_', ' ', $book->shelf_section)) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-[#8E8070]">Harga:</span>
                                            <span class="font-bold text-amber-300">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
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
                                        class="px-3 py-1.5 bg-[#2C1F1F] hover:bg-red-950 text-red-400 text-xs font-semibold rounded-xl border border-red-500/30 transition flex items-center gap-1.5">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <div class="bg-[#1C1915] border border-[#332B22] rounded-3xl p-12 text-center">
                    <p class="text-[#8E8070] text-sm">Tidak ada buku yang cocok dengan pencarian.</p>
                </div>
            @endif
        </div>

    </main>

    <!-- MODAL 1: TAMBAH BUKU (CREATE) -->
    <div x-show="createModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="createModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D3429] rounded-[32px] max-w-2xl w-full p-6 sm:p-8 shadow-2xl overflow-y-auto max-h-[90vh]"
             @click.away="createModalOpen = false">
            
            <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-white">Tambah Koleksi Buku Baru</h3>
                    <p class="text-xs text-[#8E8070] mt-0.5">Lengkapi formulir untuk memasukkan buku ke rak perpustakaan.</p>
                </div>
                <button @click="createModalOpen = false" class="p-2 text-[#8E8070] hover:text-white rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.books.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku *</label>
                        <input type="text" name="title" required placeholder="Contoh: The Lord of the Rings" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Penulis *</label>
                        <input type="text" name="author" required placeholder="Contoh: J.R.R. Tolkien" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori *</label>
                        <input type="text" name="category" required placeholder="Contoh: Fantasy / Design" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Bagian Rak *</label>
                        <select name="shelf_section" required class="w-full px-3 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            <option value="next_up">Next up (Tengah)</option>
                            <option value="currently_reading">Currently reading (Atas)</option>
                            <option value="finished">Finished (Bawah)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" placeholder="75000" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">URL Sampul Buku (Image URL) *</label>
                    <input type="url" name="cover_url" required placeholder="https://images.unsplash.com/... atau URL gambar buku" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Rating (1-5)</label>
                        <input type="number" step="0.1" name="rating" min="1" max="5" value="4.8" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Jumlah Halaman</label>
                        <input type="number" name="pages" min="1" value="320" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Tahun Terbit</label>
                        <input type="number" name="published_year" value="2024" class="w-full px-3 py-2 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Sinopsis</label>
                    <textarea name="synopsis" rows="3" placeholder="Tuliskan deskripsi ringkas tentang buku ini..." class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#332C23]">
                    <button type="button" @click="createModalOpen = false" class="px-5 py-2.5 text-xs font-semibold text-[#A69785] hover:text-white transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-lg transition">
                        Simpan Buku Baru
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL 2: EDIT BUKU (UPDATE) -->
    <div x-show="editModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="editModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D3429] rounded-[32px] max-w-2xl w-full p-6 sm:p-8 shadow-2xl overflow-y-auto max-h-[90vh]"
             @click.away="editModalOpen = false">
            
            <template x-if="selectedBook">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                        <div>
                            <h3 class="font-serif text-2xl font-bold text-white">Edit Informasi Buku</h3>
                            <p class="text-xs text-[#8E8070] mt-0.5" x-text="'Memperbarui: ' + selectedBook.title"></p>
                        </div>
                        <button @click="editModalOpen = false" class="p-2 text-[#8E8070] hover:text-white rounded-full">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('/admin/books') }}/' + selectedBook.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku *</label>
                                <input type="text" name="title" x-model="selectedBook.title" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Penulis *</label>
                                <input type="text" name="author" x-model="selectedBook.author" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori *</label>
                                <input type="text" name="category" x-model="selectedBook.category" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Bagian Rak *</label>
                                <select name="shelf_section" x-model="selectedBook.shelf_section" required class="w-full px-3 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                                    <option value="currently_reading">Currently reading (Atas)</option>
                                    <option value="next_up">Next up (Tengah)</option>
                                    <option value="finished">Finished (Bawah)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rp) *</label>
                                <input type="number" name="price" x-model="selectedBook.price" required min="0" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">URL Sampul Buku *</label>
                            <input type="url" name="cover_url" x-model="selectedBook.cover_url" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
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
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Sinopsis</label>
                            <textarea name="synopsis" x-model="selectedBook.synopsis" rows="3" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400"></textarea>
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

    <!-- MODAL 3: DELETE CONFIRMATION -->
    <div x-show="deleteModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         @keydown.escape.window="deleteModalOpen = false">
        
        <div class="bg-[#1C1915] border border-red-500/30 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center"
             @click.away="deleteModalOpen = false">
            
            <div class="w-14 h-14 rounded-full bg-red-950/70 border border-red-500/40 mx-auto flex items-center justify-center text-red-400 mb-4">
                <i data-lucide="alert-triangle" class="w-7 h-7"></i>
            </div>

            <h3 class="font-serif text-xl font-bold text-white">Hapus Buku Ini?</h3>
            <p class="text-xs text-[#8E8070] mt-2 mb-6" x-text="'Buku \'' + (bookToDelete ? bookToDelete.title : '') + '\' akan dihapus secara permanen dari perpustakaan.'"></p>

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
