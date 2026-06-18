@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="space-y-8"
     :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" 
     class="transition-all duration-700 ease-out">
    <!-- Hero / Welcome Header -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 p-6 sm:p-8 text-white shadow-xl shadow-slate-900/10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-300 text-sm mt-2 max-w-xl">Ini adalah panel manajemen perpustakaan. Kelola koleksi buku, pantau log peminjaman, dan verifikasi pengembalian dengan denda otomatis di sini.</p>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <a href="{{ route('books.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-indigo-950 shadow-sm hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Buku
            </a>
            <a href="{{ route('borrowings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600/30 px-4 py-2.5 text-sm font-semibold text-white border border-indigo-500/20 shadow-sm hover:bg-indigo-600/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
                Log Peminjaman
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        
        <!-- Total Buku -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50/50 blur-2xl transition-all group-hover:bg-blue-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-blue-600 transition-colors">Total Buku</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100/50 text-blue-600 shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $totalBooks }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Judul Terdaftar</span>
            </div>
        </div>

        <!-- Total Anggota -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-50/50 blur-2xl transition-all group-hover:bg-indigo-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-indigo-600 transition-colors">Anggota</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100/50 text-indigo-600 shadow-sm border border-indigo-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $totalUsers }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Pembaca Aktif</span>
            </div>
        </div>

        <!-- Peminjaman Aktif -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50/50 blur-2xl transition-all group-hover:bg-amber-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-amber-600 transition-colors">Sedang Dipinjam</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-50 to-amber-100/50 text-amber-600 shadow-sm border border-amber-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $activeBorrowings }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Buku di Luar</span>
            </div>
        </div>

        <!-- Pengembalian -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-50/50 blur-2xl transition-all group-hover:bg-emerald-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-emerald-600 transition-colors">Selesai Kembali</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 text-emerald-600 shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $totalReturned }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Transaksi Selesai</span>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-rose-50/50 blur-2xl transition-all group-hover:bg-rose-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-rose-600 transition-colors">Denda Terkumpul</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-rose-50 to-rose-100/50 text-rose-600 shadow-sm border border-rose-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M5.25 9h13.5m-10.5 5.25h7.5c.9 0 1.624.724 1.624 1.625v.875M9 14.25v2.25m6-2.25v2.25" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-rose-400">Rp{{ number_format($totalFines, 0, ',', '.') }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-2">Keterlambatan</span>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Left 2 Cols: Recent Activity -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm shadow-slate-100/40">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Aktivitas Peminjaman Terbaru</h2>
                        <p class="text-xs text-slate-500 mt-1">Daftar transaksi peminjaman buku terakhir</p>
                    </div>
                    <a href="{{ route('borrowings.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Lihat Semua &rarr;</a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    @if($latestBorrowings->isEmpty())
                        <div class="py-12 text-center text-slate-400 text-sm">Belum ada catatan transaksi peminjaman.</div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-50">
                                    <th class="py-3 pr-4">Anggota</th>
                                    <th class="py-3 px-4">Buku</th>
                                    <th class="py-3 px-4">Tgl Pinjam</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 pl-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @foreach($latestBorrowings as $borrowing)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3.5 pr-4 font-medium text-slate-900">
                                            {{ $borrowing->user->name }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700">
                                            {{ Str::limit($borrowing->book->title, 25) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500 text-xs">
                                            {{ $borrowing->borrow_date->format('d M Y') }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($borrowing->status === 'borrowed')
                                                @if(now()->startOfDay()->greaterThan($borrowing->return_date))
                                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700">Terlambat</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">Aktif</span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">Kembali</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 pl-4 text-right">
                                            @if($borrowing->status === 'borrowed')
                                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Pending Reservations + Low stock alerts + Info -->
        <div class="space-y-6">

            <!-- Reservasi Menunggu Konfirmasi -->
            <div class="rounded-2xl border border-blue-100 bg-blue-50/40 p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 border-b border-blue-100 pb-4 flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Reservasi Menunggu
                    </span>
                    @if($pendingReservations->isNotEmpty())
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-700">{{ $pendingReservations->count() }}</span>
                    @endif
                </h2>
                <div class="mt-4 space-y-2.5">
                    @if($pendingReservations->isEmpty())
                        <div class="py-5 text-center text-slate-400 text-xs">Tidak ada reservasi tertunda.</div>
                    @else
                        @foreach($pendingReservations as $res)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-blue-100 shadow-sm">
                                <div class="min-w-0 flex-1 pr-2">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $res->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ Str::limit($res->book->title, 24) }}</p>
                                    <p class="text-[10px] text-blue-500 mt-0.5">{{ $res->borrow_date->diffForHumans() }}</p>
                                </div>
                                <form action="{{ route('borrowings.confirm-pickup', $res) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-blue-500 transition-all shadow-sm whitespace-nowrap">
                                        Serahkan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                        <a href="{{ route('borrowings.index', ['status' => 'reserved']) }}" class="block text-center text-xs font-semibold text-blue-600 hover:text-blue-500 mt-2">
                            Lihat semua reservasi &rarr;
                        </a>
                    @endif
                </div>
            </div>

            <!-- Alert Stok Menipis -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm shadow-slate-100/40">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
                    Stok Buku Menipis
                </h2>

                <div class="mt-4 space-y-3">
                    @if($lowStockBooks->isEmpty())
                        <div class="py-6 text-center text-slate-400 text-xs">Semua buku stok cukup.</div>
                    @else
                        @foreach($lowStockBooks as $book)
                            <a href="{{ route('books.show', $book) }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100/80 hover:border-amber-200 transition-colors group">
                                <div class="min-w-0 flex-1 pr-3">
                                    <p class="text-xs font-bold text-slate-900 truncate group-hover:text-amber-700 transition-colors">{{ $book->title }}</p>
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $book->author }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-bold {{ $book->stock == 0 ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    Stok: {{ $book->stock }}
                                </span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Quick Info -->
            <div class="rounded-2xl bg-gradient-to-br from-indigo-50/50 to-violet-50/50 border border-indigo-100/50 p-5 shadow-sm">
                <h3 class="font-bold text-indigo-950 text-sm flex items-center gap-2 mb-3">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                    </svg>
                    Ketentuan Perpustakaan
                </h3>
                <ul class="space-y-1.5 text-xs text-indigo-900/80 leading-relaxed">
                    <li class="flex items-start gap-2"><span class="text-indigo-400 mt-0.5">•</span> Durasi peminjaman: <strong>{{ config('library.loan_days') }} hari</strong></li>
                    <li class="flex items-start gap-2"><span class="text-indigo-400 mt-0.5">•</span> Denda: <strong>Rp{{ number_format(config('library.fine_per_day'), 0, ',', '.') }}/hari</strong> keterlambatan</li>
                    <li class="flex items-start gap-2"><span class="text-indigo-400 mt-0.5">•</span> Batas pinjam: <strong>{{ config('library.max_loans') }} buku</strong> per anggota</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
