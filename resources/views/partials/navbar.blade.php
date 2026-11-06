<!-- UNIFIED USER NAVBAR (READOWL BOOKSTORE) -->
<header class="border-b border-[#EBE1D3] bg-[#FAF7F2]/95 backdrop-blur-md sticky top-0 z-40 transition-colors"
        x-data="{ mobileNavOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Left: Brand Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-full bg-[#EFE4D6] group-hover:bg-[#E5D7C5] flex items-center justify-center text-[#7A5034] transition shadow-xs">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8 2 4 5 4 10c0 4 2 8 8 12 6-4 8-8 8-12 0-5-4-8-8-8z"/>
                        <circle cx="9" cy="9" r="2.5" fill="#7A5034"/>
                        <circle cx="15" cy="9" r="2.5" fill="#7A5034"/>
                        <path d="m12 12 1 2-2 0 1-2"/>
                    </svg>
                </div>
                <div>
                    <span class="font-serif text-2xl font-bold tracking-tight text-[#4A3222] group-hover:text-[#7A5034] transition block leading-none">
                        Readowl
                    </span>
                    <span class="text-[9px] uppercase tracking-widest text-[#9C8B7E] font-semibold">
                        Bookstore
                    </span>
                </div>
            </a>
        </div>

        <!-- Center: Primary Navigation Links (Desktop) -->
        <nav class="hidden md:flex items-center gap-1 sm:gap-2">
            <a href="{{ route('home') }}" 
               class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#F2EAE0]' }}">
                Beranda
            </a>

            <a href="{{ route('shop') }}" 
               class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('shop') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#F2EAE0]' }}">
                Toko Buku
            </a>

            <a href="{{ route('catalog') }}" 
               class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition flex items-center gap-1.5 {{ request()->routeIs('catalog', 'library') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#F2EAE0]' }}">
                <i data-lucide="book-open" class="w-4 h-4 text-[#7A5034]"></i>
                <span>Rak Buku (My Library)</span>
            </a>

            <a href="{{ route('about') }}" 
               class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('about') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#F2EAE0]' }}">
                Tentang Kami
            </a>

            <a href="{{ route('contact') }}" 
               class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition {{ request()->routeIs('contact*') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60] hover:text-[#4A3222] hover:bg-[#F2EAE0]' }}">
                Kontak
            </a>
        </nav>

        <!-- Right: Actions, Cart, Orders & Profile/Auth -->
        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                <!-- Cart Button with Counter -->
                <a href="{{ route('cart.index') }}" 
                   class="relative p-2 rounded-xl text-[#7A5034] hover:bg-[#EFE4D6] transition {{ request()->routeIs('cart.index') ? 'bg-[#EFE4D6]' : '' }}" 
                   title="Keranjang Belanja">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    @php
                        $navCartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                    @endphp
                    @if($navCartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[1.25rem] h-5 px-1 bg-[#7A5034] text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-xs">
                            {{ $navCartCount }}
                        </span>
                    @endif
                </a>

                <!-- Pesanan Saya -->
                <a href="{{ route('orders.index') }}" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('orders.index') ? 'bg-[#1C1815] text-white shadow-xs' : 'bg-[#EFE4D6] text-[#5F3D27] hover:bg-[#E5D7C5]' }}">
                    <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                    <span>Pesanan Saya</span>
                </a>

                <!-- User Profile Chip & Logout -->
                <div class="flex items-center gap-2 pl-1 sm:pl-2 border-l border-[#E5DACB]">
                    <div class="hidden lg:flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#7A5034] text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold text-[#4A3222] leading-tight max-w-[110px] truncate" title="{{ Auth::user()->name }}">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="block text-[10px] text-[#8C7A6D]">Pembaca</span>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 text-[#9C8B7E] hover:text-red-600 hover:bg-[#F2EAE0] rounded-xl transition" 
                                title="Keluar dari Akun">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold text-[#5F3D27] hover:bg-[#EFE4D6] rounded-xl transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-[#7A5034] hover:bg-[#5F3D27] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    Daftar
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <button type="button" 
                    @click="mobileNavOpen = !mobileNavOpen" 
                    class="md:hidden p-2 text-[#5F3D27] hover:bg-[#EFE4D6] rounded-xl transition"
                    title="Menu Navigasi">
                <i data-lucide="menu" class="w-5 h-5" x-show="!mobileNavOpen"></i>
                <i data-lucide="x" class="w-5 h-5" x-show="mobileNavOpen" style="display: none;"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Dropdown -->
    <div x-show="mobileNavOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-[#E8DDCE] bg-[#FAF7F2] px-4 py-3 space-y-1 shadow-lg"
         style="display: none;">
        
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('home') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
            Beranda
        </a>
        <a href="{{ route('shop') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('shop') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
            Toko Buku (Shop)
        </a>
        <a href="{{ route('catalog') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('catalog', 'library') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
            Rak Buku (My Library)
        </a>
        <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('about') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
            Tentang Kami
        </a>
        <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('contact*') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
            Kontak
        </a>

        @auth
            <div class="pt-2 border-t border-[#E8DDCE] space-y-1">
                <a href="{{ route('orders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('orders.index') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>Pesanan Saya</span>
                    </div>
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold {{ request()->routeIs('cart.index') ? 'bg-[#EFE5D7] text-[#4F3320]' : 'text-[#7A6B60]' }}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        <span>Keranjang Belanja</span>
                    </div>
                    @if(isset($navCartCount) && $navCartCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-[#7A5034] text-white text-[10px] font-bold">
                            {{ $navCartCount }}
                        </span>
                    @endif
                </a>
            </div>
        @endauth
    </div>
</header>
