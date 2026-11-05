@extends('layouts.base')

@section('title', 'Riwayat Pembelian Buku - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#F6F4EE] flex flex-col justify-between p-4 sm:p-8 lg:p-12">
    
    <!-- Top Navigation Bar -->
    <header class="max-w-7xl mx-auto w-full flex items-center justify-between pb-6 border-b border-[#E8E1D5]">
        <div class="flex items-center gap-3">
            <a href="{{ route('catalog') }}" class="w-10 h-10 rounded-full bg-[#EFE7DC] flex items-center justify-center text-[#7A5034] hover:bg-[#E2D5C4] transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight text-[#1C1815]">Readowl</a>
                <span class="text-xs text-[#8C8075] block">Koleksi Pribadi & Pembelian</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('catalog') }}" class="px-4 py-2 bg-[#1C1815] text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-black transition flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                <span>Kembali ke Rak 3D</span>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto w-full py-8 flex-1">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center gap-2.5">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Title & Stats Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <span class="text-xs uppercase font-bold tracking-widest text-[#8C8075]">Arsip Pembaca</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#1C1815] mt-1">Buku yang Telah Dibeli</h1>
                <p class="text-xs sm:text-sm text-[#7A6E63] mt-1">Daftar buku yang sudah Anda beli dan siap dibaca kapan saja.</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-white border border-[#E8E1D5] rounded-2xl p-4 shadow-sm text-center min-w-[130px]">
                    <span class="text-[10px] uppercase font-bold text-[#8C8075] block">Total Koleksi</span>
                    <span class="font-serif text-2xl font-bold text-[#1C1815]">{{ $orders->total() }} Buku</span>
                </div>
                <div class="bg-white border border-[#E8E1D5] rounded-2xl p-4 shadow-sm text-center min-w-[150px]">
                    <span class="text-[10px] uppercase font-bold text-[#8C8075] block">Total Pengeluaran</span>
                    <span class="font-serif text-xl font-bold text-[#7A5034]">
                        Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Orders Listing -->
        @if($orders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($orders as $order)
                    <div class="bg-white border border-[#EAE3D6] rounded-3xl p-5 shadow-sm hover:shadow-md transition flex gap-5 items-center">
                        <!-- Book Cover -->
                        <div class="w-20 sm:w-24 aspect-[2/3] shrink-0 rounded-xl overflow-hidden shadow-md border border-[#E5DACB]">
                            <img src="{{ $order->book->cover_url }}" alt="{{ $order->book->title }}" class="w-full h-full object-cover">
                        </div>

                        <!-- Order Info -->
                        <div class="flex-1 overflow-hidden space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono text-[#8C8075] uppercase">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <h3 class="font-serif font-bold text-base text-[#1C1815] truncate">{{ $order->book->title }}</h3>
                            <p class="text-xs text-[#7A6E63] truncate">{{ $order->book->author }}</p>

                            <div class="pt-2 text-xs text-[#5C534B] flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-[#A39688] block">Metode: {{ $order->payment_method }}</span>
                                    <span class="font-bold text-[#7A5034]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-[10px] text-[#8C8075]">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <div class="pt-2">
                                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1C1815] hover:text-[#7A5034] transition">
                                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                    <span>Buka di Rak Buku</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white border border-[#EAE3D6] rounded-3xl p-12 text-center max-w-lg mx-auto shadow-sm my-8">
                <div class="w-16 h-16 rounded-full bg-[#FAF7F2] border border-[#E5DACB] mx-auto flex items-center justify-center text-[#7A5034] mb-4">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-[#1C1815]">Belum Ada Buku yang Dibeli</h3>
                <p class="text-xs sm:text-sm text-[#7A6E63] mt-2 mb-6">
                    Koleksi bacaan Anda masih kosong. Buka katalog rak 3D dan pilih buku yang ingin Anda miliki.
                </p>
                <a href="{{ route('catalog') }}" class="px-6 py-3 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs sm:text-sm font-semibold rounded-full shadow-md transition inline-flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Jelajahi Katalog Buku</span>
                </a>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full pt-6 border-t border-[#E8E1D5] flex items-center justify-between text-xs text-[#8C8075]">
        <span>© 2026 Readowl Bookstore • Arsip Transaksi</span>
        <span>MySQL: perpus_db</span>
    </footer>

</div>
@endsection
