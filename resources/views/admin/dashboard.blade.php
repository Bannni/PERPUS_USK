@extends('layouts.base')

@section('title', 'Admin Dashboard - Readowl Studio')

@section('content')
<div class="min-h-screen bg-[#151311] text-[#EBE5DB]" x-data="{
    activeTab: '{{ $activeTab ?? 'books' }}',
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
    <header class="border-b border-[#2C2621] bg-[#1A1815]/90 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center text-[#151311] font-bold shadow-md">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="font-serif text-xl font-bold tracking-tight text-white block">READOWL STUDIO</span>
                    <span class="text-[10px] tracking-widest uppercase text-amber-400 font-semibold">Dashboard Administrator</span>
                </div>
            </div>

            <!-- Tab Switchers Center/Right (All inside dashboard) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <nav class="flex items-center bg-[#231E19] border border-[#3A3228] p-1 rounded-2xl">
                    <button type="button" 
                            @click="activeTab = 'books'" 
                            :class="activeTab === 'books' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5">
                        <i data-lucide="book" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Koleksi Buku</span>
                        <span class="text-[10px] opacity-80">({{ $totalBooks }})</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'users'" 
                            :class="activeTab === 'users' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Data Pengguna</span>
                        <span class="text-[10px] opacity-80">({{ $totalUsers }})</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'orders'" 
                            :class="activeTab === 'orders' ? 'bg-amber-400 text-[#151311] font-bold shadow-sm' : 'text-[#A69785] hover:text-white'"
                            class="px-3.5 py-1.5 rounded-xl text-xs transition flex items-center gap-1.5">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Log Transaksi</span>
                        <span class="text-[10px] opacity-80">({{ $totalOrders }})</span>
                    </button>
                </nav>

                <button @click="createModalOpen = true; activeTab = 'books'" class="px-3.5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-[#151311] font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4 stroke-[3]"></i>
                    <span class="hidden md:inline">Tambah Buku</span>
                </button>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar dari Panel Admin" class="p-2 text-[#9E9081] hover:text-red-400 hover:bg-[#26211C] rounded-xl transition">
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

        <!-- 4 TOP OVERVIEW STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Card 1 -->
            <div @click="activeTab = 'books'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-amber-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Total Koleksi</span>
                    <i data-lucide="book" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalBooks }}</div>
                <span class="text-[10px] text-amber-400/90 mt-1 block">Buku terdaftar di katalog</span>
            </div>

            <!-- Card 2 -->
            <div @click="activeTab = 'orders'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-emerald-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Total Pendapatan</span>
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight truncate">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[10px] text-emerald-400/90 mt-1 block">{{ $totalOrders }} transaksi sukses</span>
            </div>

            <!-- Card 3 -->
            <div @click="activeTab = 'users'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-sky-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Pengguna Terdaftar</span>
                    <i data-lucide="users" class="w-4 h-4 text-sky-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $totalUsers }}</div>
                <span class="text-[10px] text-sky-400/90 mt-1 block">{{ $totalMembers }} anggota • {{ $totalAdmins }} admin</span>
            </div>

            <!-- Card 4 -->
            <div @click="activeTab = 'books'" class="cursor-pointer bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] hover:border-amber-400/50 rounded-3xl p-5 shadow-lg relative overflow-hidden transition group">
                <div class="flex items-center justify-between text-[#8E8070] mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider">Genre & Kategori</span>
                    <i data-lucide="tags" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="font-serif text-3xl font-bold text-white tracking-tight">{{ $categories->count() }}</div>
                <span class="text-[10px] text-amber-400/90 mt-1 block">Klasifikasi genre aktif</span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: MANAJEMEN & KURASI BUKU (CRUD)       -->
        <!-- ========================================== -->
        <section x-show="activeTab === 'books'">
            
            <!-- Filter & Search Bar for Books -->
            <div class="bg-[#1D1A16] border border-[#332C24] rounded-2xl p-4 sm:p-5 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Category Filter Pills -->
                <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.dashboard', ['tab' => 'books']) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($category) ? 'bg-amber-400 text-[#151311] shadow-xs' : 'text-[#A69785] bg-[#26211B] hover:bg-[#332C24]' }}">
                        Semua Genre
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('admin.dashboard', ['tab' => 'books', 'category' => $cat, 'q' => request('q')]) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $category === $cat ? 'bg-amber-400 text-[#151311] shadow-xs' : 'text-[#A69785] bg-[#26211B] hover:bg-[#332C24]' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Input -->
                <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full md:w-72">
                    <input type="hidden" name="tab" value="books">
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    <i data-lucide="search" class="w-4 h-4 text-[#8E8070] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Cari judul atau penulis..." 
                           class="w-full pl-10 pr-4 py-2 bg-[#26211B] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                </form>
            </div>

            <!-- Books Grid Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Daftar Buku Kurasi</h2>
                <span class="text-xs text-[#8E8070]">Menampilkan {{ $books->total() }} koleksi</span>
            </div>

            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
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
                                                {{ $book->category }}
                                            </span>
                                            <span class="text-xs text-amber-400 font-bold shrink-0">★ {{ $book->rating }}</span>
                                        </div>

                                        <h3 class="font-serif font-bold text-base text-white truncate mt-1" title="{{ $book->title }}">
                                            {{ $book->title }}
                                        </h3>
                                        <p class="text-xs text-[#9E9081] truncate">{{ $book->author }}</p>
                                    </div>

                                    <!-- Price info -->
                                    <div class="pt-2 border-t border-[#332C24] space-y-1">
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

        <!-- ========================================== -->
        <!-- TAB 2: DATA PENGGUNA & MEMBER               -->
        <!-- ========================================== -->
        <section x-show="activeTab === 'users'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                
                <div class="flex flex-col sm:flex-row items-center justify-between pb-5 border-b border-[#2E2820] gap-4 mb-6">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Monitoring Data Pengguna</h2>
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
                                        <span class="text-[10px] text-[#8E8070]">buku</span>
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

        <!-- ========================================== -->
        <!-- TAB 3: LOG TRANSAKSI & PESANAN             -->
        <!-- ========================================== -->
        <section x-show="activeTab === 'orders'" style="display: none;">
            
            <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#2E2820]">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-white">Log Pesanan & Transaksi</h2>
                        <p class="text-xs text-[#8E8070] mt-0.5">Catatan seluruh riwayat pesanan buku oleh para pembaca.</p>
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
                                    <th class="pb-3 px-3">No. Pesanan</th>
                                    <th class="pb-3 px-3">Pembaca</th>
                                    <th class="pb-3 px-3">Buku</th>
                                    <th class="pb-3 px-3">Metode</th>
                                    <th class="pb-3 px-3">Total Harga</th>
                                    <th class="pb-3 px-3">Status</th>
                                    <th class="pb-3 px-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#26211B]">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="py-4 px-3 font-mono text-[#D4C7B8]">{{ $order->order_number }}</td>
                                        <td class="py-4 px-3">
                                            <div class="font-semibold text-white">{{ $order->user->name }}</div>
                                            <div class="text-[10px] text-[#8E8070]">{{ $order->user->email }} • {{ $order->user->phone ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-12 rounded overflow-hidden shadow-xs shrink-0 bg-[#28231E]">
                                                    <img src="{{ $order->book->cover_url }}" 
                                                         alt="{{ $order->book->title }}" 
                                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                                         class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-white max-w-xs truncate">{{ $order->book->title }}</div>
                                                    <div class="text-[10px] text-[#8E8070]">{{ $order->book->author }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-3 text-[#D4C7B8]">{{ $order->payment_method }}</td>
                                        <td class="py-4 px-3 font-bold text-amber-300">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-3">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-950 text-emerald-400 border border-emerald-500/30">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-[#8E8070]">{{ $order->created_at->format('d M Y, H:i') }}</td>
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

    </main>

    <!-- ========================================== -->
    <!-- MODAL 1: TAMBAH BUKU (CREATE BOOK)         -->
    <!-- ========================================== -->
    <div x-show="createModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="createModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D352A] rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto"
             @click.away="createModalOpen = false">
            
            <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-white">Tambah Koleksi Buku Baru</h3>
                    <p class="text-xs text-[#A69785] mt-1">Masukkan data lengkap buku baru ke dalam perpustakaan Readowl.</p>
                </div>
                <button @click="createModalOpen = false" class="p-2 text-[#8E8070] hover:text-white rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku *</label>
                    <input type="text" name="title" required placeholder="Contoh: The Psychology of Money" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Penulis *</label>
                    <input type="text" name="author" required placeholder="Contoh: Morgan Housel" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori / Genre *</label>
                        <input type="text" name="category" required placeholder="Contoh: Finansial, Klasik, Fantasi" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rupiah) *</label>
                        <input type="number" name="price" required placeholder="Contoh: 95000" class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <!-- Cover Image (Upload or URL) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#26211A] p-3 rounded-2xl border border-[#3D352A]">
                    <div>
                        <label class="block text-xs font-semibold text-amber-300 mb-1">Upload Berkas Sampul</label>
                        <input type="file" name="cover_file" accept="image/*" class="w-full text-xs text-[#A69785] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#3D352A] file:text-amber-300 hover:file:bg-[#4D4336] cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-amber-300 mb-1">Atau Gunakan URL Gambar</label>
                        <input type="url" name="cover_url" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-1.5 bg-[#1C1915] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
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
                    <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Sinopsis Buku</label>
                    <textarea name="synopsis" rows="3" placeholder="Tuliskan ulasan singkat mengenai alur cerita atau isi buku..." class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400"></textarea>
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

    <!-- ========================================== -->
    <!-- MODAL 2: EDIT BUKU (UPDATE BOOK)           -->
    <!-- ========================================== -->
    <div x-show="editModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @keydown.escape.window="editModalOpen = false">
        
        <div class="bg-[#1C1915] border border-[#3D352A] rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto"
             @click.away="editModalOpen = false">
            
            <template x-if="selectedBook">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[#332C23] mb-6">
                        <div>
                            <h3 class="font-serif text-2xl font-bold text-white">Edit Informasi Buku</h3>
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
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Judul Buku *</label>
                            <input type="text" name="title" x-model="selectedBook.title" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Nama Penulis *</label>
                            <input type="text" name="author" x-model="selectedBook.author" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Kategori / Genre *</label>
                                <input type="text" name="category" x-model="selectedBook.category" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#D4C7B8] mb-1">Harga (Rupiah) *</label>
                                <input type="number" name="price" x-model="selectedBook.price" required class="w-full px-4 py-2.5 bg-[#26211A] border border-[#3D352A] rounded-xl text-xs text-white focus:outline-none focus:border-amber-400">
                            </div>
                        </div>

                        <!-- Cover Image (Upload or URL) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#26211A] p-3 rounded-2xl border border-[#3D352A]">
                            <div>
                                <label class="block text-xs font-semibold text-amber-300 mb-1">Ganti Berkas Sampul</label>
                                <input type="file" name="cover_file" accept="image/*" class="w-full text-xs text-[#A69785] file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#3D352A] file:text-amber-300 hover:file:bg-[#4D4336] cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-amber-300 mb-1">Atau Ubah URL Gambar</label>
                                <input type="url" name="cover_url" x-model="selectedBook.cover_url" class="w-full px-3 py-1.5 bg-[#1C1915] border border-[#3D352A] rounded-xl text-xs text-white placeholder-[#786C5E] focus:outline-none focus:border-amber-400">
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

    <!-- ========================================== -->
    <!-- MODAL 3: HAPUS BUKU (DELETE CONFIRMATION)  -->
    <!-- ========================================== -->
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
