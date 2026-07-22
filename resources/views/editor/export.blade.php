@extends('layouts.editor')

@section('title', $book->title . ' - Export')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">📤 Export knihy</h2>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Exportujte svoju knihu do rôznych formátov</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- PDF -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">📄</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">PDF</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Univerzálny formát vhodný pre tlač a zdieľanie</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Profesionálny vzhľad</li>
                <li>✓ Zachováva formátovanie</li>
                <li>✓ Vhodné pre tlač</li>
            </ul>
            <a href="{{ route('api.export.pdf', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-md">
                Stiahnuť PDF
            </a>
        </div>

        <!-- EPUB -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">📱</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">EPUB</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Najpoužívanejší formát pre e-čitačky</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Kompatibilné so všetkými e-čitačkami</li>
                <li>✓ Prispôsobiteľná veľkosť písma</li>
                <li>✓ Podpora záložiek</li>
            </ul>
            <a href="{{ route('api.export.epub', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-md">
                Stiahnuť EPUB
            </a>
        </div>

        <!-- MOBI -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">📘</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">MOBI</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Formát pre Kindle zariadenia</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Amazon Kindle</li>
                <li>✓ Podpora Whispersync</li>
                <li>✓ Optimalizované pre Kindle</li>
            </ul>
            <a href="{{ route('api.export.mobi', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-md">
                Stiahnuť MOBI
            </a>
        </div>

        <!-- DOCX -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">📝</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">DOCX</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Formát pre Microsoft Word</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Microsoft Word</li>
                <li>✓ Google Docs</li>
                <li>✓ LibreOffice</li>
            </ul>
            <a href="{{ route('api.export.docx', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">
                Stiahnuť DOCX
            </a>
        </div>

        <!-- Markdown -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">📃</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Markdown</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Ľahký textový formát pre vývojárov</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Čistý textový formát</li>
                <li>✓ Verzovanie v GIT</li>
                <li>✓ Konvertovateľné na HTML</li>
            </ul>
            <a href="{{ route('api.export.markdown', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-md">
                Stiahnuť MD
            </a>
        </div>

        <!-- HTML -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
            <div class="text-4xl mb-4">🌐</div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">HTML</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Webový formát pre online publikovanie</p>
            <ul class="text-xs text-slate-500 dark:text-slate-400 mb-6 space-y-1">
                <li>✓ Publikovanie na webe</li>
                <li>✓ Responzívny dizajn</li>
                <li>✓ Vyhľadávače (SEO)</li>
            </ul>
            <a href="{{ route('api.export.html', $book) }}" 
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium rounded-lg hover:from-purple-600 hover:to-purple-700 transition shadow-md">
                Stiahnuť HTML
            </a>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">⚙️ Možnosti exportu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300">Zahrnúť obsah</span>
                </label>
                <label class="flex items-center gap-3 mt-2">
                    <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300">Zahrnúť dizajn</span>
                </label>
                <label class="flex items-center gap-3 mt-2">
                    <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300">Zahrnúť obrázky</span>
                </label>
            </div>
            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300">Čísla strán</span>
                </label>
                <label class="flex items-center gap-3 mt-2">
                    <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-700 dark:text-slate-300">Hlavička a päta</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Batch Export -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
        <div class="flex items-start gap-4">
            <span class="text-3xl">🚀</span>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Hromadný export</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300 mb-4">Exportujte do všetkých formátov naraz jedným kliknutím</p>
                <button class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-md">
                    📦 Exportovať všetko
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
