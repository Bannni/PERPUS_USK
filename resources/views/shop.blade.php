@extends('layouts.base')

@section('title', 'Toko Buku (Shop) - Beli Koleksi Buku Terbaik')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]" x-data="{
    selectedBook: null,
    buyModalOpen: false,
    buyQuantity: 1,
    openBuy(book) {
        this.selectedBook = book;
        this.buyQuantity = 1;
        this.buyModalOpen = true;
    }
}">

    <!-- TOP HEADER -->
    <header class="border-b border-[#EFE5D7] bg-[#FAF7F2]/90 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-[#EFE4D6] flex items-center justify-center text-[#7A5034]">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <span class="font-serif text-2xl font-bold tracking-tight text-[#5F3D27]">Readowl Shop</span>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-[#7A6B60]">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27] transition">Beranda</a>
                <a href="{{ route('shop') }}" class="font-bold text-[#7A5034]">Toko Buku</a>
                <a href="{{ route('catalog') }}" class="hover:text-[#5F3D27] transition flex items-center gap-1.5 font-semibold text-[#7A5034]">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Rak 3D</span>
                </a>
                <a href="{{ route('about') }}" class="hover:text-[#5F3D27] transition">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="hover:text-[#5F3D27] transition">Kontak</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-[#5F3D27] transition">Pesanan Saya</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 bg-[#1C1815] text-amber-300 rounded-xl text-xs font-semibold hover:bg-black transition">
                            Admin
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- User Auth & Cart -->
            <div class="flex items-center gap-3">
                @auth
                    <!-- Cart Button with Counter -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-[#7A5034] hover:bg-[#EFE4D6] rounded-xl transition" title="Keranjang Belanja">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-[#7A5034] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-[#8C7A6D]">Halo,</span>
                        <span class="font-bold text-[#4A3222]">{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-[#9C8B7E] hover:text-red-600 transition" title="Keluar">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold text-[#5F3D27] hover:underline">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-[#7A5034] text-white text-xs font-semibold rounded-xl hover:bg-[#5F3D27] transition">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- MAIN STORE SECTION -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <a href="{{ route('catalog') }}" class="text-xs font-bold text-emerald-900 underline hover:no-underline">
                    Buka Rak Buku Saya (My Library) →
                </a>
            </div>
        @endif

        <!-- Banner / Header -->
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <span class="text-xs font-bold uppercase tracking-widest text-[#8C7A6D]">Katalog Pembelian Resmi</span>
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-[#4A3222] mt-1">
                Beli Buku untuk Mengisi Rakmu
            </h1>
            <p class="text-xs sm:text-sm text-[#7A6B60] mt-3">
                Pilih dan beli buku yang Anda sukai. Setiap buku yang dibeli akan langsung masuk ke rak 3D di halaman <strong>My Library</strong> sesuai dengan genrenya!
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-[#F5EFEB] border border-[#E9DFD2] rounded-2xl p-4 sm:p-5 mb-10 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Genre Category Pills -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <a href="{{ route('shop') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($selectedCategory) ? 'bg-[#7A5034] text-white shadow-xs' : 'text-[#7A6B60] bg-white hover:bg-[#EFE5D7]' }}">
                    Semua Genre
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop', ['category' => $cat, 'q' => request('q')]) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $selectedCategory === $cat ? 'bg-[#7A5034] text-white shadow-xs' : 'text-[#7A6B60] bg-white hover:bg-[#EFE5D7]' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <!-- Search Field -->
            <form action="{{ route('shop') }}" method="GET" class="relative w-full md:w-72">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <i data-lucide="search" class="w-4 h-4 text-[#A8988B] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari judul buku..." 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30 transition">
            </form>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($books as $book)
                @php
                    $isPurchased = in_array($book->id, $userPurchasedIds);
                @endphp
                <div class="bg-white border border-[#EFE7DD] rounded-2xl p-3 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <!-- Cover -->
                        <div class="aspect-[2/3] w-full rounded-xl overflow-hidden shadow-sm border border-[#E9DFD2] bg-[#FAF7F2] relative">
                            <img src="{{ $book->cover_url }}" 
                                 alt="{{ $book->title }}" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            
                            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-xs text-[#7A5034] text-[10px] font-bold px-1.5 py-0.5 rounded shadow-xs">
                                ★ {{ $book->rating }}
                            </div>

                            @if($isPurchased)
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center p-2 text-center">
                                    <span class="px-2 py-1 bg-emerald-600 text-white font-bold text-[10px] rounded-lg shadow-md flex items-center gap-1">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>Sudah di Rak</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="mt-3">
                            <span class="text-[9px] uppercase font-bold tracking-wider text-[#9C8B7E] block truncate">
                                {{ $book->category }}
                            </span>
                            <h3 class="font-bold text-xs sm:text-sm text-[#4A3222] group-hover:text-[#7A5034] transition line-clamp-1 mt-0.5" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>
                            <p class="text-[11px] text-[#8C7A6D] line-clamp-1">{{ $book->author }}</p>

                            <!-- Stock & Publisher -->
                            @php
                                $stock = $book->stock ? $book->stock->quantity : 0;
                            @endphp
                            <div class="flex items-center justify-between text-[10px] mt-1.5 pt-1.5 border-t border-dashed border-[#F2EAE0]">
                                <span class="text-[#8C7A6D] truncate max-w-[100px]">{{ $book->publisher ?? 'Readowl' }}</span>
                                @if($stock > 0)
                                    <span class="text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded text-[9px]">
                                        Stok: {{ $stock }}
                                    </span>
                                @else
                                    <span class="text-red-600 font-semibold bg-red-50 px-1.5 py-0.5 rounded text-[9px]">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Price & Buy / Cart Button -->
                    <div class="mt-3 pt-2.5 border-t border-[#F2EAE0]">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-[#9C8B7E]">Harga:</span>
                            <span class="text-xs sm:text-sm font-bold text-[#7A5034]">
                                Rp {{ number_format($book->price, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($isPurchased)
                            <a href="{{ route('catalog') }}" class="w-full py-2 bg-[#EFE5D7] text-[#5F3D27] text-xs font-semibold rounded-xl text-center block hover:bg-[#E5D7C5] transition">
                                Buka di Rak 3D
                            </a>
                        @elseif($stock > 0)
                            <div class="grid grid-cols-2 gap-1.5">
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="w-full py-2 bg-[#FAF7F2] hover:bg-[#EFE5D7] text-[#5F3D27] text-xs font-semibold rounded-xl border border-[#DACAB6] transition flex items-center justify-center gap-1" title="Tambah ke Keranjang">
                                        <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                        <span>+ Cart</span>
                                    </button>
                                </form>
                                <button type="button" 
                                        @click="openBuy({{ json_encode($book) }})" 
                                        class="w-full py-2 bg-[#1C1815] hover:bg-black text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-1">
                                    <span>Beli</span>
                                </button>
                            </div>
                        @else
                            <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-xs font-semibold rounded-xl cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-[#9C8B7E] text-xs sm:text-sm">
                    Tidak ada buku yang ditemukan pada filter ini.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $books->links() }}
        </div>

    </main>

    <!-- BUY MODAL (CHECKOUT DRAWER) -->
    <div x-show="buyModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="buyModalOpen = false">
        
        <div class="bg-[#FAF7F2] border border-[#E7DCCE] rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative"
             @click.away="buyModalOpen = false">
            
            <button @click="buyModalOpen = false" class="absolute top-4 right-4 p-2 text-[#9C8B7E] hover:text-[#4A3222] rounded-full">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <template x-if="selectedBook">
                <div>
                    <div class="flex items-center gap-4 pb-4 border-b border-[#E8DEC0] mb-5">
                        <div class="w-16 h-24 rounded-lg overflow-hidden shadow-sm shrink-0 border border-[#DACBB8] bg-[#FAF7F2]">
                            <img :src="selectedBook.cover_url" 
                                 :alt="selectedBook.title" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-[#9C6D47] bg-[#EFE4D6] px-2 py-0.5 rounded-full" x-text="selectedBook.category"></span>
                            <h3 class="font-serif text-lg font-bold text-[#4A3222] mt-1" x-text="selectedBook.title"></h3>
                            <p class="text-xs text-[#8C7A6D]" x-text="'Penulis: ' + selectedBook.author"></p>
                            <p class="text-sm font-bold text-[#7A5034] mt-1" x-text="'Rp ' + Number(selectedBook.price).toLocaleString('id-ID')"></p>
                        </div>
                    </div>

                    @auth
                        <!-- Form Beli untuk User Login -->
                        <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="book_id" :value="selectedBook.id">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-[#5C534B] mb-1">Jumlah Eksemplar</label>
                                    <input type="number" name="quantity" min="1" max="5" x-model="buyQuantity" class="w-full px-3 py-2 bg-white border border-[#DACAB6] rounded-xl text-xs font-semibold text-[#4A3222] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#5C534B] mb-1">Metode Pembayaran</label>
                                    <select name="payment_method" class="w-full px-3 py-2 bg-white border border-[#DACAB6] rounded-xl text-xs font-medium text-[#4A3222] focus:outline-none">
                                        <option value="QRIS (Instant)">QRIS (Instant)</option>
                                        <option value="Transfer Bank BCA/Mandiri">Transfer Bank</option>
                                        <option value="E-Wallet GoPay/OVO">E-Wallet</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#5C534B] mb-1">Catatan Pembelian (Opsional)</label>
                                <input type="text" name="buyer_notes" placeholder="Contoh: Tambahkan ke rak koleksi utama" class="w-full px-3 py-2 bg-white border border-[#DACAB6] rounded-xl text-xs text-[#4A3222] placeholder-[#B5ABA0] focus:outline-none">
                            </div>

                            <div class="bg-[#F2E8DC] p-4 rounded-2xl flex items-center justify-between border border-[#E3D6C5]">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-[#8C7A6D] block">Total Pembayaran:</span>
                                    <span class="font-serif text-2xl font-bold text-[#7A5034]" x-text="'Rp ' + (selectedBook.price * buyQuantity).toLocaleString('id-ID')"></span>
                                </div>
                                <span class="text-[10px] text-emerald-800 bg-emerald-100 font-semibold px-2.5 py-1 rounded-full">
                                    ✓ Langsung Masuk Rak 3D
                                </span>
                            </div>

                            <div class="pt-2 flex items-center justify-end gap-3">
                                <button type="button" @click="buyModalOpen = false" class="px-5 py-2.5 text-xs font-semibold text-[#8C7A6D] hover:text-[#4A3222]">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    <span>Konfirmasi & Bayar Sekarang</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Prompt jika belum login -->
                        <div class="text-center py-4 space-y-4">
                            <p class="text-xs text-[#7A6B60]">
                                Anda perlu masuk ke akun Readowl untuk membeli buku ini dan menyimpannya di rak buku pribadi Anda.
                            </p>
                            <div class="flex gap-3 justify-center">
                                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-[#1C1815] text-white text-xs font-semibold rounded-xl hover:bg-black transition">
                                    Masuk ke Akun
                                </a>
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#EFE5D7] text-[#5F3D27] text-xs font-semibold rounded-xl hover:bg-[#E5D7C5] transition">
                                    Daftar Akun Baru
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
