@extends('layouts.app')

@section('title', 'Dashboard - eBook Builder Pro')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Vítejte zpět, {{ auth()->user()->name }}!</h1>
        <p class="text-blue-100">Pokračujte v psaní nebo vytvořte novou knihu</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <a href="{{ route('editor.new') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="relative z-10">
                <div class="text-3xl mb-2">📝</div>
                <h3 class="text-lg font-semibold">Nová kniha</h3>
                <p class="text-sm text-blue-100">Začněte nový projekt</p>
            </div>
        </a>

        <a href="{{ route('ai.explore') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="relative z-10">
                <div class="text-3xl mb-2">🤖</div>
                <h3 class="text-lg font-semibold">AI Asistent</h3>
                <p class="text-sm text-purple-100">Generujte obsah s AI</p>
            </div>
        </a>

        <a href="{{ route('templates') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="relative z-10">
                <div class="text-3xl mb-2">🎨</div>
                <h3 class="text-lg font-semibold">Šablóny</h3>
                <p class="text-sm text-green-100">Vyberte šablónu</p>
            </div>
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
            <div class="text-3xl font-bold text-blue-600">{{ auth()->user()->books()->count() }}</div>
            <p class="text-sm text-slate-600 dark:text-slate-400">Celkem knih</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
            <div class="text-3xl font-bold text-green-600">{{ auth()->user()->books()->where('status', 'published')->count() }}</div>
            <p class="text-sm text-slate-600 dark:text-slate-400">Publikované</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
            <div class="text-3xl font-bold text-purple-600">{{ auth()->user()->books()->sum(function($b) { return $b->wordCount(); }) }}</div>
            <p class="text-sm text-slate-600 dark:text-slate-400">Psaných slov</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
            <div class="text-3xl font-bold text-orange-600">{{ auth()->user()->books()->sum('view_count') }}</div>
            <p class="text-sm text-slate-600 dark:text-slate-400">Čtenářů</p>
        </div>
    </div>

    <!-- Recent Books -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Nedávné knihy</h2>
            <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Zobrazit všechny →</a>
        </div>

        @if(auth()->user()->books()->exists())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(auth()->user()->books()->latest()->take(6)->get() as $book)
                <a href="{{ route('editor.show', $book) }}" class="group bg-white dark:bg-slate-800 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-400 transition hover:shadow-lg">
                    <div class="h-40 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-4xl">
                        {{ $book->cover_image ? '📖' : '📕' }}
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold group-hover:text-blue-600 transition line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            {{ $book->chapters()->count() }} kapitol | {{ $book->wordCount() }} slov
                        </p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded-full {{ $book->status === 'published' ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $book->status === 'published' ? 'Publikováno' : 'Koncept' }}
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $book->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-slate-50 dark:bg-slate-800/50 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-600">
                <div class="text-4xl mb-4">📚</div>
                <h3 class="text-lg font-semibold mb-2">Žádné knihy</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-4">Začněte psát svou první knihu</p>
                <a href="{{ route('editor.new') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Vytvořit knihu
                </a>
            </div>
        @endif
    </div>

    <!-- Tips & Tricks -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">💡 Tipy & Triky</h3>
        <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
            <li>✓ Použijte AI asistenta pro generování kapitol a obsahu</li>
            <li>✓ Pravidelně zálohujte své dílo v cloudu</li>
            <li>✓ Experimentujte s různými šablonami designu</li>
            <li>✓ Exportujte do PDF, EPUB a dalších formátů</li>
        </ul>
    </div>
</div>

<script>
function publishBook(bookId) {
    if (confirm('Opravdu chcete publikovat tuto knihu?')) {
        fetch(`/api/books/${bookId}/publish`, { 
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            alert('Kniha byla publikována!');
            location.reload();
        })
        .catch(e => alert('Chyba: ' + e.message));
    }
}
</script>
@endsection
