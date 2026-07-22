@props(['action' => '', 'icon' => '🤖', 'label' => 'AI Tool', 'description' => '', 'loading' => false, 'variant' => 'default'])

@php
$variants = [
    'default' => 'bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300',
    'primary' => 'bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
    'success' => 'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 text-green-700 dark:text-green-300',
    'warning' => 'bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-300',
];
$btnClass = $variants[$variant] ?? $variants['default'];
@endphp

<button @click="{{ $action }}" 
        :disabled="isLoading"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $btnClass }} hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed group">
    <span class="flex-shrink-0 text-base group-hover:scale-110 transition-transform" x-show="!isLoading">{{ $icon }}</span>
    <svg x-show="isLoading" class="flex-shrink-0 w-4 h-4 animate-spin text-purple-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <div class="flex-1 text-left">
        <span class="font-medium">{{ $label }}</span>
        @if($description)
        <p class="text-xs opacity-70 mt-0.5">{{ $description }}</p>
        @endif
    </div>
    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
</button>
