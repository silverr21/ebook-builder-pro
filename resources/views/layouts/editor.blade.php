<!DOCTYPE html>
<html lang="sk" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eBook Editor')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="{ 'w-64': sidebarOpen, 'w-0': !sidebarOpen }" class="bg-slate-800 text-white transition-all duration-300 overflow-hidden fixed h-screen md:relative z-40">
            <div class="p-4 border-b border-slate-700">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold flex items-center gap-2">
                    <span>📚</span>
                    <span x-show="sidebarOpen">eBook Pro</span>
                </a>
            </div>

            <nav class="p-4 space-y-2">
                <div x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2 mb-4">Projekt</div>
                
                <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition">
                    <span>📖</span>
                    <span x-show="sidebarOpen">Všechny knihy</span>
                </a>
                
                @isset($book)
                <div x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase px-2 mt-6 mb-4">{{ $book->title }}</div>
                
                <a href="{{ route('editor.show', $book) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-600 transition">
                    <span>✏️</span>
                    <span x-show="sidebarOpen">Editor</span>
                </a>

                <a href="{{ route('editor.design', $book) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition">
                    <span>🎨</span>
                    <span x-show="sidebarOpen">Design</span>
                </a>

                <a href="{{ route('editor.preview', $book) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition">
                    <span>👁️</span>
                    <span x-show="sidebarOpen">Náhled</span>
                </a>

                <a href="{{ route('editor.export', $book) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition">
                    <span>📤</span>
                    <span x-show="sidebarOpen">Export</span>
                </a>

                <a href="{{ route('editor.settings', $book) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition">
                    <span>⚙️</span>
                    <span x-show="sidebarOpen">Nastavení</span>
                </a>
                @endisset
            </nav>

            <div class="absolute bottom-4 left-4 right-4 space-y-2">
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition text-sm">
                    <span x-show="!darkMode">🌙</span>
                    <span x-show="darkMode">☀️</span>
                    <span x-show="sidebarOpen" x-text="darkMode ? 'Světlý režim' : 'Tmavý režim'"></span>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-red-700/20 transition text-sm text-red-400">
                        <span>🚪</span>
                        <span x-show="sidebarOpen">Odhlásiť</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    @isset($book)
                    <div>
                        <h1 class="text-lg font-semibold">{{ $book->title }}</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $book->chapters()->count() }} kapitol | 
                            {{ $book->wordCount() }} slov
                        </p>
                    </div>
                    @endisset
                </div>

                <div class="flex items-center gap-4">
                    @isset($book)
                    <button type="button" @if($book->status === 'draft') onclick="publishBook({{ $book->id }})" @endif class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium {{ $book->status === 'published' ? 'opacity-50 cursor-not-allowed' : '' }}">
                        {{ $book->status === 'published' ? '✓ Publikováno' : 'Publikovat' }}
                    </button>
                    @endisset

                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        {{ auth()->user()->name }}
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @yield('scripts')
</body>
</html>
