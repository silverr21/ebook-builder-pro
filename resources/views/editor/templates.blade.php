@extends('layouts.app')

@section('title', 'Šablóny - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="text-6xl mb-4">🎨</div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">Šablóny</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Vyberte si z profesionálne navrhnutých šablón pre vašu knihu
            </p>
        </div>

        <!-- Template Categories -->
        <div class="flex justify-center gap-2 mb-10">
            <button class="px-4 py-2 bg-white dark:bg-slate-800 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                Všetky
            </button>
            <button class="px-4 py-2 bg-white dark:bg-slate-800 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                Beletria
            </button>
            <button class="px-4 py-2 bg-white dark:bg-slate-800 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                Náučná
            </button>
            <button class="px-4 py-2 bg-white dark:bg-slate-800 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                Akademická
            </button>
            <button class="px-4 py-2 bg-white dark:bg-slate-800 rounded-full text-sm font-medium border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                Detská
            </button>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Modern Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <span class="text-6xl">🎯</span>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Modern</h3>
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">Populárne</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Čistý, moderný dizajn s výraznými farbami</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 4 farebné schémy</span>
                        <span>•</span>
                        <span>📐 2 varianty</span>
                    </div>
                </div>
            </div>

            <!-- Classic Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-amber-600 to-amber-800 flex items-center justify-center">
                    <span class="text-6xl">📜</span>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Classic</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Klasický knižný dizajn s tradičným vzhľadom</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 3 farebné schémy</span>
                        <span>•</span>
                        <span>📐 2 varianty</span>
                    </div>
                </div>
            </div>

            <!-- Minimal Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center">
                    <span class="text-6xl">✨</span>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Minimal</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Minimalistický a elegantný dizajn bez rušivých prvkov</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 2 farebné schémy</span>
                        <span>•</span>
                        <span>📐 1 variant</span>
                    </div>
                </div>
            </div>

            <!-- Academic Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                    <span class="text-6xl">🎓</span>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Academic</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Formát vhodný pre akademické a vedecké publikácie</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 2 farebné schémy</span>
                        <span>•</span>
                        <span>📐 2 varianty</span>
                    </div>
                </div>
            </div>

            <!-- Children Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center">
                    <span class="text-6xl">🧒</span>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Children</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Farebný a hravý dizajn pre detské knihy</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 6 farebných schém</span>
                        <span>•</span>
                        <span>📐 3 varianty</span>
                    </div>
                </div>
            </div>

            <!-- Poetry Template -->
            <div class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl transition cursor-pointer">
                <div class="h-48 bg-gradient-to-br from-rose-300 to-rose-500 flex items-center justify-center">
                    <span class="text-6xl">🌸</span>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Poetry</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Elegantný dizajn pre básne a poetické texty</p>
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span>🎨 4 farebné schémy</span>
                        <span>•</span>
                        <span>📐 2 varianty</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-12 text-center">
            <a href="{{ route('editor.new') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                ✨ Použiť šablónu a vytvoriť knihu
            </a>
        </div>
    </div>
</div>
@endsection
