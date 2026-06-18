@if($books->isEmpty())
    <div class="rounded-2xl border border-slate-100 bg-white py-16 text-center text-slate-500 shadow-sm">
        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
        </svg>
        <h3 class="mt-4 text-sm font-semibold text-slate-900">Buku tidak ditemukan</h3>
        <p class="mt-1 text-xs text-slate-500">Silakan coba kata kunci pencarian lain.</p>
    </div>
@else
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($books as $book)
            <div class="flex flex-col rounded-2xl border border-white/40 bg-white shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group overflow-hidden relative cursor-pointer" onclick="window.location='{{ route('books.show', $book) }}'"
                
                <!-- Cover Container -->
                <div class="relative aspect-[3/4] bg-indigo-50 flex items-center justify-center text-indigo-400 overflow-hidden">
                    @if($book->cover_image)
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    @else
                        <div class="flex flex-col items-center gap-2 p-4 text-center group-hover:scale-105 transition-transform duration-700">
                            <svg class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                            </svg>
                            <span class="text-xs font-semibold text-indigo-900 bg-indigo-100 rounded-lg px-2.5 py-1 select-none">No Cover</span>
                        </div>
                    @endif

                    <!-- Stock Badge Overlay -->
                    <div class="absolute top-3 right-3 z-10">
                        @if($book->stock > 0)
                            <span class="inline-flex items-center rounded-full bg-indigo-600/90 backdrop-blur-md px-3 py-1 text-xs font-bold text-white shadow-lg border border-indigo-500/50">
                                Stok: {{ $book->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-rose-600/90 backdrop-blur-md px-3 py-1 text-xs font-bold text-white shadow-lg border border-rose-500/50">
                                Habis
                            </span>
                        @endif
                    </div>
                    
                    <!-- Hover Overlay (Glassmorphism Slide Up) -->
                    <div class="absolute inset-x-0 bottom-0 top-1/2 bg-gradient-to-t from-slate-900/90 via-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex flex-col justify-end p-5 translate-y-4 group-hover:translate-y-0">
                        <!-- Action Buttons -->
                        <div class="mt-4 flex items-center justify-between gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            @if(Auth::user()->isAdmin())
                                <!-- Admin Actions -->
                                <a href="{{ route('books.edit', $book) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm py-2.5 text-xs font-semibold text-white hover:bg-white hover:text-indigo-900 transition-colors">
                                    Ubah
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-1 inline" onsubmit="return confirm('Hapus buku ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-rose-500/80 backdrop-blur-sm py-2.5 text-xs font-semibold text-white hover:bg-rose-600 transition-colors border border-rose-400/50">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <!-- User Actions -->
                                @if($book->stock > 0)
                                    <form action="{{ route('borrowings.store') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-500 py-3 text-sm font-bold text-white shadow-xl shadow-indigo-900/20 hover:bg-indigo-400 transition-all">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Pinjam Sekarang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center rounded-xl bg-slate-800/80 backdrop-blur-sm py-3 text-sm font-bold text-slate-400 cursor-not-allowed border border-slate-700">
                                        Habis Dipinjam
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Book Metadata (Always Visible) -->
                <div class="flex-1 p-5 flex flex-col justify-between bg-white z-20">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider bg-indigo-50 px-2 py-1 rounded-md">{{ $book->publisher }} ({{ $book->year }})</span>
                            @if($book->category)
                                <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-600 border border-slate-100">{{ $book->category->name }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mt-2 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors" title="{{ $book->title }}">{{ $book->title }}</h3>
                        <p class="text-xs text-slate-500 mt-2 font-medium truncate flex items-center gap-1">
                            <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            {{ $book->author }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="mt-8 pagination-container">
        {{ $books->links() }}
    </div>
@endif
