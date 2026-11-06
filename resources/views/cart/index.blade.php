@extends('layouts.base')

@section('title', 'Keranjang Belanja (Cart) - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]">

    <!-- TOP HEADER -->
    <header class="border-b border-[#EFE5D7] bg-[#FAF7F2]/90 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-[#EFE4D6] flex items-center justify-center text-[#7A5034]">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-serif text-2xl font-bold tracking-tight text-[#5F3D27] block leading-none">Readowl Cart</span>
                        <span class="text-[9px] uppercase tracking-widest text-[#9C8B7E]">Keranjang Belanja Pengguna</span>
                    </div>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-6 text-xs sm:text-sm font-medium text-[#7A6B60]">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27] transition">Beranda</a>
                <a href="{{ route('shop') }}" class="hover:text-[#5F3D27] transition">Toko Buku</a>
                <a href="{{ route('catalog') }}" class="hover:text-[#5F3D27] transition">Rak 3D</a>
                <a href="{{ route('about') }}" class="hover:text-[#5F3D27] transition">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="hover:text-[#5F3D27] transition">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('orders.index') }}" class="px-3.5 py-1.5 bg-[#EFE4D6] text-[#7A5034] rounded-xl text-xs font-semibold hover:bg-[#E5D7C5] transition">
                    Pesanan Saya
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CART CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center gap-3 shadow-xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-2xl flex items-center gap-3 shadow-xs">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-2xl space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- Title -->
        <div class="mb-8">
            <span class="text-xs uppercase font-bold tracking-widest text-[#9C8B7E]">Alur Transaksi BNSP</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#4A3222] mt-1">Keranjang Belanja Buku</h1>
            <p class="text-xs sm:text-sm text-[#7A6B60] mt-1">
                Kelola item buku, sesuaikan kuantitas, dan lanjutkan ke checkout dengan metode <strong>Payment at Delivery (COD)</strong>.
            </p>
        </div>

        @if($carts->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Cart Items Table/Cards (8 cols) -->
                <div class="lg:col-span-7 xl:col-span-8 space-y-4">
                    <div class="bg-white border border-[#EFE5D7] rounded-3xl p-5 sm:p-6 shadow-xs divide-y divide-[#F2EAE0]">
                        @foreach($carts as $item)
                            <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <!-- Cover & Details -->
                                <div class="flex items-center gap-4">
                                    <div class="w-16 sm:w-20 aspect-[2/3] rounded-xl overflow-hidden shadow-sm shrink-0 border border-[#E5DACB] bg-[#FAF7F2]">
                                        <img src="{{ $item->book->cover_url }}" 
                                             alt="{{ $item->book->title }}" 
                                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="text-[9px] uppercase font-bold text-[#9C8B7E] block">{{ $item->book->category }}</span>
                                        <h3 class="font-serif font-bold text-sm sm:text-base text-[#4A3222] line-clamp-1">{{ $item->book->title }}</h3>
                                        <p class="text-xs text-[#8C7A6D]">{{ $item->book->author }}</p>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-xs font-bold text-[#7A5034]">Rp {{ number_format($item->book->price, 0, ',', '.') }}</span>
                                            <span class="text-[10px] text-slate-400">•</span>
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                                Sisa Stok: {{ $item->book->stock ? $item->book->stock->quantity : 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Delete -->
                                <div class="flex items-center justify-between w-full sm:w-auto gap-4 pt-2 sm:pt-0">
                                    <!-- Quantity Update Form -->
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-[#DACBB8] rounded-xl overflow-hidden bg-[#FAF7F2]">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                name="quantity" 
                                                value="{{ max(1, $item->quantity - 1) }}" 
                                                class="px-2.5 py-1 text-xs text-[#5F3D27] hover:bg-[#EFE4D6] transition font-bold"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>
                                        <span class="px-3 py-1 text-xs font-bold text-[#4A3222] border-x border-[#DACBB8] bg-white">
                                            {{ $item->quantity }}
                                        </span>
                                        <button type="submit" 
                                                name="quantity" 
                                                value="{{ $item->quantity + 1 }}" 
                                                class="px-2.5 py-1 text-xs text-[#5F3D27] hover:bg-[#EFE4D6] transition font-bold"
                                                {{ ($item->book->stock && $item->quantity >= $item->book->stock->quantity) ? 'disabled' : '' }}>
                                            +
                                        </button>
                                    </form>

                                    <!-- Item Subtotal -->
                                    <div class="text-right min-w-[90px]">
                                        <span class="font-serif font-bold text-xs sm:text-sm text-[#4A3222] block">
                                            Rp {{ number_format($item->book->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-[#A8988B] hover:text-red-600 rounded-lg transition" title="Hapus dari Keranjang">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center px-2 text-xs text-[#8C7A6D]">
                        <a href="{{ route('shop') }}" class="font-semibold text-[#7A5034] hover:underline flex items-center gap-1">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            <span>Lanjut Belanja Buku Lainnya</span>
                        </a>
                        <span>{{ $carts->count() }} judul buku di keranjang</span>
                    </div>
                </div>

                <!-- Right: Checkout & Delivery Form (4 cols) -->
                <div class="lg:col-span-5 xl:col-span-4 bg-white border border-[#EFE5D7] rounded-3xl p-6 sm:p-7 shadow-md space-y-6">
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-[#9C8B7E] block">Ringkasan Pesanan</span>
                        <h2 class="font-serif text-2xl font-bold text-[#4A3222]">Formulir Checkout</h2>
                    </div>

                    <!-- Price Calculations -->
                    <div class="space-y-3 pb-4 border-b border-[#F2EAE0] text-xs">
                        <div class="flex justify-between text-[#7A6B60]">
                            <span>Subtotal Buku ({{ $carts->sum('quantity') }} eks):</span>
                            <span class="font-semibold text-[#4A3222]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#7A6B60]">
                            <span>Biaya Pengiriman:</span>
                            <span class="font-semibold text-emerald-700">GRATIS (Promo)</span>
                        </div>
                        <div class="pt-2 flex justify-between items-center text-sm">
                            <span class="font-bold text-[#4A3222]">Total Pembayaran:</span>
                            <span class="font-serif text-2xl font-bold text-[#7A5034]">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Checkout Form (POST /checkout) -->
                    <form action="{{ route('checkout') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Shipping Address -->
                        <div>
                            <label class="block text-xs font-bold text-[#5C534B] mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#7A5034]"></i>
                                <span>Alamat Pengiriman Lengkap *</span>
                            </label>
                            <textarea name="shipping_address" 
                                      rows="3" 
                                      required 
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, kode pos..." 
                                      class="w-full px-3.5 py-2.5 bg-[#FAF7F2] border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30 leading-relaxed">{{ old('shipping_address') }}</textarea>
                            <span class="text-[10px] text-[#9C8B7E] block mt-1">Kurir akan mengirim buku ke alamat ini.</span>
                        </div>

                        <!-- Payment Method (PRD Requirement: Payment at Delivery) -->
                        <div>
                            <label class="block text-xs font-bold text-[#5C534B] mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#7A5034]"></i>
                                <span>Metode Pembayaran *</span>
                            </label>

                            <div class="p-3.5 rounded-2xl border-2 border-[#7A5034] bg-[#FAF7F2] flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" 
                                           id="payment_cod" 
                                           name="payment_method" 
                                           value="Payment at Delivery (COD)" 
                                           checked 
                                           class="w-4 h-4 text-[#7A5034] focus:ring-[#7A5034]">
                                    <label for="payment_cod" class="cursor-pointer">
                                        <span class="text-xs font-bold text-[#4A3222] block">Payment at Delivery (COD)</span>
                                        <span class="text-[10px] text-[#7A6B60] block">Bayar tunai di tempat saat kurir menyerahkan buku</span>
                                    </label>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">
                                    Resmi BNSP
                                </span>
                            </div>
                        </div>

                        <!-- Buyer Notes -->
                        <div>
                            <label class="block text-xs font-semibold text-[#5C534B] mb-1">Catatan Tambahan (Opsional)</label>
                            <input type="text" 
                                   name="buyer_notes" 
                                   value="{{ old('buyer_notes') }}"
                                   placeholder="Contoh: Titipkan ke satpam jika sedang keluar" 
                                   class="w-full px-3.5 py-2 bg-[#FAF7F2] border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30">
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99]">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Proses Checkout (COD)</span>
                            </button>
                            <p class="text-[10px] text-center text-[#9C8B7E] mt-2">
                                Stok buku pada tabel <code>stocks</code> akan otomatis berkurang setelah pesanan berhasil dibuat.
                            </p>
                        </div>
                    </form>

                </div>

            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white border border-[#EFE5D7] rounded-3xl p-12 text-center max-w-lg mx-auto shadow-xs my-8">
                <div class="w-16 h-16 rounded-full bg-[#FAF7F2] border border-[#E5DACB] mx-auto flex items-center justify-center text-[#7A5034] mb-4">
                    <i data-lucide="shopping-cart" class="w-8 h-8"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-[#4A3222]">Keranjang Belanja Kosong</h3>
                <p class="text-xs sm:text-sm text-[#7A6B60] mt-2 mb-6">
                    Anda belum memasukkan buku apa pun ke dalam keranjang belanja. Jelajahi toko buku kami dan temukan bacaan terbaik Anda.
                </p>
                <a href="{{ route('shop') }}" class="px-6 py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition inline-flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Jelajahi Toko Buku</span>
                </a>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-[#EFE5D7] bg-[#FAF7F2] py-8 text-xs text-[#9C8B7E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© 2026 Readowl Bookstore • Modul Keranjang Belanja & Checkout COD</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27]">Beranda</a>
                <span>•</span>
                <a href="{{ route('shop') }}" class="hover:text-[#5F3D27]">Toko</a>
                <span>•</span>
                <a href="{{ route('cart.index') }}" class="hover:text-[#5F3D27] font-semibold text-[#7A5034]">Keranjang</a>
                <span>•</span>
                <a href="{{ route('orders.index') }}" class="hover:text-[#5F3D27]">Pesanan Saya</a>
            </div>
        </div>
    </footer>

</div>
@endsection
