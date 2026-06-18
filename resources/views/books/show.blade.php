@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div x-data="{ loaded: false, borrowing: false }" x-init="setTimeout(() => loaded = true, 80)"
     :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
     class="transition-all duration-700 ease-out space-y-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('books.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            Katalog Buku
        </a>
        <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
        <span class="text-slate-800 font-medium truncate max-w-xs">{{ $book->title }}</span>
    </nav>

    {{-- Main Detail Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-0">

            {{-- Cover Image Column --}}
            <div class="md:col-span-2 relative min-h-[400px] bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center overflow-hidden group">
                @if($book->cover_image)
                    <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-slate-900 to-violet-900 flex flex-col items-center justify-center p-8 text-center">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-4 left-4 w-32 h-32 rounded-full bg-white blur-3xl"></div>
                            <div class="absolute bottom-4 right-4 w-24 h-24 rounded-full bg-indigo-400 blur-2xl"></div>
                        </div>
                        <svg class="h-20 w-20 text-white/30 mb-4 relative z-10" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <p class="text-white/50 text-sm font-medium relative z-10">Sampul Tidak Tersedia</p>
                    </div>
                @endif

                {{-- Stock badge overlay --}}
                <div class="absolute top-4 left-4 z-10">
                    @if($book->stock > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 backdrop-blur-md px-3 py-1.5 text-xs font-bold text-white shadow-lg border border-emerald-400/50">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            Tersedia ({{ $book->stock }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/90 backdrop-blur-md px-3 py-1.5 text-xs font-bold text-white shadow-lg border border-rose-400/50">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            Habis Dipinjam
                        </span>
                    @endif
                </div>
            </div>

            {{-- Info Column --}}
            <div class="md:col-span-3 p-8 lg:p-10 flex flex-col">
                {{-- Category --}}
                @if($book->category)
                    <div class="mb-3">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 border border-indigo-100 px-3 py-1 text-xs font-bold text-indigo-600 tracking-wider uppercase">
                            {{ $book->category->name }}
                        </span>
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-3 tracking-tight">
                    {{ $book->title }}
                </h1>

                {{-- Author --}}
                <p class="text-lg text-slate-600 font-medium mb-6 flex items-center gap-2">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    {{ $book->author }}
                </p>

                {{-- Metadata Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-8 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Penerbit</span>
                        <span class="block text-sm font-semibold text-slate-800">{{ $book->publisher }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tahun Terbit</span>
                        <span class="block text-sm font-semibold text-slate-800">{{ $book->year }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">ISBN</span>
                        <span class="block text-sm font-semibold text-slate-800 font-mono">{{ $book->isbn ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Dipinjam</span>
                        <span class="block text-sm font-semibold text-slate-800">{{ $book->borrowings_count ?? 0 }} kali</span>
                    </div>
                </div>

                {{-- Batas Pinjam Info --}}
                <div class="flex items-start gap-3 p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 mb-8">
                    <svg class="h-5 w-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                    <p class="text-xs text-indigo-800 leading-relaxed">
                        Durasi peminjaman <strong>{{ config('library.loan_days') }} hari</strong> sejak buku diambil. Keterlambatan dikenakan denda <strong>Rp{{ number_format(config('library.fine_per_day'), 0, ',', '.') }}/hari</strong>.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-auto flex flex-wrap gap-3">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('books.edit', $book) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 hover:bg-indigo-500 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                            Edit Buku
                        </a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST"
                              x-data
                              @submit.prevent="if(confirm('Hapus buku ini secara permanen?')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all hover:scale-[1.02]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </button>
                        </form>
                    @else
                        @if($book->stock > 0)
                            <form action="{{ route('borrowings.store') }}" method="POST" class="flex-1 max-w-xs" x-data
                                  @submit="borrowing = true">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" :disabled="borrowing"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                                    <span x-show="!borrowing">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                    <svg x-show="borrowing" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span x-text="borrowing ? 'Memproses...' : 'Pinjam Sekarang'"></span>
                                </button>
                            </form>
                        @else
                            <button disabled
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-6 py-3.5 text-sm font-bold text-slate-400 cursor-not-allowed border border-slate-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                Stok Habis
                            </button>
                        @endif
                    @endif

                    <a href="{{ route('books.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-50 border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->isNotEmpty())
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900">Buku Terkait</h2>
            @if($book->category)
                <a href="{{ route('books.index', ['category' => $book->category_id]) }}"
                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                    Lihat semua {{ $book->category->name }} &rarr;
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($relatedBooks as $related)
                <a href="{{ route('books.show', $related) }}"
                   class="group flex flex-col rounded-2xl border border-white/40 bg-white shadow-md shadow-slate-200/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="aspect-[3/4] bg-indigo-50 overflow-hidden relative">
                        @if($related->cover_image)
                            <img src="{{ asset($related->cover_image) }}" alt="{{ $related->title }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-violet-100 text-indigo-400">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">{{ $related->title }}</p>
                        <p class="text-[10px] text-slate-500 mt-1 truncate">{{ $related->author }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
