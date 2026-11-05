@extends('layouts.base')

@section('title', 'Daftar Akun Baru - Readowl Bookstore')

@section('content')
<div class="min-h-screen bg-[#F5EFEB] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
            <div class="w-11 h-11 rounded-full bg-[#EFE4D6] flex items-center justify-center text-[#7A5034]">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2C8 2 4 5 4 10c0 4 2 8 8 12 6-4 8-8 8-12 0-5-4-8-8-8z"/>
                    <circle cx="9" cy="9" r="2.5" fill="#7A5034"/>
                    <circle cx="15" cy="9" r="2.5" fill="#7A5034"/>
                    <path d="m12 12 1 2-2 0 1-2"/>
                </svg>
            </div>
            <span class="font-serif text-3xl font-bold tracking-tight text-[#5F3D27]">Readowl</span>
        </a>
        <h2 class="mt-4 font-serif text-2xl font-bold text-[#4A3222]">Mulai Petualangan Membacamu</h2>
        <p class="mt-1 text-xs sm:text-sm text-[#7A6B60]">
            Buat akun untuk menyimpan rak bacaan dan menikmati koleksi buku pilihan.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-[#FAF7F2] py-8 px-6 sm:px-10 border border-[#E7DCCE] rounded-3xl shadow-lg relative">
            
            <!-- Error messages / Alert -->
            @if (isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center gap-1.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0 text-red-500"></i>
                            <span>{{ $error }}</span>
                        </p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-[#5F3D27] uppercase tracking-wider">Nama Lengkap</label>
                    <div class="mt-1.5 relative">
                        <i data-lucide="user" class="w-4 h-4 text-[#A8988B] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               required 
                               value="{{ old('name') }}"
                               placeholder="Contoh: Assya Pratama" 
                               class="w-full pl-10 pr-4 py-2.5 bg-white text-xs sm:text-sm text-[#4A3222] placeholder-[#B5A79B] border border-[#E0D3C1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#5F3D27] uppercase tracking-wider">Alamat Email</label>
                    <div class="mt-1.5 relative">
                        <i data-lucide="mail" class="w-4 h-4 text-[#A8988B] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="nama@email.com" 
                               class="w-full pl-10 pr-4 py-2.5 bg-white text-xs sm:text-sm text-[#4A3222] placeholder-[#B5A79B] border border-[#E0D3C1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-[#5F3D27] uppercase tracking-wider">Kata Sandi</label>
                    <div class="mt-1.5 relative">
                        <i data-lucide="lock" class="w-4 h-4 text-[#A8988B] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               placeholder="Minimal 6 karakter" 
                               class="w-full pl-10 pr-4 py-2.5 bg-white text-xs sm:text-sm text-[#4A3222] placeholder-[#B5A79B] border border-[#E0D3C1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#5F3D27] uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                    <div class="mt-1.5 relative">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#A8988B] absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               required 
                               placeholder="Ulangi kata sandi Anda" 
                               class="w-full pl-10 pr-4 py-2.5 bg-white text-xs sm:text-sm text-[#4A3222] placeholder-[#B5A79B] border border-[#E0D3C1] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#B0825B]/40 focus:border-[#B0825B] transition">
                    </div>
                </div>

                <!-- Terms notice -->
                <p class="text-[11px] text-[#8C7A6D]">
                    Dengan mendaftar, Anda menyetujui ketentuan layanan dan kebijakan privasi Readowl.
                </p>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-[#7A5034] hover:bg-[#5F3D27] text-white font-semibold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2 group">
                        <span>Buat Akun Saya</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition"></i>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 pt-6 border-t border-[#EFE5D7] text-center text-xs text-[#7A6B60]">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-[#7A5034] hover:underline">
                    Masuk di sini
                </a>
            </div>

            <!-- Back to Home -->
            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-[11px] text-[#A8988B] hover:text-[#5F3D27] inline-flex items-center gap-1">
                    <span>← Kembali ke Halaman Utama</span>
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
