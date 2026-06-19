<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital - Jendela Dunia</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-2deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 7s ease-in-out infinite 1s; }
        
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800" x-data="{ showQuickView: false, activeBook: {}, toastVisible: false, toastMessage: '' }">

    <!-- Global Toast for Footer Newsletter -->
    <div x-show="toastVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed bottom-4 right-4 z-[60] bg-slate-900 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3" style="display: none;">
        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <p x-text="toastMessage" class="font-medium text-sm"></p>
        <button @click="toastVisible = false" class="text-slate-400 hover:text-white ml-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/70 backdrop-blur-lg border-b border-white/20 shadow-sm transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 transition-all duration-300" :class="{ 'h-16': scrolled }">
                <div class="flex-shrink-0 flex items-center gap-3 group cursor-pointer" @click="window.scrollTo({top: 0, behavior: 'smooth'})">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">SIPUS</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors relative group py-2">
                        Beranda
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#fitur" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors relative group py-2">
                        Fitur
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#cara-kerja" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors relative group py-2">
                        Cara Kerja
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#koleksi" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors relative group py-2">
                        Koleksi
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold overflow-hidden border border-slate-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 px-6 py-2.5 rounded-full shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5 border border-indigo-400/20">Daftar Gratis</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden relative bg-grid-pattern bg-white">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] rounded-full bg-indigo-400/10 blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] rounded-full bg-violet-400/10 blur-[80px]"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-indigo-50 text-indigo-700 font-semibold text-sm mb-6 border border-indigo-100 shadow-sm animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Perpustakaan Digital Generasi Baru
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                        Buka Jendela <br class="hidden lg:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Dunia Literasi</span>
                    </h1>
                    <p class="text-lg text-slate-600 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Akses ribuan buku digital berkualitas, mulai dari fiksi, akademik, hingga literatur anak. Perluas wawasanmu kapan saja dan di mana saja.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 text-base font-bold rounded-full text-white bg-slate-900 hover:bg-slate-800 shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-1 gap-2">
                            Mulai Membaca 
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="#koleksi" class="inline-flex justify-center items-center px-8 py-4 border-2 border-slate-200 text-base font-bold rounded-full text-slate-700 bg-white hover:border-indigo-200 hover:bg-indigo-50 transition-all gap-2">
                            Lihat Koleksi
                        </a>
                    </div>
                    
                    <div class="mt-12 flex items-center justify-center lg:justify-start gap-6 text-sm font-medium text-slate-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Akses 24/7
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Gratis Pendaftaran
                        </div>
                    </div>
                </div>

                <!-- Right Content (Interactive Book Stack) -->
                <div class="hidden lg:flex relative h-[500px] items-center justify-center perspective-1000">
                    <!-- Decorative Elements -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 to-violet-500/10 rounded-full blur-3xl animate-pulse-glow"></div>
                    
                    <!-- Book 1 (Back Left) -->
                    <div class="absolute w-48 h-64 bg-white rounded-r-xl rounded-l-sm shadow-2xl border-l-4 border-slate-800 transform -rotate-12 -translate-x-20 -translate-y-10 opacity-80 animate-float-delayed flex flex-col">
                        <div class="h-3/4 bg-slate-800 rounded-tr-xl flex items-center justify-center text-white/20 p-4 text-center border-b border-slate-700">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        </div>
                        <div class="h-1/4 bg-slate-900 rounded-br-xl"></div>
                    </div>
                    
                    <!-- Book 2 (Back Right) -->
                    <div class="absolute w-48 h-64 bg-white rounded-r-xl rounded-l-sm shadow-2xl border-l-4 border-blue-600 transform rotate-12 translate-x-24 translate-y-8 opacity-90 animate-float flex flex-col" style="animation-delay: 1.5s;">
                        <div class="h-full bg-gradient-to-b from-blue-500 to-indigo-600 rounded-r-xl rounded-l-sm flex items-center justify-center p-4">
                            <div class="w-full h-full border border-white/20 rounded-lg flex flex-col items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-white/20 mb-2"></div>
                                <div class="w-16 h-2 bg-white/20 rounded"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Book (Center Front) -->
                    <div class="absolute w-64 h-80 bg-white rounded-r-xl rounded-l-md shadow-2xl shadow-indigo-500/40 border-l-8 border-slate-900 transform rotate-3 animate-float z-10 flex flex-col overflow-hidden group cursor-pointer hover:rotate-0 transition-transform duration-500">
                        <!-- Book Cover Design -->
                        <div class="h-full w-full bg-gradient-to-br from-indigo-900 via-slate-900 to-violet-900 flex flex-col relative">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10"></div>
                            <div class="p-6 flex flex-col h-full z-10">
                                <div class="flex-grow">
                                    <span class="text-indigo-300 text-xs font-bold uppercase tracking-widest mb-2 block">Bestseller</span>
                                    <h3 class="text-white font-extrabold text-2xl leading-tight mb-2">Desain <br>Antarmuka <br>Modern</h3>
                                    <p class="text-slate-400 text-xs">Panduan komprehensif UI/UX</p>
                                </div>
                                <div class="mt-auto">
                                    <div class="h-px w-full bg-white/20 mb-4"></div>
                                    <p class="text-white/80 font-semibold text-sm">Tim Developer</p>
                                </div>
                            </div>
                            
                            <!-- Glossy overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent w-1/3 pointer-events-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Stats Section -->
    <section class="py-12 bg-white border-y border-slate-100 relative z-20 -mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div x-data="{ count: 0, target: {{ $totalBooks ?? 0 }} }" x-intersect.once="let i = setInterval(() => { count += Math.ceil(target/40); if(count >= target) { count = target; clearInterval(i); } }, 30)" class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900" x-text="count">0</p>
                        <p class="text-sm font-medium text-slate-500">Total Buku</p>
                    </div>
                </div>
                <!-- Stat 2 -->
                <div x-data="{ count: 0, target: {{ $totalUsers ?? 0 }} }" x-intersect.once="let i = setInterval(() => { count += Math.ceil(target/40); if(count >= target) { count = target; clearInterval(i); } }, 30)" class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900" x-text="count">0</p>
                        <p class="text-sm font-medium text-slate-500">Anggota Aktif</p>
                    </div>
                </div>
                <!-- Stat 3 -->
                <div x-data="{ count: 0, target: {{ $totalBorrowings ?? 0 }} }" x-intersect.once="let i = setInterval(() => { count += Math.ceil(target/40); if(count >= target) { count = target; clearInterval(i); } }, 30)" class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900" x-text="count">0</p>
                        <p class="text-sm font-medium text-slate-500">Buku Dipinjam</p>
                    </div>
                </div>
                <!-- Stat 4 -->
                <div x-data="{ count: 0, target: {{ $totalCategories ?? 0 }} }" x-intersect.once="let i = setInterval(() => { count += Math.ceil(target/40); if(count >= target) { count = target; clearInterval(i); } }, 30)" class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6 flex items-center gap-5 hover:-translate-y-1 hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900" x-text="count">0</p>
                        <p class="text-sm font-medium text-slate-500">Kategori</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-indigo-600 font-bold uppercase tracking-wider text-sm mb-2 block">Keunggulan Kami</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Mengapa Memilih SIPUS?</h2>
                <p class="text-lg text-slate-600">Berbagai fitur unggulan yang dirancang khusus untuk memberikan kenyamanan membaca dan belajar terbaik bagi Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-lg transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Koleksi Lengkap</h3>
                    <p class="text-slate-600 leading-relaxed">Ribuan buku dari berbagai genre, akademik, fiksi, hingga literatur populer tersedia untuk dipinjam kapan saja.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 group-hover:shadow-lg transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Akses Multi Perangkat</h3>
                    <p class="text-slate-600 leading-relaxed">Baca buku favorit Anda langsung dari perangkat smartphone, tablet, atau komputer secara responsif dan mudah.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-violet-100 to-violet-50 text-violet-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-lg transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Proses Instan</h3>
                    <p class="text-slate-600 leading-relaxed">Proses pendaftaran, peminjaman, dan pengembalian buku sepenuhnya otomatis tanpa antrian atau birokrasi rumit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section (Interactive) -->
    <section id="cara-kerja" class="py-24 bg-white border-t border-slate-100" x-data="{ step: 1 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">Cara Kerja SIPUS</h2>
                <p class="text-lg text-slate-600">Hanya butuh 4 langkah mudah untuk mulai membaca.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Steps List -->
                <div class="space-y-4">
                    <!-- Step 1 -->
                    <div @click="step = 1" class="p-6 rounded-2xl cursor-pointer transition-all duration-300 border-2"
                         :class="step === 1 ? 'bg-indigo-50 border-indigo-200 shadow-md' : 'bg-white border-transparent hover:bg-slate-50'">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0"
                                 :class="step === 1 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'bg-slate-100 text-slate-500'">1</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2" :class="step === 1 ? 'text-indigo-900' : 'text-slate-700'">Daftar Akun Gratis</h4>
                                <p class="text-slate-600 leading-relaxed">Buat akun menggunakan email Anda secara gratis dan instan. Tidak ada biaya keanggotaan tersembunyi.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div @click="step = 2" class="p-6 rounded-2xl cursor-pointer transition-all duration-300 border-2"
                         :class="step === 2 ? 'bg-indigo-50 border-indigo-200 shadow-md' : 'bg-white border-transparent hover:bg-slate-50'">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0"
                                 :class="step === 2 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'bg-slate-100 text-slate-500'">2</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2" :class="step === 2 ? 'text-indigo-900' : 'text-slate-700'">Cari & Pilih Buku</h4>
                                <p class="text-slate-600 leading-relaxed">Jelajahi ratusan koleksi buku digital kami. Gunakan fitur pencarian untuk menemukan literatur spesifik.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div @click="step = 3" class="p-6 rounded-2xl cursor-pointer transition-all duration-300 border-2"
                         :class="step === 3 ? 'bg-indigo-50 border-indigo-200 shadow-md' : 'bg-white border-transparent hover:bg-slate-50'">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0"
                                 :class="step === 3 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'bg-slate-100 text-slate-500'">3</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2" :class="step === 3 ? 'text-indigo-900' : 'text-slate-700'">Ajukan Peminjaman</h4>
                                <p class="text-slate-600 leading-relaxed">Pilih buku yang tersedia dan klik pinjam. Proses peminjaman disetujui secara otomatis oleh sistem.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div @click="step = 4" class="p-6 rounded-2xl cursor-pointer transition-all duration-300 border-2"
                         :class="step === 4 ? 'bg-indigo-50 border-indigo-200 shadow-md' : 'bg-white border-transparent hover:bg-slate-50'">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0"
                                 :class="step === 4 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'bg-slate-100 text-slate-500'">4</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2" :class="step === 4 ? 'text-indigo-900' : 'text-slate-700'">Baca Sepuasnya</h4>
                                <p class="text-slate-600 leading-relaxed">Buku sudah masuk ke perpustakaan pribadi Anda. Selamat membaca dan memperluas wawasan!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Display -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 shadow-inner h-96 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-pattern opacity-50"></div>
                    
                    <!-- Preview 1 -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="relative z-10 w-full max-w-sm bg-white p-6 rounded-2xl shadow-xl border border-slate-100">
                        <div class="h-8 bg-slate-100 rounded mb-6 w-1/2 mx-auto"></div>
                        <div class="space-y-4">
                            <div class="h-10 bg-slate-50 border border-slate-200 rounded-lg w-full"></div>
                            <div class="h-10 bg-slate-50 border border-slate-200 rounded-lg w-full"></div>
                            <div class="h-10 bg-indigo-600 rounded-lg w-full mt-4"></div>
                        </div>
                    </div>
                    
                    <!-- Preview 2 -->
                    <div x-show="step === 2" style="display:none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="relative z-10 w-full max-w-sm grid grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-xl shadow-lg border border-slate-100 h-40 flex flex-col">
                            <div class="bg-slate-200 rounded-lg h-20 w-full mb-2"></div>
                            <div class="h-2 bg-slate-200 w-3/4 rounded mb-1"></div>
                            <div class="h-2 bg-slate-100 w-1/2 rounded"></div>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow-lg border border-slate-100 h-40 flex flex-col">
                            <div class="bg-slate-200 rounded-lg h-20 w-full mb-2"></div>
                            <div class="h-2 bg-slate-200 w-3/4 rounded mb-1"></div>
                            <div class="h-2 bg-slate-100 w-1/2 rounded"></div>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow-lg border border-slate-100 h-40 flex flex-col">
                            <div class="bg-indigo-100 rounded-lg h-20 w-full mb-2"></div>
                            <div class="h-2 bg-slate-200 w-3/4 rounded mb-1"></div>
                            <div class="h-2 bg-slate-100 w-1/2 rounded"></div>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow-lg border border-slate-100 h-40 flex flex-col">
                            <div class="bg-slate-200 rounded-lg h-20 w-full mb-2"></div>
                            <div class="h-2 bg-slate-200 w-3/4 rounded mb-1"></div>
                            <div class="h-2 bg-slate-100 w-1/2 rounded"></div>
                        </div>
                    </div>

                    <!-- Preview 3 -->
                    <div x-show="step === 3" style="display:none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative z-10 w-full max-w-sm bg-white p-6 rounded-2xl shadow-xl border border-slate-100 text-center">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Berhasil Dipinjam</h4>
                        <p class="text-sm text-slate-500 mb-6">Buku telah ditambahkan ke rak Anda.</p>
                        <div class="h-10 bg-emerald-500 rounded-full w-3/4 mx-auto"></div>
                    </div>

                    <!-- Preview 4 -->
                    <div x-show="step === 4" style="display:none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="relative z-10 w-full max-w-sm bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex gap-4 items-center">
                        <div class="w-24 h-32 bg-slate-200 rounded flex-shrink-0 shadow-inner"></div>
                        <div class="w-full space-y-3">
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                            <div class="h-3 bg-slate-100 rounded w-5/6"></div>
                            <div class="h-3 bg-slate-100 rounded w-4/6"></div>
                            <div class="h-3 bg-slate-100 rounded w-full"></div>
                            <div class="h-3 bg-slate-100 rounded w-3/4"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Books Collections Sections -->
    <div id="koleksi" class="bg-slate-50 py-24 border-t border-slate-200">
        <!-- New Books Carousel Section -->
        @if(isset($newBooks) && $newBooks->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20" x-data="{ 
            interval: null,
            startAutoScroll() {
                this.interval = setInterval(() => {
                    let slider = this.$refs.slider;
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: 320, behavior: 'smooth' });
                    }
                }, 3000);
            },
            stopAutoScroll() { clearInterval(this.interval); },
            scrollNext() { this.$refs.slider.scrollBy({ left: 320, behavior: 'smooth' }) }, 
            scrollPrev() { this.$refs.slider.scrollBy({ left: -320, behavior: 'smooth' }) } 
        }" x-init="startAutoScroll()" @mouseenter="stopAutoScroll()" @mouseleave="startAutoScroll()" @touchstart="stopAutoScroll()" @touchend="startAutoScroll()">
            <div class="flex flex-col sm:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Baru Ditambahkan</h2>
                    <p class="text-slate-600 text-lg">Koleksi buku segar yang siap menemani harimu.</p>
                </div>
                <div class="flex gap-2">
                    <button @click="scrollPrev" class="w-12 h-12 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="scrollNext" class="w-12 h-12 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Carousel Container -->
            <div x-ref="slider" class="flex gap-6 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-8 pt-4 px-4 -mx-4">
                @foreach($newBooks as $book)
                <div class="snap-start shrink-0 w-64 bg-white rounded-2xl shadow-sm hover:shadow-2xl border border-slate-100 transition-all duration-300 transform hover:-translate-y-2 group flex flex-col cursor-pointer"
                     data-book="{{ json_encode(['title' => $book->title, 'author' => $book->author, 'publisher' => $book->publisher, 'category' => $book->category?->name ?? 'Tanpa Kategori', 'cover' => $book->cover_url ?? '', 'borrowings_count' => $book->borrowings_count ?? 0, 'year' => $book->year, 'stock' => $book->stock]) }}"
                     @click="activeBook = JSON.parse($el.dataset.book); showQuickView = true">
                    <div class="h-80 w-full bg-slate-100 overflow-hidden relative rounded-t-2xl">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100 group-hover:scale-105 transition-transform duration-500">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">Baru</div>
                        <!-- Quick View Overlay on Hover -->
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                            <span class="px-4 py-2 bg-white/20 text-white font-semibold rounded-lg backdrop-blur-md border border-white/30">Quick View</span>
                        </div>
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            @if($book->category)
                            <span class="text-xs font-bold text-indigo-600 mb-2 block tracking-wider uppercase">{{ $book->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-lg text-slate-900 leading-tight mb-1 line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-sm text-slate-500 mb-4">{{ $book->author }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Popular Books Carousel Section -->
        @if(isset($popularBooks) && $popularBooks->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ 
            interval: null,
            startAutoScroll() {
                this.interval = setInterval(() => {
                    let slider = this.$refs.slider;
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        slider.scrollBy({ left: 320, behavior: 'smooth' });
                    }
                }, 3000);
            },
            stopAutoScroll() { clearInterval(this.interval); },
            scrollNext() { this.$refs.slider.scrollBy({ left: 320, behavior: 'smooth' }) }, 
            scrollPrev() { this.$refs.slider.scrollBy({ left: -320, behavior: 'smooth' }) } 
        }" x-init="startAutoScroll()" @mouseenter="stopAutoScroll()" @mouseleave="startAutoScroll()" @touchstart="stopAutoScroll()" @touchend="startAutoScroll()">
            <div class="flex flex-col sm:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Buku Terpopuler</h2>
                    <p class="text-slate-600 text-lg">Pilihan favorit para pembaca minggu ini.</p>
                </div>
                <div class="flex gap-2">
                    <button @click="scrollPrev" class="w-12 h-12 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:bg-blue-50 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="scrollNext" class="w-12 h-12 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:bg-blue-50 hover:shadow-md transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Carousel Container -->
            <div x-ref="slider" class="flex gap-6 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-8 pt-4 px-4 -mx-4">
                @foreach($popularBooks as $book)
                <div class="snap-start shrink-0 w-64 bg-white rounded-2xl shadow-sm hover:shadow-2xl border border-slate-100 transition-all duration-300 transform hover:-translate-y-2 group flex flex-col cursor-pointer"
                     data-book="{{ json_encode(['title' => $book->title, 'author' => $book->author, 'publisher' => $book->publisher, 'category' => $book->category?->name ?? 'Tanpa Kategori', 'cover' => $book->cover_url ?? '', 'borrowings_count' => $book->borrowings_count ?? 0, 'year' => $book->year, 'stock' => $book->stock]) }}"
                     @click="activeBook = JSON.parse($el.dataset.book); showQuickView = true">
                    <div class="h-80 w-full bg-slate-100 overflow-hidden relative rounded-t-2xl">
                        <div class="absolute top-0 left-0 w-12 h-12 bg-amber-500 text-white flex items-center justify-center font-black text-xl rounded-br-2xl z-10 shadow-lg">
                            #{{ $loop->iteration }}
                        </div>
                        @if($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100 group-hover:scale-105 transition-transform duration-500">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm z-20">
                            <span class="px-4 py-2 bg-white/20 text-white font-semibold rounded-lg backdrop-blur-md border border-white/30">Quick View</span>
                        </div>
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            @if($book->category)
                            <span class="text-xs font-bold text-blue-600 mb-2 block tracking-wider uppercase">{{ $book->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-lg text-slate-900 leading-tight mb-1 line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-sm text-slate-500 mb-3">{{ $book->author }}</p>
                            <div class="flex items-center gap-1.5 text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-1 rounded-md inline-flex border border-amber-100">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                {{ $book->borrowings_count ?? 0 }} Dipinjam
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    <!-- Call to Action -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-indigo-800 to-violet-900"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse-glow"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-violet-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse-glow" style="animation-delay: 2s;"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Mulai Perjalanan Literasi Anda Hari Ini</h2>
            <p class="text-indigo-200 text-xl mb-10 max-w-2xl mx-auto">Jadilah bagian dari komunitas pembaca kami dan akses tak terbatas ke semua koleksi e-book serta buku fisik.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 bg-white text-indigo-900 font-bold text-lg px-10 py-5 rounded-full shadow-2xl hover:bg-indigo-50 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-1">
                Buat Akun Gratis 
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <!-- Comprehensive Footer -->
    <footer class="bg-slate-950 pt-20 pb-10 text-slate-300 relative border-t border-slate-900 overflow-hidden">
        <!-- Glow accents behind footer -->
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
                
                <!-- Column 1: Brand & Info -->
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight text-white">SIPUS</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Sistem Informasi Perpustakaan cerdas yang menjembatani Anda dengan ribuan ilmu. Menyediakan layanan akses buku digital interaktif untuk generasi masa kini.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-500 transition-all">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 hover:border-pink-500 transition-all">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-400 hover:border-blue-400 transition-all">
                            <span class="sr-only">Twitter</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="lg:col-span-2">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-slate-400 hover:text-white hover:pl-2 transition-all duration-300 block">Beranda</a></li>
                        <li><a href="#fitur" class="text-slate-400 hover:text-white hover:pl-2 transition-all duration-300 block">Fitur</a></li>
                        <li><a href="#cara-kerja" class="text-slate-400 hover:text-white hover:pl-2 transition-all duration-300 block">Panduan</a></li>
                        <li><a href="#koleksi" class="text-slate-400 hover:text-white hover:pl-2 transition-all duration-300 block">Koleksi Buku</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Hours -->
                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Kontak & Layanan</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Literasi Bangsa No. 123,<br>Kota Pendidikan, Indonesia 12345</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:halo@perpusdigital.id" class="hover:text-white transition-colors">halo@perpusdigital.id</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 555-0123</span>
                        </li>
                        <li class="flex items-start gap-3 mt-4 pt-4 border-t border-slate-800">
                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <strong class="text-slate-300 block mb-1">Jam Operasional:</strong>
                                Senin - Jumat: 08:00 - 16:00<br>
                                Sabtu: 09:00 - 14:00 (Minggu Tutup)
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Berlangganan Info</h4>
                    <p class="text-slate-400 text-sm mb-4">Dapatkan update buku terbaru dan info literasi menarik langsung ke inbox Anda.</p>
                    <form @submit.prevent="toastMessage = 'Terima kasih telah berlangganan newsletter kami!'; toastVisible = true; setTimeout(() => toastVisible = false, 4000); $event.target.reset();" class="relative">
                        <input type="email" placeholder="Alamat Email" required class="w-full bg-slate-900 border border-slate-700 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors pr-12">
                        <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 w-10 bg-indigo-600 hover:bg-indigo-500 text-white rounded-md flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 relative">
                <p class="text-sm text-slate-500">
                    &copy; {{ date('Y') }} SIPUS PerpusDigital. Hak cipta dilindungi.
                </p>
                <p class="text-sm text-slate-500">
                    Built with <span class="text-red-400">&#9829;</span> by
                    <span class="font-semibold text-slate-300">KhoerudinXYZ</span>
                    <span class="text-slate-600">&</span>
                    <span class="font-semibold text-indigo-400">Claude</span>
                </p>
                <div class="flex items-center gap-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat Ketentuan</a>
                </div>
                
                <!-- Scroll to Top Button -->
                <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 500" x-show="show" x-transition class="absolute right-0 -top-6 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 hover:-translate-y-1 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
            </div>
        </div>
    </footer>

    <!-- Quick View Modal (Global) -->
    <template x-teleport="body">
        <div x-show="showQuickView" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background overlay -->
            <div x-show="showQuickView" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showQuickView = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="showQuickView" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-100">
                    
                    <button @click="showQuickView = false" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/80 backdrop-blur-sm border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-0">
                        <!-- Cover Image Side -->
                        <div class="md:col-span-2 bg-slate-100 relative min-h-[300px] md:min-h-full">
                            <template x-if="activeBook.cover">
                                <img :src="activeBook.cover" :alt="activeBook.title" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            <template x-if="!activeBook.cover">
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 bg-slate-200">
                                    <svg class="w-20 h-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="font-medium">Tanpa Sampul</span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Content Side -->
                        <div class="md:col-span-3 p-8 md:p-10 flex flex-col h-full bg-white">
                            <div class="mb-2">
                                <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold tracking-wider uppercase" x-text="activeBook.category"></span>
                            </div>
                            <h2 class="text-3xl font-extrabold text-slate-900 mb-2 leading-tight" x-text="activeBook.title"></h2>
                            <p class="text-lg text-slate-600 mb-6 font-medium" x-text="'Oleh ' + activeBook.author"></p>
                            
                            <div class="grid grid-cols-2 gap-4 mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Penerbit</span>
                                    <span class="block text-sm text-slate-800 font-medium" x-text="activeBook.publisher || '-'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tahun Terbit</span>
                                    <span class="block text-sm text-slate-800 font-medium" x-text="activeBook.year || '-'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jumlah Dipinjam</span>
                                    <span class="block text-sm text-slate-800 font-medium"><span x-text="activeBook.borrowings_count"></span> kali</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Ketersediaan</span>
                                    <template x-if="activeBook.stock > 0">
                                        <span class="inline-flex items-center gap-1.5 text-sm text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tersedia (<span x-text="activeBook.stock"></span>)
                                        </span>
                                    </template>
                                    <template x-if="activeBook.stock <= 0">
                                        <span class="inline-flex items-center gap-1.5 text-sm text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded border border-red-100">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Sedang Kosong
                                        </span>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-6 border-t border-slate-100 flex gap-4">
                                <a href="{{ route('login') }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-600/30 transition-all text-center">
                                    Pinjam Sekarang
                                </a>
                                <button @click="showQuickView = false" class="px-6 py-3.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

</body>
</html>
