@extends('layouts.base')

@section('title', 'Semua Pesanan & Transaksi - Admin Readowl')

@section('content')
<div class="min-h-screen bg-[#151311] text-[#EBE5DB]">

    <!-- ADMIN TOP NAVIGATION -->
    <header class="border-b border-[#2C2621] bg-[#1A1815]/80 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-[#26211C] border border-[#3D352D] flex items-center justify-center text-[#D4C7B8] hover:text-white transition">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="font-serif text-xl font-bold text-white">Log Transaksi & Pembelian</h1>
                    <span class="text-[10px] text-amber-400 uppercase tracking-widest font-semibold">Semua Pembelian Pembaca</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-[#211E1A] border border-[#383129] rounded-xl text-xs">
                    <span class="text-[#8E8070]">Total Pendapatan: </span>
                    <span class="font-bold text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#2E2820]">
                <div>
                    <h2 class="font-serif text-xl font-bold text-white">Daftar Transaksi ({{ $orders->total() }})</h2>
                    <p class="text-xs text-[#8E8070] mt-0.5">Catatan pesanan buku dari para pengguna terdaftar.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-amber-400 hover:underline">
                    ← Kembali ke Studio Buku
                </a>
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
                                            <div class="w-8 h-12 rounded overflow-hidden shadow-xs shrink-0">
                                                <img src="{{ $order->book->cover_url }}" alt="{{ $order->book->title }}" class="w-full h-full object-cover">
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

                <div class="mt-6 pt-4 border-t border-[#2E2820]">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="py-12 text-center text-[#8E8070] text-xs">
                    Belum ada riwayat transaksi pembelian buku.
                </div>
            @endif
        </div>

    </main>

</div>
@endsection
