@extends('layouts.app')

@section('title', $book->title . ' - eBook Builder Pro')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <!-- Hero -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-start gap-8">
                <!-- Cover -->
                <div class="w-40 h-56 bg-white/10 rounded-xl flex items-center justify-center text-6xl shadow-2xl flex-shrink-0 backdrop-blur-sm">
                    {{ $book->cover_image ? '📖' : '📕' }}
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm bg-white/20">
                            @if($book->status === 'published') 📤 Publikované
                            @elseif($book->status === 'archived') 📦 Archivované
                            @else ✏️ Koncept
                            @endif
                        </span>
                        @if($book->is_featured)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-400 text-amber-900">
                            ⭐ Odporúčame
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-bold mb-2">{{ $book->title }}</h1>
                    
                    @if($book->author)
                    <p class="text-xl text-blue-200 mb-4">{{ $book->author }}</p>
                    @endif

                    @if($book->description)
                    <p class="text-blue-100 mb-6 max-w-2xl">{{ $book->description }}</p>
                    @endif

                    <!-- Quick Stats -->
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-blue-200">📖</span>
                            <span>{{ $book->chapters()->count() }} kapitol</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-blue-200">📝</span>
                            <span>{{ $book->wordCount() }} slov</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-blue-200">👁️</span>
                            <span>{{ $book->view_count }} prečítaní</span>
                        </div>
                        @if($book->published_at)
                        <div class="flex items-center gap-2">
                            <span class="text-blue-200">📅</span>
                            <span>{{ $book->published_at->format('d.m.Y') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 mt-6">
                        @auth
                            @can('update', $book)
                            <a href="{{ route('editor.show', $book) }}" 
                               class="px-6 py-2.5 bg-white text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition shadow-lg">
                                ✏️ Editovať
                            </a>
                            <a href="{{ route('editor.export', $book) }}"
                               class="px-6 py-2.5 border border-white/30 text-white font-medium rounded-lg hover:bg-white/10 transition">
                                📤 Exportovať
                            </a>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Table of Contents -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-8 mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">📑 Obsah</h2>
            
            @if($book->chapters->count() > 0)
            <div class="space-y-4">
                @foreach($book->chapters as $index => $chapter)
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white">
                                <span class="text-blue-600 dark:text-blue-400 mr-2">{{ $index + 1 }}.</span>
                                {{ $chapter->title }}
                            </h3>
                            @if($chapter->description)
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $chapter->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0 ml-4">
                            {{ $chapter->sections()->count() }} sekcií
                        </span>
                    </div>
                    
                    @if($chapter->sections->count() > 0)
                    <ul class="mt-2 ml-6 space-y-1">
                        @foreach($chapter->sections as $section)
                        @if($section->title)
                        <li class="text-sm text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition cursor-default">
                            • {{ $section->title }}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                <p>Táto kniha zatiaľ neobsahuje žiadne kapitoly.</p>
            </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <div class="text-2xl font-bold text-blue-600">{{ $book->chapters()->count() }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Kapitol</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <div class="text-2xl font-bold text-green-600">{{ $book->sections()->count() }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Sekcií</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($book->wordCount(), 0, ',', ' ') }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Slov</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-5">
                <div class="text-2xl font-bold text-amber-600">{{ ceil($book->wordCount() / 200) }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400">Minút čítania</p>
            </div>
        </div>

        <!-- Tags -->
        @if($book->tags)
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 mb-8">
            <h3 class="font-semibold text-slate-900 dark:text-white mb-3">🏷️ Tagy</h3>
            <div class="flex flex-wrap gap-2">
                @foreach((array) $book->tags as $tag)
                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-full">
                    #{{ $tag }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Design Info -->
        @if($book->design)
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-semibold text-slate-900 dark:text-white mb-3">🎨 Dizajn</h3>
            <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                <span>Šablóna: <strong class="text-slate-900 dark:text-white capitalize">{{ $book->design->template }}</strong></span>
                <span>•</span>
                <span>Písmo: <strong class="text-slate-900 dark:text-white">{{ $book->design->typography['body']['fontFamily'] ?? 'N/A' }}</strong></span>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
