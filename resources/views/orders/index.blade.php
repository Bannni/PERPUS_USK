@extends('layouts.base')

@section('title', 'Riwayat Pembelian & Bukti Transaksi - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#F6F4EE] flex flex-col justify-between p-4 sm:p-8 lg:p-12"
     x-data="{
        invoiceModal: false,
        activeOrder: null,
        openInvoice(order) {
            this.activeOrder = order;
            this.invoiceModal = true;
        }
     }">
    
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
            <a href="{{ route('shop') }}" class="px-4 py-2 bg-[#EFE5D7] text-[#5F3D27] text-xs sm:text-sm font-semibold rounded-xl hover:bg-[#E2D5C4] transition flex items-center gap-1.5">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Toko Buku (Shop)</span>
            </a>
            <a href="{{ route('catalog') }}" class="px-4 py-2 bg-[#1C1815] text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-black transition flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                <span>Rak Buku 3D</span>
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
                <div class="bg-white border border-[#E8E1D5] rounded-2xl p-4 shadow-xs text-center min-w-[130px]">
                    <span class="text-[10px] uppercase font-bold text-[#8C8075] block">Total Koleksi</span>
                    <span class="font-serif text-2xl font-bold text-[#1C1815]">{{ $orders->total() }} Buku</span>
                </div>
                <div class="bg-white border border-[#E8E1D5] rounded-2xl p-4 shadow-xs text-center min-w-[150px]">
                    <span class="text-[10px] uppercase font-bold text-[#8C8075] block">Total Transaksi</span>
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
                    @php
                        $orderData = [
                            'order_number' => $order->order_number,
                            'title' => $order->book->title,
                            'author' => $order->book->author,
                            'category' => $order->book->category,
                            'price' => $order->book->price,
                            'quantity' => $order->quantity,
                            'total_price' => $order->total_price,
                            'payment_method' => $order->payment_method,
                            'status' => $order->status,
                            'buyer_notes' => $order->buyer_notes,
                            'date' => $order->created_at->format('d M Y, H:i'),
                            'user_name' => Auth::user()->name,
                            'user_email' => Auth::user()->email,
                            'user_phone' => Auth::user()->phone ?? '-',
                            'cover_url' => $order->book->cover_url
                        ];
                    @endphp
                    <div class="bg-white border border-[#EAE3D6] rounded-3xl p-5 shadow-xs hover:shadow-md transition flex gap-5 items-center">
                        <!-- Book Cover -->
                        <div class="w-20 sm:w-24 aspect-[2/3] shrink-0 rounded-xl overflow-hidden shadow-md border border-[#E5DACB] bg-[#F7F2E8]">
                            <img src="{{ $order->book->cover_url }}" 
                                 alt="{{ $order->book->title }}" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';"
                                 class="w-full h-full object-cover">
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

                            <div class="pt-2 flex items-center gap-3">
                                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1C1815] hover:text-[#7A5034] transition">
                                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                    <span>Buka di Rak</span>
                                </a>

                                <span class="text-slate-300">•</span>

                                <button type="button" 
                                        @click="openInvoice({{ json_encode($orderData) }})" 
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#7A5034] hover:text-[#5F3D27] hover:underline transition">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                                    <span>Bukti Pembelian</span>
                                </button>
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
            <div class="bg-white border border-[#EAE3D6] rounded-3xl p-12 text-center max-w-lg mx-auto shadow-xs my-8">
                <div class="w-16 h-16 rounded-full bg-[#FAF7F2] border border-[#E5DACB] mx-auto flex items-center justify-center text-[#7A5034] mb-4">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-[#1C1815]">Belum Ada Buku yang Dibeli</h3>
                <p class="text-xs sm:text-sm text-[#7A6E63] mt-2 mb-6">
                    Koleksi bacaan Anda masih kosong. Kunjungi toko buku kami dan pilih judul favorit untuk mulai mengisi rak buku Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('shop') }}" class="w-full sm:w-auto px-6 py-3 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition inline-flex items-center justify-center gap-2">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Beli Buku di Shop</span>
                    </a>
                    <a href="{{ route('catalog') }}" class="w-full sm:w-auto px-6 py-3 bg-[#EFE5D7] text-[#5F3D27] text-xs sm:text-sm font-semibold rounded-xl hover:bg-[#E5D7C5] transition inline-flex items-center justify-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>Lihat Rak 3D</span>
                    </a>
                </div>
            </div>
        @endif

    </main>

    <!-- INVOICE / RECEIPT MODAL -->
    <div x-show="invoiceModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="invoiceModal = false">
        
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-[#E8DEC0] overflow-hidden"
             @click.away="invoiceModal = false">
            
            <button @click="invoiceModal = false" class="absolute top-4 right-4 p-2 text-[#9C8B7E] hover:text-[#1C1815] rounded-full print:hidden">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <template x-if="activeOrder">
                <div class="receipt-content">
                    <!-- Receipt Header -->
                    <div class="text-center pb-5 border-b border-dashed border-slate-300">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#FAF7F2] border border-[#E8DEC0] text-[#7A5034] mb-2">
                            <i data-lucide="book-open" class="w-6 h-6"></i>
                        </div>
                        <h2 class="font-serif text-2xl font-bold text-[#1C1815]">Readowl Bookstore</h2>
                        <p class="text-[11px] text-[#7A6E63]">Bukti Pembayaran & Lisensi Digital Resmi</p>
                        <div class="mt-2 inline-block px-3 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            TRANSAKSI BERHASIL / LUNAS
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="py-4 space-y-2 text-xs border-b border-dashed border-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nomor Pesanan:</span>
                            <span class="font-mono font-bold text-slate-800" x-text="activeOrder.order_number"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal:</span>
                            <span class="text-slate-800" x-text="activeOrder.date"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nama Pembeli:</span>
                            <span class="font-medium text-slate-800" x-text="activeOrder.user_name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. WhatsApp/Telepon:</span>
                            <span class="font-medium text-slate-800" x-text="activeOrder.user_phone"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode Pembayaran:</span>
                            <span class="font-medium text-slate-800" x-text="activeOrder.payment_method"></span>
                        </div>
                    </div>

                    <!-- Purchased Item -->
                    <div class="py-4 space-y-3 border-b border-dashed border-slate-300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-14 rounded overflow-hidden shadow-xs shrink-0 border border-slate-200">
                                <img :src="activeOrder.cover_url" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <h4 class="font-serif font-bold text-xs text-slate-900 truncate" x-text="activeOrder.title"></h4>
                                <p class="text-[10px] text-slate-500" x-text="'Penulis: ' + activeOrder.author"></p>
                                <p class="text-[10px] text-slate-500" x-text="'Genre: ' + activeOrder.category"></p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-800" x-text="'Rp ' + Number(activeOrder.price).toLocaleString('id-ID')"></span>
                                <span class="block text-[10px] text-slate-400" x-text="'Qty: ' + activeOrder.quantity"></span>
                            </div>
                        </div>

                        <template x-if="activeOrder.buyer_notes">
                            <div class="bg-slate-50 p-2 rounded-lg text-[11px] text-slate-600">
                                <span class="font-semibold text-slate-700">Catatan:</span>
                                <span x-text="activeOrder.buyer_notes"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Total Amount -->
                    <div class="py-4 flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-700">TOTAL PEMBAYARAN:</span>
                        <span class="text-lg font-serif text-[#7A5034]" x-text="'Rp ' + Number(activeOrder.total_price).toLocaleString('id-ID')"></span>
                    </div>

                    <!-- Barcode Aesthetic -->
                    <div class="pt-2 text-center border-t border-dashed border-slate-200">
                        <div class="font-mono text-xl tracking-[0.3em] text-slate-400 select-none">
                            ||| | |||| | |||||| | ||| || |
                        </div>
                        <p class="text-[9px] text-slate-400 uppercase mt-1">Status: Terverifikasi di Rak Pribadi • Readowl Cloud</p>
                    </div>

                    <!-- Print / Action Buttons -->
                    <div class="mt-6 flex items-center justify-end gap-3 print:hidden">
                        <button type="button" @click="invoiceModal = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Tutup
                        </button>
                        <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-[#1C1815] hover:bg-black text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            <span>Cetak Struk / Unduh PDF</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full pt-6 border-t border-[#E8E1D5] flex items-center justify-between text-xs text-[#8C8075]">
        <span>© 2026 Readowl Bookstore • Arsip Transaksi</span>
        <span>Sistem Kurasi Readowl</span>
    </footer>

</div>
@endsection
