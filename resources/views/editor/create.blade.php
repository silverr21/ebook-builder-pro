@extends('layouts.app')

@section('title', 'Nová kniha - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <div class="text-6xl mb-4">📚</div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">Vytvorte novú knihu</h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg">Začnite písať svoju ďalšiu najpredávanejšiu knihu</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Názov knihy <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder:text-slate-400"
                        placeholder="Napríklad: Ako si splniť sny">
                </div>

                <div>
                    <label for="author" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Autor
                    </label>
                    <input type="text" id="author" name="author"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder:text-slate-400"
                        placeholder="Vaše meno alebo pseudonym" value="{{ auth()->user()->name }}">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Popis knihy
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder:text-slate-400 resize-none"
                        placeholder="Stručný popis vašej knihy..."></textarea>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">💡</span>
                        <div>
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Tip</p>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                Názov a popis môžete kedykoľvek neskôr zmeniť v nastaveniach knihy.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition transform hover:-translate-y-0.5 shadow-lg shadow-blue-500/25">
                        ✨ Vytvoriť knihu
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Zrušiť
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick tips -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 text-center">
                <span class="text-2xl">🤖</span>
                <h4 class="font-medium text-sm mt-2 text-slate-700 dark:text-slate-300">AI Asistent</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Generujte obsah pomocou umelej inteligencie</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 text-center">
                <span class="text-2xl">🎨</span>
                <h4 class="font-medium text-sm mt-2 text-slate-700 dark:text-slate-300">Profesionálny dizajn</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Vyberte z množstva šablón a štýlov</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 text-center">
                <span class="text-2xl">📤</span>
                <h4 class="font-medium text-sm mt-2 text-slate-700 dark:text-slate-300">Export</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Exportujte do PDF, EPUB, MOBI a ďalších</p>
            </div>
        </div>
    </div>
</div>
@endsection
