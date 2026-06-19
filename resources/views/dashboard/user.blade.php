@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="space-y-8"
     :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" 
     class="transition-all duration-700 ease-out">
    <!-- Hero / Welcome Header -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-300 text-sm mt-2 max-w-xl">Selamat datang di perpustakaan digital SIPUS. Temukan buku favoritmu, pinjam secara instan, dan pantau waktu pengembalian dengan mudah.</p>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-950 shadow-sm hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                </svg>
                Cari & Pinjam Buku
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        
        <!-- Peminjaman Aktif -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-50/50 blur-2xl transition-all group-hover:bg-indigo-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-indigo-600 transition-colors">Peminjaman Aktif</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100/50 text-indigo-600 shadow-sm border border-indigo-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $activeBorrowingsCount }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Buku sedang dibaca</span>
            </div>
        </div>

        <!-- Riwayat Selesai -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-50/50 blur-2xl transition-all group-hover:bg-emerald-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-emerald-600 transition-colors">Buku Dikembalikan</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 text-emerald-600 shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">{{ $historyCount }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-1">Total riwayat baca</span>
            </div>
        </div>

        <!-- Total Denda Saya -->
        <div class="relative overflow-hidden rounded-2xl border border-white/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:bg-white group cursor-default">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-rose-50/50 blur-2xl transition-all group-hover:bg-rose-100/50 group-hover:scale-150"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-sm font-semibold text-slate-500 group-hover:text-rose-600 transition-colors">Denda Saya</span>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-rose-50 to-rose-100/50 text-rose-600 shadow-sm border border-rose-100 group-hover:scale-110 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <span class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-rose-400">Rp{{ number_format($myFines, 0, ',', '.') }}</span>
                <span class="block text-xs font-medium text-slate-400 mt-2">Denda Keterlambatan</span>
            </div>
        </div>
    </div>

    <!-- Active Borrowings Table & Books Rec -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Left: My Active Loans -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm shadow-slate-100/40">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Buku Yang Sedang Anda Pinjam</h2>
                        <p class="text-xs text-slate-500 mt-1">Kembalikan tepat waktu sebelum jatuh tempo untuk menghindari denda</p>
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto">
                    @if($myActiveBorrowings->isEmpty())
                        <div class="py-12 text-center text-slate-400 text-sm">
                            <p class="font-medium text-slate-500">Anda tidak sedang meminjam buku.</p>
                            <a href="{{ route('books.index') }}" class="text-indigo-600 font-semibold text-xs mt-2 inline-block hover:underline">Pinjam buku pertamamu &rarr;</a>
                        </div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-50">
                                    <th class="py-3 pr-4">Judul Buku</th>
                                    <th class="py-3 px-4">Tgl Pinjam</th>
                                    <th class="py-3 px-4">Batas Kembali</th>
                                    <th class="py-3 pl-4 text-right">Status Sisa Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @foreach($myActiveBorrowings as $borrowing)
                                    @php
                                        $today = now()->startOfDay();
                                        $dueDate = $borrowing->return_date ? $borrowing->return_date->startOfDay() : null;
                                        $daysLeft = $dueDate ? $today->diffInDays($dueDate, false) : null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 pr-4">
                                            <div class="font-semibold text-slate-900 text-xs">{{ $borrowing->book->title }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $borrowing->book->author }}</div>
                                        </td>
                                        <td class="py-4 px-4 text-slate-500 text-xs">
                                            {{ $borrowing->status === 'reserved' ? 'Belum Diambil' : ($borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-') }}
                                        </td>
                                        <td class="py-4 px-4 text-slate-600 text-xs font-medium">
                                            {{ $borrowing->status === 'reserved' ? 'Tenggat 24 Jam' : ($borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-') }}
                                        </td>
                                        <td class="py-4 pl-4 text-right">
                                            @if($borrowing->status === 'reserved')
                                                <div class="flex items-center justify-end gap-2">
                                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100 animate-pulse">
                                                        Siap Diambil
                                                    </span>
                                                    <form action="{{ route('borrowings.cancel', $borrowing) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-50 px-2 py-1 text-[10px] font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100">
                                                            Batal
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($borrowing->status === 'returning')
                                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-100">
                                                    Proses Kembali
                                                </span>
                                            @elseif($borrowing->status === 'borrowed')
                                                <div class="flex items-center justify-end gap-3">
                                                    @if($daysLeft < 0)
                                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-100">
                                                            Terlambat {{ abs($daysLeft) }} hari
                                                        </span>
                                                    @elseif($daysLeft == 0)
                                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-100">
                                                            Hari Terakhir!
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-100">
                                                            {{ $daysLeft }} hari lagi
                                                        </span>
                                                    @endif
                                                    
                                                    <form action="{{ route('borrowings.request-return', $borrowing) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100">
                                                            Kembalikan
                                                        </button>
                                                    </form>
                                                </div>
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

        <!-- Right: Recommended Books -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm shadow-slate-100/40">
                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4">
                    Rekomendasi Buku Baru
                </h2>
                
                <div class="mt-4 space-y-4">
                    @if($availableBooks->isEmpty())
                        <div class="py-6 text-center text-slate-400 text-xs">Belum ada buku tersedia.</div>
                    @else
                        @foreach($availableBooks as $book)
                            <div class="flex gap-3 items-center p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-indigo-100 transition-colors">
                                <!-- Cover image placeholder/rendered -->
                                <div class="flex-shrink-0 h-16 w-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold overflow-hidden shadow-sm">
                                    @if($book->cover_image)
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-xs">{{ Str::limit($book->title, 2, '') }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $book->title }}</p>
                                    <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $book->author }}</p>
                                    <span class="inline-block mt-1 text-[9px] font-semibold text-indigo-600 bg-indigo-50 rounded px-1.5 py-0.5">Stok: {{ $book->stock }}</span>
                                </div>
                                <div>
                                    <form action="{{ route('borrowings.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('books.index') }}" class="block text-center text-xs font-semibold text-indigo-600 hover:text-indigo-500 mt-2">Lihat Semua Buku &rarr;</a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
