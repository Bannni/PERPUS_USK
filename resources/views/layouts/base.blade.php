<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
