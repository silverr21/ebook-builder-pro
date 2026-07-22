<!DOCTYPE html>
<html lang="sk" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Overenie emailu – eBook Builder Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-teal-50 to-cyan-100 dark:from-slate-900 dark:via-slate-800 dark:to-teal-950 flex flex-col items-center justify-center p-4">
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
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl shadow-lg shadow-teal-500/25 mb-4">
                <span class="text-3xl">📧</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">Overte svoj email</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Skôr než začnete, overte svoju emailovú adresu.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-300">
                    {{ session('message') }}
                </div>
            @endif

            <div class="text-center">
                <div class="text-6xl mb-6">✉️</div>
                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    Poslali sme vám overovací email na adresu <strong class="text-slate-900 dark:text-white">{{ auth()->user()->email }}</strong>.
                    Kliknite na odkaz v emaile pre overenie svojho účtu.
                </p>

                <form method="POST" action="{{ route('verification.resend') }}" class="space-y-4">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-cyan-700 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-teal-500/25">
                        Znova odoslať overovací email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition">
                        Odhlásiť sa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
