<!DOCTYPE html>
<html lang="sk" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrácia – eBook Builder Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-slate-900 dark:via-slate-800 dark:to-purple-950 flex flex-col items-center justify-center p-4">
    <!-- Dark mode toggle -->
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

    <!-- Register Card -->
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg shadow-purple-500/25 mb-4">
                <span class="text-3xl">✍️</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">Vytvorte účet</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Začnite písať svoju prvú knihu ešte dnes.</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Meno</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                            placeholder="Vaše meno">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                            placeholder="vas@email.sk">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Heslo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Minimálne 8 znakov">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password-confirm" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Potvrdenie hesla</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Zopakujte heslo">
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-2">
                    <input type="checkbox" required
                        class="mt-1 w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-purple-600 focus:ring-purple-500 cursor-pointer">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Registráciou súhlasíte s 
                        <a href="#" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">podmienkami používania</a> 
                        a 
                        <a href="#" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">ochranou osobných údajov</a>.
                    </p>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-purple-500/25">
                    Vytvoriť účet
                </button>

                <!-- Login link -->
                <p class="text-center text-sm text-slate-600 dark:text-slate-400 mt-4">
                    Už máte účet?
                    <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 transition">
                        Prihláste sa
                    </a>
                </p>
            </form>
        </div>

        <!-- Features -->
        <div class="mt-8 grid grid-cols-3 gap-3">
            <div class="text-center p-3 bg-white/50 dark:bg-slate-800/50 rounded-xl backdrop-blur-sm">
                <span class="text-xl">🤖</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">AI Asistent</p>
            </div>
            <div class="text-center p-3 bg-white/50 dark:bg-slate-800/50 rounded-xl backdrop-blur-sm">
                <span class="text-xl">🎨</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Šablóny</p>
            </div>
            <div class="text-center p-3 bg-white/50 dark:bg-slate-800/50 rounded-xl backdrop-blur-sm">
                <span class="text-xl">📤</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Export</p>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 dark:text-slate-500 mt-6">
            &copy; {{ date('Y') }} eBook Builder Pro. Všetky práva vyhradené.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
