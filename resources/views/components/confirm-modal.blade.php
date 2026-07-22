@props(['id' => 'confirm-modal', 'title' => 'Potvrdiť akciu', 'message' => 'Ste si istý?', 'confirmText' => 'Potvrdiť', 'cancelText' => 'Zrušiť', 'confirmColor' => 'red', 'icon' => '⚠️'])

@php
$colors = [
    'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
    'amber' => 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500',
];
$btnColor = $colors[$confirmColor] ?? $colors['red'];
@endphp

<div x-data="{ show: false, onConfirm: null, loading: false }" x-init="window['{{ $id }}'] = { 
    open(callback) { show = true; onConfirm = callback; },
    close() { show = false; onConfirm = null; }
}" x-show="show" @keydown.escape.window="show = false" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
        
        <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative w-full max-w-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6 text-center">
                <div class="text-4xl mb-4">{{ $icon }}</div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ $title }}</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">{{ $message }}</p>
                <div class="flex items-center gap-3">
                    <button @click="show = false; loading = false" class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        {{ $cancelText }}
                    </button>
                    <button @click="if(onConfirm) { loading = true; onConfirm().finally(() => { show = false; loading = false; }); }" 
                            :disabled="loading"
                            class="flex-1 px-4 py-2.5 text-white font-semibold rounded-xl transition shadow-lg {{ $btnColor }} disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="loading ? 'Spracúvam...' : '{{ $confirmText }}'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
