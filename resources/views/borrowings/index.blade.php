@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="space-y-8 animate-fade-in">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                {{ Auth::user()->isAdmin() ? 'Log Peminjaman Seluruh Anggota' : 'Riwayat Peminjaman Saya' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ Auth::user()->isAdmin() ? 'Pantau dan kelola seluruh transaksi peminjaman buku' : 'Daftar lengkap buku yang pernah atau sedang Anda pinjam' }}
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/40">
        <form action="{{ route('borrowings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            @if(Auth::user()->isAdmin())
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama anggota atau judul buku..."
                        class="block w-full rounded-xl border border-slate-200 pl-10 pr-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all">
                </div>
            @endif
            <select name="status" class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all">
                <option value="">Semua Status</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reservasi Baru</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                <option value="returning" {{ request('status') === 'returning' ? 'selected' : '' }}>Sedang Dikembalikan</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-50 px-5 py-3 text-sm font-semibold text-indigo-600 hover:bg-indigo-100 transition-all">
                    Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('borrowings.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 border border-slate-200/60 hover:bg-slate-100 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="rounded-2xl border border-slate-100 bg-white shadow-sm shadow-slate-100/40 overflow-hidden">
        @if($borrowings->isEmpty())
            <div class="py-16 text-center text-slate-500">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900">Belum ada riwayat peminjaman</h3>
                <p class="mt-1 text-xs text-slate-500">Data peminjaman buku akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No</th>
                            @if(Auth::user()->isAdmin())
                                <th class="px-6 py-4">Anggota</th>
                            @endif
                            <th class="px-6 py-4">Judul Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Batas Kembali</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($borrowings as $index => $borrowing)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-xs font-medium">
                                    {{ $borrowings->firstItem() + $index }}
                                </td>
                                @if(Auth::user()->isAdmin())
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900 text-xs">{{ $borrowing->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $borrowing->user->email }}</div>
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900 text-xs">{{ Str::limit($borrowing->book->title, 30) }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $borrowing->book->author }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-xs">
                                    {{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-xs font-medium">
                                    {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    @if($borrowing->actual_return_date)
                                        <span class="text-slate-600">{{ $borrowing->actual_return_date->format('d M Y') }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($borrowing->status === 'reserved')
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100 animate-pulse">
                                            Reservasi Baru
                                        </span>
                                    @elseif($borrowing->status === 'canceled')
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                            Dibatalkan
                                        </span>
                                    @elseif($borrowing->status === 'borrowed')
                                        @if(now()->startOfDay()->greaterThan($borrowing->return_date))
                                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-100">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-100">
                                                Dipinjam
                                            </span>
                                        @endif
                                    @elseif($borrowing->status === 'returning')
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-100">
                                            Sedang Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($borrowing->fine > 0)
                                        <div>
                                            <span class="text-xs font-bold text-rose-600 block">Rp{{ number_format($borrowing->fine, 0, ',', '.') }}</span>
                                            @if($borrowing->fine_payment_status === 'paid')
                                                <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 uppercase tracking-wider">Lunas</span>
                                            @else
                                                <span class="text-[9px] font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100 uppercase tracking-wider">Belum Bayar</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium">
                                    <div class="flex flex-col sm:flex-row gap-1.5 justify-end items-end sm:items-center">
                                    @if(Auth::user()->isAdmin())
                                        @if($borrowing->status === 'reserved')
                                            <form action="{{ route('borrowings.confirm-pickup', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 hover:border-blue-600">
                                                    Serahkan Buku
                                                </button>
                                            </form>
                                            <form action="{{ route('borrowings.cancel', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200 transition-all shadow-sm border border-slate-200">
                                                    Batal
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'borrowed')
                                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100 hover:border-indigo-600">
                                                    Kembalikan (On-site)
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'returning')
                                            <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 hover:border-emerald-600">
                                                    Konfirmasi Penerimaan
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'returned' && $borrowing->fine_payment_status === 'unpaid')
                                            <form action="{{ route('borrowings.pay-fine', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100 hover:border-rose-600">
                                                    Pelunasan Denda
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Selesai</span>
                                        @endif
                                    @else
                                        @if($borrowing->status === 'reserved')
                                            <form action="{{ route('borrowings.cancel', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100 hover:border-rose-600">
                                                    Batalkan Reservasi
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'borrowed')
                                            <form action="{{ route('borrowings.request-return', $borrowing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100 hover:border-indigo-600">
                                                    Ajukan Pengembalian
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'returning')
                                            <span class="inline-flex items-center text-xs font-medium text-amber-600">
                                                <svg class="w-3.5 h-3.5 mr-1 animate-spin text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                Menunggu Konfirmasi
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">Selesai</span>
                                        @endif
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
