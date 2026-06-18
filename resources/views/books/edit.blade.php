@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">

    <!-- Header -->
    <div>
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Katalog
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Buku</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi buku "<strong>{{ $book->title }}</strong>"</p>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm shadow-slate-100/40">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul Buku -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Judul Buku <span class="text-rose-500">*</span></label>
                <input id="title" name="title" type="text" required value="{{ old('title', $book->title) }}"
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('title') border-red-300 @enderror">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="category_id" class="block text-sm font-semibold text-slate-700">Kategori Buku <span class="text-rose-500">*</span></label>
                    <a href="{{ route('categories.create') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 transition-colors">+ Kategori Baru</a>
                </div>
                <select id="category_id" name="category_id" required
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('category_id') border-red-300 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penulis & Penerbit -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="author" class="block text-sm font-semibold text-slate-700">Penulis <span class="text-rose-500">*</span></label>
                    <input id="author" name="author" type="text" required value="{{ old('author', $book->author) }}"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('author') border-red-300 @enderror">
                    @error('author')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="publisher" class="block text-sm font-semibold text-slate-700">Penerbit <span class="text-rose-500">*</span></label>
                    <input id="publisher" name="publisher" type="text" required value="{{ old('publisher', $book->publisher) }}"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('publisher') border-red-300 @enderror">
                    @error('publisher')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tahun, ISBN, Stok -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="year" class="block text-sm font-semibold text-slate-700">Tahun Terbit <span class="text-rose-500">*</span></label>
                    <input id="year" name="year" type="number" required value="{{ old('year', $book->year) }}" min="1000" max="{{ date('Y') + 1 }}"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('year') border-red-300 @enderror">
                    @error('year')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="isbn" class="block text-sm font-semibold text-slate-700">ISBN</label>
                    <input id="isbn" name="isbn" type="text" value="{{ old('isbn', $book->isbn) }}"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('isbn') border-red-300 @enderror"
                        placeholder="Opsional">
                    @error('isbn')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700">Jumlah Stok <span class="text-rose-500">*</span></label>
                    <input id="stock" name="stock" type="number" required value="{{ old('stock', $book->stock) }}" min="0"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('stock') border-red-300 @enderror">
                    @error('stock')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cover Image -->
            <div>
                <label for="cover_image" class="block text-sm font-semibold text-slate-700">Sampul Buku (Opsional)</label>
                @if($book->cover_image)
                    <div class="mt-2 mb-3 flex items-center gap-4">
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="h-20 w-16 rounded-lg border border-slate-200 object-cover shadow-sm">
                        <span class="text-xs text-slate-500">Sampul saat ini. Upload baru untuk mengganti.</span>
                    </div>
                @endif
                
                <div id="preview-container" class="hidden mt-2 mb-3 items-center gap-4 p-2 border border-dashed border-indigo-100 bg-indigo-50/30 rounded-lg animate-fade-in">
                    <img id="image-preview" src="#" alt="Pratinjau Sampul Baru" class="h-20 w-16 rounded-lg border border-indigo-200 object-cover shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-xs font-medium text-indigo-600">Sampul baru terpilih:</span>
                        <span id="file-name" class="text-xs text-slate-600 font-mono truncate max-w-xs">nama_file.jpg</span>
                    </div>
                </div>

                <div class="flex items-center justify-center w-full">
                    <label for="cover_image" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer bg-slate-50/50 hover:bg-indigo-50/50 hover:border-indigo-200 transition-all">
                        <div class="flex flex-col items-center justify-center py-4">
                            <svg class="h-7 w-7 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                            <p class="text-xs text-slate-500"><span id="upload-text" class="font-semibold text-indigo-600">Klik untuk upload baru</span></p>
                            <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, WEBP (Maks. 2MB)</p>
                        </div>
                        <input id="cover_image" name="cover_image" type="file" class="hidden" accept="image/*" onchange="previewImage(this)" />
                    </label>
                </div>
                @error('cover_image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Perbarui Buku
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');
    const uploadText = document.getElementById('upload-text');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Show file name
        fileName.textContent = file.name;
        
        // Update upload text
        if (uploadText) uploadText.textContent = 'Klik untuk ganti file';
        
        // Create file reader to show image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
        previewContainer.classList.remove('flex');
        if (uploadText) uploadText.textContent = 'Klik untuk upload baru';
    }
}
</script>
@endsection
