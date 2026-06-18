@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="space-y-8"
     :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" 
     class="transition-all duration-700 ease-out">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Katalog Buku Perpustakaan</h1>
            <p class="text-sm text-slate-500 mt-1">Jelajahi dan temukan koleksi buku-buku terbaik kami</p>
        </div>
        
        @if(Auth::user()->isAdmin())
            <a href="{{ route('books.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Buku Baru
            </a>
        @endif
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/40">
        <form action="{{ route('books.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3" id="search-form">
            <div class="relative flex-1 group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="h-5 w-5 search-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
                    </svg>
                    <!-- Loading Spinner -->
                    <svg class="h-5 w-5 animate-spin hidden loading-spinner text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan judul, penulis, penerbit, atau ISBN..." autocomplete="off"
                    class="block w-full rounded-xl border border-slate-200 pl-10 pr-10 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 sm:text-sm transition-all bg-slate-50 focus:bg-white hover:border-slate-300 hover:bg-white">
                
                <!-- Clear Button -->
                <button type="button" id="clear-search" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 hidden transition-colors" title="Hapus pencarian">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Books Grid Container -->
    <div id="book-list-container" class="relative transition-opacity duration-300">
        @include('books.partials.book-list')
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const bookListContainer = document.getElementById('book-list-container');
    const searchIcon = document.querySelector('.search-icon');
    const loadingSpinner = document.querySelector('.loading-spinner');
    const clearSearchBtn = document.getElementById('clear-search');
    let debounceTimer;

    function toggleClearButton() {
        if (searchInput.value.trim().length > 0) {
            clearSearchBtn.classList.remove('hidden');
        } else {
            clearSearchBtn.classList.add('hidden');
        }
    }

    function performSearch(url) {
        // Show loading state
        searchIcon.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        bookListContainer.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            bookListContainer.innerHTML = html;
            
            // Update URL without reloading page
            window.history.pushState({}, '', url);
        })
        .catch(error => console.error('Error fetching search results:', error))
        .finally(() => {
            // Hide loading state
            searchIcon.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            bookListContainer.style.opacity = '1';
            toggleClearButton();
        });
    }

    // Debounced search on input
    searchInput.addEventListener('input', function() {
        toggleClearButton();
        clearTimeout(debounceTimer);
        
        debounceTimer = setTimeout(() => {
            const formData = new FormData(searchForm);
            const searchParams = new URLSearchParams(formData);
            const url = `${searchForm.action}?${searchParams.toString()}`;
            performSearch(url);
        }, 400); // 400ms delay
    });

    // Form submit fallback
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(searchForm);
        const searchParams = new URLSearchParams(formData);
        const url = `${searchForm.action}?${searchParams.toString()}`;
        performSearch(url);
    });

    // Clear search button
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();
        toggleClearButton();
        performSearch(searchForm.action);
    });

    // Handle pagination clicks (event delegation)
    bookListContainer.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination-container a');
        if (paginationLink) {
            e.preventDefault();
            performSearch(paginationLink.href);
            // Scroll smoothly to top of book list
            document.getElementById('search-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    // Initialize UI state
    toggleClearButton();
});
</script>
@endsection
