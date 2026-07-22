@props(['book' => null])

<div x-data="{ 
    show: false,
    topic: '',
    genre: 'fiction',
    style: 'formal',
    chapters: 5,
    loading: false,
    progress: '',
    error: ''
}" x-init="window.aiWriteModal = this">
    <!-- Trigger Button -->
    <button @click="show = true" class="w-full flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium rounded-xl hover:from-purple-600 hover:to-pink-600 transition shadow-lg shadow-purple-500/25 group">
        <span class="text-lg group-hover:scale-110 transition-transform">✨</span>
        <div class="text-left">
            <span class="font-semibold text-sm">Rýchle písanie s AI</span>
            <p class="text-xs text-white/70">Vygeneruj celú knihu naraz</p>
        </div>
        <svg class="w-5 h-5 ml-auto opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
    </button>

    <!-- Modal -->
    <div x-show="show" @click.self="show = false" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="show = false"></div>
            
            <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-lg">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-purple-500/10 to-pink-500/10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-xl">✨</div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Rýchle písanie s AI</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Vygeneruj kompletnú knihu jedným kliknutím</p>
                                </div>
                            </div>
                            <button @click="show = false" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition text-slate-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-5">
                        @if(!auth()->user()->hasAiApiKey())
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <span class="text-xl">⚠️</span>
                                <div>
                                    <p class="text-sm font-medium text-amber-900 dark:text-amber-100">API kľúč nie je nastavený</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Pre používanie AI funkcií si nastavte OpenAI API kľúč v <a href="{{ route('profile.show') }}" class="underline font-medium">Profile > AI Nastavenia</a></p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Topic -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Téma knihy</label>
                            <textarea x-model="topic" placeholder="Napríklad: Ako si vybudovať úspešný online biznis..." 
                                      class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none" 
                                      rows="2"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Genre -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Žáner</label>
                                <select x-model="genre" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="fiction">Beletria</option>
                                    <option value="non-fiction">Náučná</option>
                                    <option value="fantasy">Fantasy</option>
                                    <option value="romance">Romantická</option>
                                    <option value="thriller">Thriller</option>
                                    <option value="sci-fi">Sci-Fi</option>
                                    <option value="educational">Vzdelávacia</option>
                                    <option value="biography">Biografia</option>
                                </select>
                            </div>
                            <!-- Style -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Štýl písania</label>
                                <select x-model="style" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="formal">Formálny</option>
                                    <option value="casual">Neformálny</option>
                                    <option value="academic">Akademický</option>
                                    <option value="poetic">Poetický</option>
                                    <option value="technical">Technický</option>
                                </select>
                            </div>
                        </div>

                        <!-- Chapters -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Počet kapitol</label>
                            <div class="flex items-center gap-4">
                                <input type="range" x-model="chapters" min="1" max="20" class="flex-1 accent-purple-600">
                                <span class="text-sm font-semibold text-slate-900 dark:text-white w-8 text-center" x-text="chapters"></span>
                            </div>
                        </div>

                        <!-- Error -->
                        <div x-show="error" class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl" style="display: none;">
                            <p class="text-sm text-red-700 dark:text-red-300" x-text="error"></p>
                        </div>

                        <!-- Progress -->
                        <div x-show="loading" class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 animate-spin text-purple-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <p class="text-sm text-slate-600 dark:text-slate-400" x-text="progress || 'Generujem knihu...'"></p>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-2">
                            <button @click="generateBook()" :disabled="loading || !topic.trim()"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-purple-500/25 flex items-center justify-center gap-2">
                                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-show="!loading">✨ Vygenerovať knihu</span>
                                <span x-show="loading">Generujem...</span>
                            </button>
                            <button @click="show = false" :disabled="loading"
                                    class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition disabled:opacity-50">
                                Zrušiť
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    window.generateBook = async function() {
        const modal = window.aiWriteModal;
        if (!modal || modal.loading || !modal.topic.trim()) return;
        
        modal.loading = true;
        modal.error = '';
        modal.progress = 'Začínam generovať knihu...';
        
        try {
            const response = await fetch('/api/ai/generate-entire-book/{{ $book->id }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    topic: modal.topic,
                    genre: modal.genre,
                    style: modal.style,
                    chapters: modal.chapters
                })
            });
            
            const data = await response.json();
            
            if (data.error) {
                modal.error = data.error;
                window.showToast(data.error, 'error');
            } else {
                modal.progress = '✅ Hotovo! Načítavam...';
                window.showToast('Kniha vygenerovaná! ' + data.count + ' kapitol', 'success');
                modal.show = false;
                setTimeout(() => location.reload(), 1000);
            }
        } catch (e) {
            modal.error = 'Chyba pripojenia: ' + e.message;
            window.showToast('Chyba: ' + e.message, 'error');
        } finally {
            modal.loading = false;
        }
    };
    </script>
</div>
