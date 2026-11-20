@extends('layouts.base')

@section('title', 'Hubungi Kami (Contact Us) - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]">

    <!-- TOP HEADER -->
    @include('partials.navbar')

    <!-- MAIN CONTACT FORM SECTION -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl flex items-center gap-3 shadow-xs max-w-2xl mx-auto">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-2xl max-w-2xl mx-auto space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start max-w-5xl mx-auto">
            
            <!-- Left Contact Info -->
            <div class="lg:col-span-5 space-y-6">
                <div>
                    <span class="text-xs uppercase font-bold tracking-widest text-[#9C8B7E] bg-[#EFE4D6] px-3 py-1 rounded-full">
                        Formulir Kontak Publik
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#4A3222] mt-3">
                        Kirim Pesan & Pertanyaan Anda
                    </h1>
                    <p class="text-xs sm:text-sm text-[#7A6B60] mt-3 leading-relaxed">
                        Apakah Anda ingin menanyakan ketersediaan judul buku, konfirmasi status pesanan, atau memberi kritik dan saran? Silakan isi formulir berikut.
                    </p>
                </div>

                <!-- Info Cards -->
                <div class="space-y-4 pt-4">
                    <div class="flex items-start gap-4 p-4 bg-[#F5EFEB] rounded-2xl border border-[#E9DFD2]">
                        <div class="w-10 h-10 rounded-xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#9C8B7E] block">Email Resmi</span>
                            <span class="text-xs font-semibold text-[#4A3222]">halo@readowl.com</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-[#F5EFEB] rounded-2xl border border-[#E9DFD2]">
                        <div class="w-10 h-10 rounded-xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#9C8B7E] block">Layanan WhatsApp / CS</span>
                            <span class="text-xs font-semibold text-[#4A3222]">+62 812-3456-7890</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-[#F5EFEB] rounded-2xl border border-[#E9DFD2]">
                        <div class="w-10 h-10 rounded-xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#9C8B7E] block">Kantor Studio Perpustakaan</span>
                            <span class="text-xs text-[#4A3222]">Jl. Literasi Kencana No. 42, Jakarta Selatan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form Card -->
            <div class="lg:col-span-7 bg-white border border-[#EFE5D7] rounded-3xl p-6 sm:p-10 shadow-lg">
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-[#5C534B] mb-1.5">Nama Lengkap *</label>
                        <input type="text" 
                               name="name" 
                               required 
                               value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                               placeholder="Masukkan nama lengkap Anda" 
                               class="w-full px-4 py-3 bg-[#FAF7F2] border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#5C534B] mb-1.5">Alamat Email *</label>
                        <input type="email" 
                               name="email" 
                               required 
                               value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                               placeholder="nama@email.com" 
                               class="w-full px-4 py-3 bg-[#FAF7F2] border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#5C534B] mb-1.5">Pesan / Pertanyaan / Aduan *</label>
                        <textarea name="message" 
                                  rows="5" 
                                  required 
                                  placeholder="Tuliskan pertanyaan atau informasi buku yang Anda butuhkan secara jelas..." 
                                  class="w-full px-4 py-3 bg-[#FAF7F2] border border-[#E0D5C7] rounded-xl text-xs text-[#4A3222] placeholder-[#A8988B] focus:outline-none focus:ring-2 focus:ring-[#7A5034]/30 transition leading-relaxed">{{ old('message') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99]">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            <span>Kirim Pesan ke Dashboard Admin</span>
                        </button>
                        <p class="text-[11px] text-center text-[#9C8B7E] mt-3">
                            Pesan Anda akan otomatis terhubung ke panel administrator Readowl.
                        </p>
                    </div>
                </form>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-[#EFE5D7] bg-[#FAF7F2] py-8 text-xs text-[#9C8B7E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© 2026 Readowl Bookstore • Formulir Kontak Terintegrasi</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27]">Beranda</a>
                <span>•</span>
                <a href="{{ route('shop') }}" class="hover:text-[#5F3D27]">Toko</a>
                <span>•</span>
                <a href="{{ route('about') }}" class="hover:text-[#5F3D27]">Tentang Kami</a>
                <span>•</span>
                <a href="{{ route('contact') }}" class="hover:text-[#5F3D27] font-semibold text-[#7A5034]">Kontak</a>
            </div>
        </div>
    </footer>

</div>
@endsection
