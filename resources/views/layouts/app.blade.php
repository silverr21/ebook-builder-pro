<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'eBook Builder Pro')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 transition-colors duration-200">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600">
                            📚 eBook Pro
                        </a>
                        
                        @auth
                        <div class="hidden md:flex items-center gap-4">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 transition">
                                Dashboard
                            </a>
                            <a href="{{ route('books.index') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 transition">
                                Moje knihy
                            </a>
                            <a href="{{ route('editor.new') }}" class="text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 transition">
                                Nová kniha
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-red-500 hover:text-red-600 transition">
                                🛡️ Admin
                            </a>
                            @endif
                        </div>
                        @endauth
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm5.657-9.193a1 1 0 00-1.414 0l-.707.707A1 1 0 005.05 6.464l.707-.707a1 1 0 001.414-1.414zM5 8a1 1 0 100-2H4a1 1 0 100 2h1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                                Prihlásenie
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Registrácia
                            </a>
                        @else
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-3 py-1.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition" title="Profil">
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                        Odhlásiť
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-slate-600 dark:text-slate-400 text-sm">
                    <p>&copy; 2026 eBook Builder Pro. Všechna práva vyhrazena.</p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
