@extends('layouts.app')

@section('title', 'Moje knihy - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">📚 Moje knihy</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Spravujte všetky svoje knihy na jednom mieste</p>
            </div>
            <a href="{{ route('editor.new') }}"
               class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                ✨ Nová kniha
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 mb-6">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" placeholder="Hľadať knihy..." 
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <select class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    <option value="">Všetky statusy</option>
                    <option value="draft">Koncept</option>
                    <option value="published">Publikované</option>
                    <option value="archived">Archivované</option>
                </select>
                <select class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    <option value="latest">Najnovšie</option>
                    <option value="oldest">Najstaršie</option>
                    <option value="title">Podľa názvu</option>
                    <option value="word_count">Podľa počtu slov</option>
                </select>
            </div>
        </div>

        <!-- Books Grid -->
        @if($books->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
            <div class="group bg-white dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-xl transition hover:-translate-y-1">
                <!-- Cover -->
                <a href="{{ route('editor.show', $book) }}" class="block">
                    <div class="h-40 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-5xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                        <span class="relative">{{ $book->cover_image ? '📖' : '📕' }}</span>
                    </div>
                </a>

                <!-- Info -->
                <div class="p-5">
                    <a href="{{ route('editor.show', $book) }}" class="block mb-2">
                        <h3 class="font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-2">
                            {{ $book->title }}
                        </h3>
                    </a>

                    @if($book->author)
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ $book->author }}</p>
                    @endif

                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-4">
                        <span>{{ $book->chapters_count ?? $book->chapters()->count() }} kapitol</span>
                        <span>•</span>
                        <span>{{ $book->wordCount() }} slov</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                            @if($book->status === 'published')
                                class="text-xs px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full font-medium"
                            @elseif($book->status === 'archived')
                                class="text-xs px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full font-medium"
                            @else
                                class="text-xs px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full font-medium"
                            @endif>
                            @if($book->status === 'published') Publikované
                            @elseif($book->status === 'archived') Archivované
                            @else Koncept
                            @endif
                        </span>
                        <span class="text-xs text-slate-400">{{ $book->updated_at->diffForHumans() }}</span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('editor.show', $book) }}" class="flex-1 text-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                            ✏️ Editor
                        </a>
                        <a href="{{ route('editor.preview', $book) }}" class="flex-1 text-center px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                            👁️ Náhľad
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="text-7xl mb-6">📚</div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Zatiaľ žiadne knihy</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
                Začnite písať svoju prvú knihu. S našim AI asistentom to bude hračka!
            </p>
            <a href="{{ route('editor.new') }}"
               class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                ✨ Vytvoriť prvú knihu
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
