@extends('layouts.base')

@section('title', 'Pemantauan Data Pengguna - Admin Readowl')

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
                    <h1 class="font-serif text-xl font-bold text-white">Monitoring Data Pengguna</h1>
                    <span class="text-[10px] text-amber-400 uppercase tracking-widest font-semibold">Database Pembaca & Administrator</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold text-[#D4C7B8] hover:text-white bg-[#26211C] hover:bg-[#332D26] rounded-xl border border-[#3D352D] transition flex items-center gap-2">
                    <i data-lucide="book" class="w-4 h-4 text-amber-400"></i>
                    <span>Studio Buku (CRUD)</span>
                </a>
                <a href="{{ route('admin.orders') }}" class="px-3.5 py-2 text-xs font-semibold text-[#D4C7B8] hover:text-white bg-[#26211C] hover:bg-[#332D26] rounded-xl border border-[#3D352D] transition flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-400"></i>
                    <span>Log Pesanan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        <!-- Stats Plaque -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#8E8070] block">Total Pengguna Terdaftar</span>
                <span class="font-serif text-3xl font-bold text-white mt-1 block">{{ $totalUsers }}</span>
                <span class="text-[10px] text-amber-400/80 mt-1 block">Tercatat di perpus_db</span>
            </div>

            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#8E8070] block">Pembaca / Member</span>
                <span class="font-serif text-3xl font-bold text-sky-400 mt-1 block">{{ $totalMembers }}</span>
                <span class="text-[10px] text-[#A69785] mt-1 block">Memiliki akses beli & rak 3D</span>
            </div>

            <div class="bg-gradient-to-b from-[#211E1A] to-[#1A1714] border border-[#383129] rounded-3xl p-5 shadow-lg">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#8E8070] block">Administrator</span>
                <span class="font-serif text-3xl font-bold text-amber-400 mt-1 block">{{ $totalAdmins }}</span>
                <span class="text-[10px] text-[#A69785] mt-1 block">Akses penuh CRUD & monitor</span>
            </div>
        </div>

        <!-- Search & User Table Card -->
        <div class="bg-gradient-to-b from-[#211E1A] to-[#181613] border border-[#383128] rounded-3xl p-6 shadow-xl">
            
            <div class="flex flex-col sm:flex-row items-center justify-between pb-5 border-b border-[#2E2820] gap-4 mb-6">
                <div>
                    <h2 class="font-serif text-xl font-bold text-white">Daftar Akun Pengguna</h2>
                    <p class="text-xs text-[#8E8070] mt-0.5">Pantau data nama, email, nomor telepon, dan riwayat koleksi buku pembaca.</p>
                </div>

                <form action="{{ route('admin.users') }}" method="GET" class="relative w-full sm:w-72">
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
                            <th class="pb-3 px-3">Nomor Telepon</th>
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
                                <td class="py-4 px-3 font-bold text-white">
                                    {{ $user->orders_count ?? 0 }} Buku
                                </td>
                                <td class="py-4 px-3 font-bold text-emerald-400">
                                    Rp {{ number_format($user->orders_sum_total_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-3 text-[#8E8070]">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[#8E8070]">
                                    Tidak ada data pengguna yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-[#2E2820]">
                {{ $users->links() }}
            </div>

        </div>

    </main>

</div>
@endsection
