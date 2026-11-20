<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Readowl Bookstore - Temukan Inspirasi Membacamu')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..800;1,9..144,300..800&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN with Custom Config) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: {
                            50: '#FDFBF7',
                            100: '#FAF7F0',
                            200: '#F5EFEB',
                            300: '#EFE7DD',
                            400: '#E2D5C4',
                            500: '#D5C4AF',
                        },
                        amberWood: {
                            50: '#FBF7F2',
                            100: '#F4ECE1',
                            200: '#E7D8C5',
                            300: '#D7BEA2',
                            400: '#C59E7A',
                            500: '#B0825B',
                            600: '#946645',
                            700: '#7A5034',
                            800: '#5F3D27',
                            900: '#3D2719',
                        },
                        cocoa: {
                            500: '#8C6747',
                            600: '#724F33',
                            700: '#593B23',
                            800: '#3F2817',
                            900: '#2A1A0F',
                        }
                    },
                    fontFamily: {
                        serif: ['Fraunces', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
            color: #2D231E;
        }

        .font-serif {
            font-family: 'Fraunces', serif;
        }

        /* Bookshelf Realistic Ledge */
        .shelf-container {
            position: relative;
            padding-bottom: 24px;
        }

        .shelf-surface {
            height: 10px;
            background: linear-gradient(180deg, #F5ECE0 0%, #E9DECD 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 4px 4px 0 0;
            position: relative;
            z-index: 5;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .shelf-face {
            height: 14px;
            background: linear-gradient(180deg, #DFD4C3 0%, #D2C4AF 100%);
            border-bottom: 2px solid #B8A892;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 12px 18px -2px rgba(60, 42, 28, 0.14), 0 4px 6px -2px rgba(60, 42, 28, 0.08);
            position: relative;
            z-index: 4;
        }

        /* Realistic Book Standing Look */
        .book-wrapper {
            position: relative;
            z-index: 10;
            display: inline-block;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: bottom center;
        }

        .book-wrapper:hover {
            transform: translateY(-10px) scale(1.03);
            z-index: 20;
        }

        .book-cover {
            box-shadow: -4px 6px 14px rgba(45, 30, 20, 0.22), 0 2px 4px rgba(45, 30, 20, 0.12);
            position: relative;
            overflow: hidden;
            border-radius: 3px 6px 6px 3px;
        }

        /* Subtle spine highlight */
        .book-cover::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 8px;
            background: linear-gradient(90deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0) 40%, rgba(0,0,0,0.2) 100%);
            pointer-events: none;
        }

        /* Alpine Cloak to prevent FOUC / flickering modals on page reload */
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F4ECE1;
        }
        ::-webkit-scrollbar-thumb {
            background: #D2C4AF;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #B0825B;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-[#FAF8F5] text-[#2D231E] antialiased">
    @yield('content')

    @auth
        @if(request()->routeIs('admin*'))
            <!-- ADMIN LOGOUT CONFIRMATION MODAL (Dark Theme) -->
            <div x-data="{ logoutModalOpen: false }" 
                 @open-logout-modal.window="logoutModalOpen = true; $nextTick(() => window.lucide && lucide.createIcons())"
                 x-cloak>
                <div x-show="logoutModalOpen" 
                     style="display: none;"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                     @keydown.escape.window="logoutModalOpen = false">
                    
                    <div class="bg-[#1C1915] border border-red-500/30 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center"
                         @click.away="logoutModalOpen = false">
                        
                        <div class="w-14 h-14 rounded-full bg-red-950/70 border border-red-500/40 mx-auto flex items-center justify-center text-red-400 mb-4">
                            <i data-lucide="log-out" class="w-7 h-7"></i>
                        </div>

                        <h3 class="font-serif text-xl font-bold text-white">Keluar dari Panel Admin?</h3>
                        <p class="text-xs text-[#8E8070] mt-2 mb-6">
                            Apakah Anda yakin ingin mengakhiri sesi Administrator (<strong>{{ Auth::user()->name }}</strong>)? Anda harus login kembali untuk mengelola inventaris buku dan pesanan.
                        </p>

                        <div class="flex gap-3 justify-center">
                            <button type="button" 
                                    @click="logoutModalOpen = false" 
                                    class="px-5 py-2.5 text-xs font-semibold text-[#A69785] bg-[#26211C] hover:bg-[#332C24] rounded-xl transition">
                                Batal
                            </button>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center gap-1.5">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Ya, Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- USER LOGOUT CONFIRMATION MODAL (Warm Aesthetic) -->
            <div x-data="{ logoutModalOpen: false }" 
                 @open-logout-modal.window="logoutModalOpen = true; $nextTick(() => window.lucide && lucide.createIcons())"
                 x-cloak>
                <div x-show="logoutModalOpen" 
                     style="display: none;"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm"
                     @keydown.escape.window="logoutModalOpen = false">
                    
                    <div class="bg-[#FAF7F2] border border-[#E7DBCB] text-[#2D231E] rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center relative"
                         @click.away="logoutModalOpen = false">
                        
                        <div class="w-14 h-14 rounded-full bg-red-100 border border-red-300 mx-auto flex items-center justify-center text-red-600 mb-4 shadow-sm">
                            <i data-lucide="log-out" class="w-7 h-7"></i>
                        </div>

                        <h3 class="font-serif text-xl font-bold text-[#4A3222]">Keluar dari Akun?</h3>
                        <p class="text-xs text-[#7A6B60] mt-2 mb-6">
                            Apakah Anda yakin ingin keluar dari akun <strong>{{ Auth::user()->name }}</strong>? Anda harus masuk kembali untuk mengakses keranjang belanja dan rak buku pribadi Anda.
                        </p>

                        <div class="flex gap-3 justify-center">
                            <button type="button" 
                                    @click="logoutModalOpen = false" 
                                    class="px-5 py-2.5 text-xs font-semibold text-[#5F3D27] bg-[#EFE4D6] hover:bg-[#E5D7C5] rounded-xl transition">
                                Batal
                            </button>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center gap-1.5">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Ya, Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <script>
        const initLucide = () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        };
        document.addEventListener('DOMContentLoaded', initLucide);
        document.addEventListener('alpine:initialized', initLucide);
        window.addEventListener('click', () => setTimeout(initLucide, 50));

        // Invalidate back-forward cache after logout
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
