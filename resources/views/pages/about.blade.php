@extends('layouts.base')

@section('title', 'Tentang Kami (About Us) - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#FAF7F2] text-[#2D231E]">

    <!-- TOP HEADER -->
    @include('partials.navbar')

    <!-- HERO PROFILE SECTION -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        
        <!-- Breadcrumb / Header Tag -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-[#9C8B7E] bg-[#EFE4D6] px-3.5 py-1 rounded-full">
                Profil Resmi Toko Buku
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl font-bold text-[#4A3222] mt-4 tracking-tight">
                Membawa Kehangatan Membaca ke Era Modern
            </h1>
            <p class="text-xs sm:text-base text-[#7A6B60] mt-4 leading-relaxed font-sans">
                <strong>Readowl Bookstore</strong> didirikan dengan filosofi bahwa setiap halaman buku memiliki jiwa yang layak dirayakan. Kami memadukan kemudahan belanja digital dengan pengalaman visual rak buku tiga dimensi yang menenangkan.
            </p>
        </div>

        <!-- Editorial Story Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center mb-16">
            <div class="rounded-3xl overflow-hidden shadow-xl border border-[#E7DCCE] bg-[#F2E8DC] aspect-[4/3] relative">
                <img src="https://img.peerspace.com/image/upload/f_auto,q_auto,dpr_auto,w_3840/b9eh6ktvabfzevgk9gda" 
                     alt="Readowl Library Studio" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-6 sm:p-8 text-white">
                    <div>
                        <span class="text-[10px] tracking-widest uppercase font-bold text-amber-300">Kurasi Pilihan</span>
                        <h3 class="font-serif text-xl sm:text-2xl font-bold">Arsip Sastra & Pengetahuan Terbaik</h3>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span class="text-xs uppercase font-bold text-[#7A5034] tracking-wider">Visi & Misi Kami</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[#4A3222] mt-1">
                        Akses Literasi Berkualitas dengan Pengalaman Tanpa Batas
                    </h2>
                </div>

                <p class="text-xs sm:text-sm text-[#7A6B60] leading-relaxed">
                    Kami percaya bahwa toko buku tidak sekadar tempat bertransaksi, melainkan ruang inspirasi tempat bertemunya ide-ide brilian dari berbagai penjuru dunia. Melalui Readowl, Anda dapat menemukan buku terbitan lokal dan internasional dengan jaminan keaslian dan pelayanan pengiriman langsung ke alamat tujuan Anda.
                </p>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#EFE5D7]">
                    <div class="p-4 bg-[#F5EFEB] rounded-2xl border border-[#E9DFD2]">
                        <span class="font-serif text-3xl font-bold text-[#7A5034] block">{{ $totalBooks }}</span>
                        <span class="text-xs text-[#8C7A6D] font-medium">Buku Terdaftar</span>
                    </div>
                    <div class="p-4 bg-[#F5EFEB] rounded-2xl border border-[#E9DFD2]">
                        <span class="font-serif text-3xl font-bold text-[#7A5034] block">{{ $totalCategories }}</span>
                        <span class="text-xs text-[#8C7A6D] font-medium">Kategori Genre</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 Pilar Keunggulan -->
        <div class="bg-white border border-[#EFE5D7] rounded-3xl p-8 sm:p-12 shadow-xs mb-16">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs uppercase font-bold tracking-widest text-[#9C8B7E]">Standar Kualitas</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[#4A3222] mt-1">Mengapa Memilih Readowl?</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center font-bold">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-serif font-bold text-lg text-[#4A3222]">Pembayaran Digital Terpercaya</h4>
                    <p class="text-xs text-[#7A6B60] leading-relaxed">
                        Nikmati kemudahan berbelanja buku dengan pembayaran terverifikasi aman melalui QRIS instan, Transfer Bank, dan E-Wallet.
                    </p>
                </div>

                <div class="space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center font-bold">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-serif font-bold text-lg text-[#4A3222]">Rak Buku 3D Skeuomorfik</h4>
                    <p class="text-xs text-[#7A6B60] leading-relaxed">
                        Setiap buku yang Anda miliki tersusun rapi di rak kayu 3D interaktif berdasarkan masing-masing genre bacaannya.
                    </p>
                </div>

                <div class="space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-[#EFE4D6] text-[#7A5034] flex items-center justify-center font-bold">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-serif font-bold text-lg text-[#4A3222]">Buku Asli & Inventaris Akurat</h4>
                    <p class="text-xs text-[#7A6B60] leading-relaxed">
                        Seluruh inventaris buku dikelola dengan pelacakan stok terpisah secara real-time langsung dari penerbit terpercaya.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA TO CONTACT & SHOP -->
        <div class="text-center bg-[#F2E8DC] border border-[#E3D6C5] rounded-3xl p-8 sm:p-12 max-w-3xl mx-auto space-y-4">
            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[#4A3222]">Punya Pertanyaan atau Masukan?</h3>
            <p class="text-xs sm:text-sm text-[#7A6B60]">
                Tim kami siap membantu Anda menemukan buku yang Anda cari atau menerima pesan pertanyaan melalui form kontak.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="{{ route('contact') }}" class="w-full sm:w-auto px-6 py-3 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-semibold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Kirim Pesan ke Kami</span>
                </a>
                <a href="{{ route('shop') }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-[#FAF7F2] text-[#4A3222] text-xs font-semibold rounded-xl border border-[#D9CBB8] transition flex items-center justify-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Jelajahi Toko Buku</span>
                </a>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-[#EFE5D7] bg-[#FAF7F2] py-8 text-xs text-[#9C8B7E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© 2026 Readowl Bookstore • Standar BNSP Junior Web Developer</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-[#5F3D27]">Beranda</a>
                <span>•</span>
                <a href="{{ route('shop') }}" class="hover:text-[#5F3D27]">Toko</a>
                <span>•</span>
                <a href="{{ route('about') }}" class="hover:text-[#5F3D27] font-semibold text-[#7A5034]">Tentang Kami</a>
                <span>•</span>
                <a href="{{ route('contact') }}" class="hover:text-[#5F3D27]">Kontak</a>
            </div>
        </div>
    </footer>

</div>
@endsection
