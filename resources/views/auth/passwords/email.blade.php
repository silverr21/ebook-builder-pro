<!DOCTYPE html>
<html lang="sk" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Obnova hesla – eBook Builder Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50 to-amber-100 dark:from-slate-900 dark:via-slate-800 dark:to-amber-950 flex flex-col items-center justify-center p-4">
    <div class="fixed top-4 right-4 z-50">
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                class="p-3 rounded-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border border-slate-200 dark:border-slate-700 shadow-lg hover:shadow-xl transition-all duration-300">
            <svg x-show="!darkMode" class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <svg x-show="darkMode" class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-lg shadow-orange-500/25 mb-4">
                <span class="text-3xl">🔑</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">Obnova hesla</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Zadajte svoj email a pošleme vám odkaz na obnovu hesla.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                            placeholder="vas@email.sk">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-amber-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-orange-500/25">
                    Poslať odkaz na obnovu
                </button>

                <p class="text-center text-sm text-slate-600 dark:text-slate-400">
                    <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-700 dark:text-orange-400 transition">
                        ← Späť na prihlásenie
                    </a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
