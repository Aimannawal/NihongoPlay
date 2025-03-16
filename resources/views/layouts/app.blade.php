<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NihongoPlay - Belajar Bahasa Jepang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <!-- Howler.js for Audio -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#10b981', 
                        'primary-dark': '#059669',
                        'primary-light': '#34d399', 
                        'secondary': '#4ade80', 
                        'accent': '#f59e0b', 
                        'dark': '#1f2937', 
                        'light': '#f9fafb', 
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .card-printable, .card-printable * {
                visibility: visible;
            }
            .card-printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-primary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('vocabularies.index') }}" class="flex-shrink-0 flex items-center">
                        <img class="h-20 w-auto" src="{{ asset('logo.png') }}" alt="NihongoPlay">
                    </a>
                </div>
                
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{ route('vocabularies.index') }}" class="px-3 py-2 rounded-md text-white font-medium hover:bg-primary-dark transition duration-150">Beranda</a>
                    <a href="{{ route('vocabularies.cards') }}" class="px-3 py-2 rounded-md text-white font-medium hover:bg-primary-dark transition duration-150">Kartu</a>
                    <a href="{{ route('vocabularies.create') }}" class="px-3 py-2 rounded-md text-white font-medium hover:bg-primary-dark transition duration-150">Tambah Kosakata</a>
                    <a href="{{ route('vocabularies.quiz') }}" class="px-3 py-2 rounded-md text-white font-medium hover:bg-primary-dark transition duration-150">Quiz</a>
                </div>
                
                <div class="flex md:hidden items-center">
                    <button type="button" class="mobile-menu-button text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mobile-menu hidden md:hidden bg-primary-dark">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('vocabularies.index') }}" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-primary transition duration-150">Beranda</a>
                <a href="{{ route('vocabularies.cards') }}" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-primary transition duration-150">Kartu</a>
                <a href="{{ route('vocabularies.create') }}" class="block px-3 py-2 rounded-md text-white font-medium hover:bg-primary transition duration-150">Tambah Kosakata</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} NihongoPlay - Aplikasi Belajar Bahasa Jepang</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
