@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="flex min-h-[70vh] flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50 relative overflow-hidden"
     :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" 
     class="transition-all duration-700 ease-out">
    <!-- Ambient Background Elements -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
    <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-violet-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <!-- Logo -->
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-lg shadow-indigo-200">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight text-slate-900">Selamat datang kembali</h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Atau
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                daftar anggota baru di sini
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white/70 backdrop-blur-xl py-8 px-4 shadow-2xl shadow-indigo-100/50 border border-white sm:rounded-3xl sm:px-10 transition-all duration-300 hover:shadow-indigo-200/50">
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember" class="ml-2 block text-sm text-slate-700 select-none">Ingat saya</label>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all hover:opacity-95">
                        Masuk Aplikasi
                    </button>
                </div>
            </form>

            <!-- Quick Account Selector (Very helpful for evaluation) -->
            <div class="mt-8 border-t border-slate-100 pt-6">
                <p class="text-xs font-semibold text-slate-400 text-center uppercase tracking-wider">Demo Login Cepat</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button type="button" onclick="fillCredentials('admin@perpustakaan.com')"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50/20 px-3 py-2.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition-all">
                        Login Admin
                    </button>
                    <button type="button" onclick="fillCredentials('user@perpustakaan.com')"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-violet-100 bg-violet-50/20 px-3 py-2.5 text-xs font-semibold text-violet-600 hover:bg-violet-50 transition-all">
                        Login Anggota (User)
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function fillCredentials(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
@endsection
