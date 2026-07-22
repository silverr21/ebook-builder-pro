@extends('layouts.app')

@section('title', 'AI Asistent - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-slate-900 dark:to-slate-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="text-6xl mb-4">🤖</div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">AI Asistent</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Využite silu umelej inteligencie pre tvorbu obsahu, korektúry a kreatívne nápady
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Generate Title -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">💡</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Generovať názov</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vygenerujte kreatívne názvy kníh na základe témy a žánru</p>
                <div class="space-y-2">
                    <input type="text" placeholder="Téma knihy"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <input type="text" placeholder="Žáner"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Generovať ✨
                    </button>
                </div>
            </div>

            <!-- SEO Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">🔍</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">SEO Optimalizácia</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte SEO popisy a kľúčové slová pre lepšiu viditeľnosť</p>
                <div class="space-y-2">
                    <select class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="">Vyberte knihu...</option>
                        @foreach(auth()->user()->books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Generovať SEO ✨
                    </button>
                </div>
            </div>

            <!-- FAQ Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">❓</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Generovať FAQ</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte zoznam častých otázok pre svoju knihu</p>
                <div class="space-y-2">
                    <input type="text" placeholder="Téma FAQ"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <input type="number" placeholder="Počet otázok (5-50)" min="5" max="50"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Generovať FAQ ✨
                    </button>
                </div>
            </div>

            <!-- Glossary Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">📖</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Slovník pojmov</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte slovník odborných pojmov z textu</p>
                <div class="space-y-2">
                    <textarea placeholder="Vložte text pre extrahovanie pojmov..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white resize-none"></textarea>
                    <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Generovať slovník ✨
                    </button>
                </div>
            </div>

            <!-- Summary Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">📝</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Zhrnutie textu</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte stručné zhrnutie dlhého textu</p>
                <div class="space-y-2">
                    <textarea placeholder="Vložte text na zhrnutie..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white resize-none"></textarea>
                    <div class="flex gap-2">
                        <input type="number" placeholder="Počet viet" min="3" max="20"
                            class="flex-1 px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <button class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                            Zhrnúť ✨
                        </button>
                    </div>
                </div>
            </div>

            <!-- Text Analysis -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="text-3xl mb-3">📊</div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Analýza textu</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Analyzujte text z hľadiska čitateľnosti a štatistík</p>
                <div class="space-y-2">
                    <textarea placeholder="Vložte text na analýzu..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white resize-none"></textarea>
                    <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                        Analyzovať 📊
                    </button>
                </div>
            </div>
        </div>

        <!-- Usage Tips -->
        <div class="mt-12 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-6">
            <div class="flex items-start gap-4">
                <span class="text-3xl">🎯</span>
                <div>
                    <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">Tipy pre prácu s AI</h3>
                    <ul class="space-y-2 text-sm text-purple-700 dark:text-purple-300">
                        <li>✓ Buďte čo najkonkrétnejší v požiadavkách pre lepšie výsledky</li>
                        <li>✓ Kombinujte AI generovaný obsah s vlastnými úpravami</li>
                        <li>✓ Vždy skontrolujte a upravte vygenerovaný text</li>
                        <li>✓ Používajte viacero nástrojov pre komplexnú tvorbu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
