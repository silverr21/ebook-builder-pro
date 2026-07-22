@extends('layouts.editor')

@section('title', $book->title . ' - Náhľad')

@section('content')
<div class="p-6" x-data="previewManager()">
    <!-- Controls -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">👁️ Náhľad knihy</h2>
                <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-700 rounded-lg p-1">
                    <button @click="viewMode = 'book'" 
                        class="px-3 py-1.5 text-sm rounded-md transition"
                        :class="viewMode === 'book' ? 'bg-white dark:bg-slate-600 shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                        📖 Kniha
                    </button>
                    <button @click="viewMode = 'scroll'"
                        class="px-3 py-1.5 text-sm rounded-md transition"
                        :class="viewMode === 'scroll' ? 'bg-white dark:bg-slate-600 shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                        📜 Súvislý
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Veľkosť:</span>
                    <button @click="zoomOut()" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition text-sm">➖</button>
                    <span class="text-sm font-medium w-12 text-center" x-text="zoomLevel + '%'"></span>
                    <button @click="zoomIn()" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition text-sm">➕</button>
                </div>
                <a href="{{ route('editor.export', $book) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    📤 Exportovať
                </a>
            </div>
        </div>
    </div>

    <!-- Book Preview -->
    <div class="flex justify-center">
        <div id="book-preview" 
            :class="viewMode === 'book' ? 'shadow-2xl' : 'max-w-4xl w-full'"
            :style="{ 
                transform: `scale(${zoomLevel / 100})`,
                transformOrigin: 'top center',
                maxWidth: viewMode === 'book' ? '210mm' : '100%',
                minHeight: viewMode === 'book' ? '297mm' : 'auto',
            }"
            class="bg-white dark:bg-slate-800 rounded-lg p-8 md:p-12 transition-all duration-300">

            <!-- Book metadata -->
            <div class="text-center mb-12 pb-12 border-b border-slate-200 dark:border-slate-700">
                <div class="text-6xl mb-6">{{ $book->cover_image ? '📖' : '📕' }}</div>
                <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4" style="font-family: Georgia, serif;">
                    {{ $book->title }}
                </h1>
                @if($book->author)
                <p class="text-xl text-slate-600 dark:text-slate-400" style="font-family: Georgia, serif;">
                    {{ $book->author }}
                </p>
                @endif
                @if($book->description)
                <p class="text-base text-slate-500 dark:text-slate-400 mt-6 max-w-2xl mx-auto italic">
                    {{ $book->description }}
                </p>
                @endif
            </div>

            <!-- Table of Contents -->
            <div class="mb-12 pb-12 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6" style="font-family: Georgia, serif;">
                    Obsah
                </h2>
                <ul class="space-y-3" style="font-family: Georgia, serif;">
                    @foreach($book->chapters as $index => $chapter)
                    <li class="flex items-center justify-between text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        <span>
                            <span class="font-medium">{{ $index + 1 }}.</span>
                            {{ $chapter->title }}
                        </span>
                        <span class="text-sm text-slate-400">{{ $chapter->sections()->count() }} sekcií</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Chapter Content -->
            @foreach($book->chapters as $chapter)
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-6" style="font-family: Georgia, serif; page-break-before: always;">
                    {{ $chapter->title }}
                </h2>
                @if($chapter->description)
                <p class="text-slate-500 dark:text-slate-400 mb-6 italic">{{ $chapter->description }}</p>
                @endif

                @foreach($chapter->sections as $section)
                <div class="mb-6">
                    @if($section->title)
                    <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-3" style="font-family: Georgia, serif;">
                        {{ $section->title }}
                    </h3>
                    @endif
                    <div class="text-base leading-relaxed text-slate-700 dark:text-slate-300" style="font-family: Georgia, serif; line-height: 1.8;">
                        {!! $section->content !!}
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <!-- Footer -->
            <div class="text-center text-sm text-slate-400 dark:text-slate-500 mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
                <p>Vygenerované pomocou eBook Builder Pro</p>
            </div>
        </div>
    </div>
</div>

<script>
function previewManager() {
    return {
        viewMode: 'book',
        zoomLevel: 100,

        zoomIn() {
            this.zoomLevel = Math.min(this.zoomLevel + 10, 150);
        },

        zoomOut() {
            this.zoomLevel = Math.max(this.zoomLevel - 10, 50);
        }
    }
}
</script>
@endsection
