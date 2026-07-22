@extends('layouts.app')

@section('title', 'Dashboard - eBook Builder Pro')

@section('content')
<div x-data="dashboard()" x-init="init()">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-[30rem] h-[30rem] bg-cyan-300 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                            {{ date('l, d.m.Y') }}
                        </div>
                        @if($streak > 0)
                        <div class="px-3 py-1 bg-amber-400/20 backdrop-blur-sm rounded-full text-xs font-medium flex items-center gap-1">
                            🔥 <span class="font-bold">{{ $streak }}</span> dní v rade
                        </div>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2" style="font-family: 'Poppins', sans-serif;">
                        Vitaj späť, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-blue-100 text-lg max-w-2xl">
                        @if($totalWords > 0)
                            Doteraz si napísal <strong class="text-white">{{ number_format($totalWords, 0, ',', ' ') }}</strong> slov.
                            @if($wordsThisWeek > 0)
                                Tento týždeň <strong class="text-white">{{ number_format($wordsThisWeek, 0, ',', ' ') }}</strong>.
                            @endif
                        @else
                            Pripravený začať písať svoju prvú knihu? 🚀
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('editor.new') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200 shadow-lg shadow-black/10 transform hover:-translate-y-0.5">
                        <span>✨</span>
                        <span>Nová kniha</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Wave divider -->
        <div class="relative h-8">
            <svg class="absolute bottom-0 w-full h-8 text-slate-50 dark:text-slate-900" viewBox="0 0 1440 48" preserveAspectRatio="none">
                <path d="M0,48 C360,0 1080,0 1440,48 L1440,48 L0,48 Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 relative z-10">
        
        <!-- === Quick Actions === -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <a href="{{ route('editor.new') }}" 
               class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                        📝
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">Nová kniha</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Začni nový projekt</p>
                </div>
            </a>

            <a href="{{ route('books.index') }}" 
               class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                        📚
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">Moje knihy</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $totalBooks }} kníh</p>
                </div>
            </a>

            <a href="{{ route('ai.explore') }}" 
               class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                        🤖
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">AI Asistent</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Generuj obsah</p>
                </div>
            </a>

            <a href="{{ route('templates') }}" 
               class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:border-green-400 dark:hover:border-green-500 hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                        🎨
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">Šablóny</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dizajn knihy</p>
                </div>
            </a>
        </div>

        <!-- === Main Grid: Stats + Chart === -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <!-- Left: Main Stats -->
            <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">📖</span>
                        <span class="text-xs px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full font-medium">{{ $totalBooks }}</span>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalBooks }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Celkom kníh</p>
                    @if($draftBooks > 0)
                    <div class="mt-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $totalBooks > 0 ? ($publishedBooks / $totalBooks) * 100 : 0 }}%"></div>
                    </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">📤</span>
                        <span class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full font-medium">
                            {{ $totalBooks > 0 ? round(($publishedBooks / $totalBooks) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="text-2xl font-bold text-green-600">{{ $publishedBooks }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Publikované</p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">✍️</span>
                        <span class="text-xs px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full font-medium">{{ number_format($wordsThisWeek, 0, ',', ' ') }}/t</span>
                    </div>
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($totalWords, 0, ',', ' ') }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Napísaných slov</p>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">👁️</span>
                        <span class="text-xs px-2 py-0.5 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full font-medium">{{ number_format($totalViews, 0, ',', ' ') }}</span>
                    </div>
                    <div class="text-2xl font-bold text-orange-600">{{ number_format($totalViews, 0, ',', ' ') }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Prečítaní</p>
                </div>
            </div>

            <!-- Right: Weekly Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">📊 Tento týždeň</h3>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $chaptersThisWeek }} kapitol</span>
                </div>
                <div class="flex items-end justify-between gap-1.5 h-28">
                    @foreach($chartData as $data)
                    <div class="flex-1 flex flex-col items-center gap-1 group">
                        <div class="relative w-full flex justify-center">
                            <div class="absolute -top-6 text-[10px] text-slate-500 dark:text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity font-medium">
                                {{ $data['words'] }}
                            </div>
                            <div class="w-full rounded-md transition-all duration-300 hover:opacity-80 cursor-pointer"
                                 :style="{ height: getBarHeight({{ $data['words'] }}) }"
                                 :class="getBarColor('{{ strtolower($data['day']) }}')">
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ substr($data['day'], 0, 3) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- === Activity + Books Grid === -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <!-- Activity Feed -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>⚡</span> Aktivity
                    </h3>
                    <span class="text-xs text-slate-400">{{ $recentActivity->count() }} akcií</span>
                </div>
                <div class="p-4 space-y-0 max-h-[400px] overflow-y-auto">
                    @forelse($recentActivity as $activity)
                    <a href="{{ $activity['url'] }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-base flex-shrink-0
                            @if($activity['type'] === 'chapter') bg-blue-100 dark:bg-blue-900/30
                            @elseif($activity['type'] === 'writing') bg-purple-100 dark:bg-purple-900/30
                            @else bg-green-100 dark:bg-green-900/30
                            @endif">
                            {{ $activity['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-snug">{!! $activity['text'] !!}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-slate-400">{{ $activity['book'] }}</span>
                                <span class="text-xs text-slate-300">•</span>
                                <span class="text-xs text-slate-400">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-slate-400 transition flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <div class="text-3xl mb-3">🚀</div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Zatiaľ žiadna aktivita</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Začni písať a uvidíš tu svoj pokrok</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Books Progress -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📚</span> Moje knihy
                    </h3>
                    <a href="{{ route('books.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium hover:underline">
                        Zobraziť všetky →
                    </a>
                </div>
                
                @if($recentBooks->count() > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($recentBooks as $book)
                    <a href="{{ route('editor.show', $book) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition group">
                        <!-- Book icon -->
                        <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
                            {{ $book->cover_image ? '📖' : '📕' }}
                        </div>
                        
                        <!-- Book info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-slate-900 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-1">
                                {{ $book->title }}
                            </h4>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $bookProgress[$book->id]['chapters_count'] }} kapitol
                                </span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ number_format($bookProgress[$book->id]['word_count'], 0, ',', ' ') }} slov
                                </span>
                            </div>
                            <!-- Progress bar -->
                            <div class="mt-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                @php
                                    $targetWords = 50000; // target 50k words per book
                                    $progress = min(100, ($bookProgress[$book->id]['word_count'] / $targetWords) * 100);
                                @endphp
                                <div class="h-full rounded-full transition-all duration-500" 
                                     :style="{ width: '{{ $progress }}%' }"
                                     :class="'{{ $progress >= 100 ? 'bg-green-500' : ($progress >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}'">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status + Action -->
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                @if($book->status === 'published') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                @elseif($book->status === 'archived') bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400
                                @else bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400
                                @endif">
                                @if($book->status === 'published') Publikované
                                @elseif($book->status === 'archived') Archivované
                                @else Koncept
                                @endif
                            </span>
                            <span class="text-xs text-slate-400 hidden sm:inline">{{ $bookProgress[$book->id]['updated_diff'] }}</span>
                            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-slate-400 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-16 px-4">
                    <div class="text-6xl mb-6">📚</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Zatiaľ žiadne knihy</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md mx-auto">
                        Začni písať svoju prvú knihu. S naším AI asistentom to bude hračka!
                    </p>
                    <a href="{{ route('editor.new') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                        ✨ Vytvoriť prvú knihu
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- === AI Quick Tools + Tips === -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <!-- AI Quick Tools -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl border border-purple-200 dark:border-purple-800 p-5">
                <h3 class="font-semibold text-purple-900 dark:text-purple-100 mb-4 flex items-center gap-2">
                    <span>🤖</span> AI Rýchle nástroje
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('ai.explore') }}#generate-title" 
                       class="flex items-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:border-purple-300 dark:hover:border-purple-600 border border-transparent transition shadow-sm hover:shadow">
                        <span>💡</span> Názov knihy
                    </a>
                    <a href="{{ route('ai.explore') }}#generate-chapter"
                       class="flex items-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:border-purple-300 dark:hover:border-purple-600 border border-transparent transition shadow-sm hover:shadow">
                        <span>📖</span> Kapitola
                    </a>
                    <a href="{{ route('ai.explore') }}#grammar"
                       class="flex items-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:border-purple-300 dark:hover:border-purple-600 border border-transparent transition shadow-sm hover:shadow">
                        <span>📝</span> Gramatika
                    </a>
                    <a href="{{ route('ai.explore') }}#seo"
                       class="flex items-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:border-purple-300 dark:hover:border-purple-600 border border-transparent transition shadow-sm hover:shadow">
                        <span>🔍</span> SEO
                    </a>
                </div>
            </div>

            <!-- Writing Tips -->
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-5">
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center gap-2">
                    <span>💡</span> Tipy na dnes
                </h3>
                <ul class="space-y-3 text-sm text-blue-800 dark:text-blue-200">
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Použi AI asistenta pre generovanie kapitol</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Pravidelne zálohuj svoje dielo</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Experimentuj s rôznymi šablónami dizajnu</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Exportuj do PDF, EPUB a ďalších formátov</span>
                    </li>
                </ul>
            </div>

            <!-- Stats Summary -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-200 dark:border-green-800 p-5">
                <h3 class="font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center gap-2">
                    <span>📊</span> Prehľad
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-800 dark:text-green-300">Celkom kníh</span>
                        <span class="text-lg font-bold text-green-700 dark:text-green-200">{{ $totalBooks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-800 dark:text-green-300">Publikované</span>
                        <span class="text-lg font-bold text-green-700 dark:text-green-200">{{ $publishedBooks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-800 dark:text-green-300">Slov tento týždeň</span>
                        <span class="text-lg font-bold text-green-700 dark:text-green-200">{{ number_format($wordsThisWeek, 0, ',', ' ') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-800 dark:text-green-300">🔥 Writing streak</span>
                        <span class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $streak }} dní</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function dashboard() {
    return {
        maxWords: 0,
        
        init() {
            // Find max words for chart scaling
            const chartData = @json($chartData);
            this.maxWords = Math.max(...chartData.map(d => d.words), 1);
        },
        
        getBarHeight(words) {
            const percentage = Math.max(3, (words / this.maxWords) * 100);
            return percentage + '%';
        },
        
        getBarColor(day) {
            const colors = {
                'mon': 'bg-blue-400 dark:bg-blue-500',
                'tue': 'bg-indigo-400 dark:bg-indigo-500',
                'wed': 'bg-violet-400 dark:bg-violet-500',
                'thu': 'bg-purple-400 dark:bg-purple-500',
                'fri': 'bg-pink-400 dark:bg-pink-500',
                'sat': 'bg-rose-400 dark:bg-rose-500',
                'sun': 'bg-orange-400 dark:bg-orange-500',
            };
            return colors[day] || 'bg-blue-400';
        }
    }
}
</script>
@endsection
