@extends('layouts.base')

@section('title', '404 - Halaman Tidak Ditemukan | Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E] flex flex-col justify-between p-6 sm:p-12">
    <!-- Header -->
    <header class="max-w-4xl mx-auto w-full flex items-center justify-between pb-6 border-b border-[#E8DEC0]">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-full bg-[#EFE5D7] flex items-center justify-center text-[#7A5034]">
                <i data-lucide="book-open" class="w-4 h-4"></i>
            </span>
            <span class="font-serif text-2xl font-bold tracking-tight text-[#4A3222]">Readowl</span>
        </a>
        <span class="text-xs font-mono uppercase tracking-wider text-[#9C8B7E]">Error 404 • Page Not Found</span>
    </header>

    <!-- Content -->
    <main class="max-w-xl mx-auto w-full text-center py-12">
        <div class="relative inline-block mb-6">
            <div class="w-24 h-24 rounded-3xl bg-[#F2E8DC] border border-[#E0D5C7] flex items-center justify-center text-[#7A5034] shadow-inner mx-auto">
                <i data-lucide="file-question" class="w-12 h-12"></i>
            </div>
            <span class="absolute -bottom-2 -right-2 px-3 py-1 bg-[#7A5034] text-white rounded-full text-xs font-mono font-bold">
                404
            </span>
        </div>

        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#4A3222]">Akses tidak disetujui</h1>
        

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 bg-[#1C1815] hover:bg-black text-white text-xs font-semibold rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Kembali ke Beranda</span>
            </a>
            
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-4xl mx-auto w-full pt-6 border-t border-[#E8DEC0] text-center text-xs text-[#9C8B7E]">
        © 2026 Readowl Bookstore • Temukan Bacaan Terbaikmu
    </footer>
</div>
@endsection
