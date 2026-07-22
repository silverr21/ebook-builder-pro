<!DOCTYPE html>
<html lang="sk" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Potvrdenie hesla – eBook Builder Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-rose-50 to-pink-100 dark:from-slate-900 dark:via-slate-800 dark:to-rose-950 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl shadow-lg shadow-rose-500/25 mb-4">
                <span class="text-3xl">🔒</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">Potvrdenie hesla</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Pre bezpečnosť potvrďte svoje heslo.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Heslo</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-rose-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold rounded-xl hover:from-rose-600 hover:to-pink-700 focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-rose-500/25">
                    Potvrdiť
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
