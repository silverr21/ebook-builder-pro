@extends('layouts.editor')

@section('title', $book->title . ' - Editor')

@section('content')
<x-toast />
<x-confirm-modal id="chapter-delete-modal" title="Zmazať kapitolu" message="Naozaj chcete zmazať túto kapitolu? Táto akcia je nenávratná." confirmText="Zmazať" icon="🗑️" />
<x-confirm-modal id="section-delete-modal" title="Zmazať sekciu" message="Naozaj chcete zmazať túto sekciu?" confirmText="Zmazať" icon="📄" />

<x-ai-write-modal :book="$book" />

<div class="h-full flex flex-col" x-data="editor()" x-init="init()">
    <!-- Editor Toolbar -->
    <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-4 py-2 flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-2">
            <!-- Edit mode toggle -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-700 rounded-lg p-0.5">
                <button @click="editMode = 'visual'" class="px-3 py-1.5 text-xs font-medium rounded-md transition"
                    :class="editMode === 'visual' ? 'bg-white dark:bg-slate-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                    ✏️ Vizualny
                </button>
                <button @click="editMode = 'html'" class="px-3 py-1.5 text-xs font-medium rounded-md transition"
                    :class="editMode === 'html' ? 'bg-white dark:bg-slate-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                    📝 HTML
                </button>
                <button @click="editMode = 'preview'" class="px-3 py-1.5 text-xs font-medium rounded-md transition"
                    :class="editMode === 'preview' ? 'bg-white dark:bg-slate-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'">
                    👁️ Náhľad
                </button>
            </div>

            <!-- Save Status -->
            <div class="flex items-center gap-2 ml-2">
                <span x-show="saveStatus === 'saved'" class="flex items-center gap-1 text-xs text-green-600 dark:text-green-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Uložené
                </span>
                <span x-show="saveStatus === 'saving'" class="flex items-center gap-1 text-xs text-amber-600 dark:text-amber-400">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Ukladám...
                </span>
                <span x-show="saveStatus === 'unsaved'" class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                    ● Neuložené
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button @click="saveContent()" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293z"/></svg>
                Uložiť
            </button>
            <button @click="openAiAssistant()" class="px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition flex items-center gap-1.5 shadow-sm">
                🤖 AI
            </button>
        </div>
    </div>

    <!-- Main Editor Grid -->
    <div class="flex-1 grid grid-cols-12 gap-0 overflow-hidden">
        <!-- Left Panel - Chapters -->
        <div class="col-span-3 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
            <!-- Chapters Header -->
            <div class="p-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span>📖</span> Kapitoly
                        <span class="text-xs font-normal text-slate-400">({{ $book->chapters->count() }})</span>
                    </h3>
                    <button @click="showNewChapterModal = true" class="p-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Nová kapitola">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
                <button @click="aiGenerateChapter()" class="w-full flex items-center justify-center gap-1.5 px-2 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-medium rounded-lg hover:from-purple-600 hover:to-pink-600 transition shadow-sm">
                    🤖 Generovať kapitolu
                </button>
            </div>

            <!-- Chapters List -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/50">
                @forelse($book->chapters as $chapter)
                <div @click="selectChapter({{ $chapter->id }})" 
                     class="group relative px-3 py-3 cursor-pointer transition"
                     :class="{ 'bg-blue-50 dark:bg-blue-900/20': selectedChapter === {{ $chapter->id }} }">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium line-clamp-1" 
                                :class="selectedChapter === {{ $chapter->id }} ? 'text-blue-700 dark:text-blue-300' : 'text-slate-700 dark:text-slate-300'">
                                {{ $chapter->title }}
                            </h4>
                            @php $sectionCount = $chapter->sections()->count(); @endphp
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1">
                                <span>{{ $sectionCount }} sekcií</span>
                                <span>•</span>
                                <span>{{ $chapter->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                        <button @click.stop="deleteChapter({{ $chapter->id }})" 
                                class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg opacity-0 group-hover:opacity-100 transition ml-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="text-3xl mb-3">📚</div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Zatiaľ žiadne kapitoly</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Vytvorte prvú kapitolu pre začatie</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Center Panel - Editor Area -->
        <div class="col-span-6 flex flex-col overflow-hidden bg-white dark:bg-slate-800">
            <!-- Section Tabs (within selected chapter) -->
            <div x-show="selectedChapter > 0" class="border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center overflow-x-auto scrollbar-hide gap-0.5 px-2">
                    <template x-for="(section, index) in currentSections" :key="section.id">
                        <button @click="selectSectionById(section.id)" 
                                class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium border-b-2 transition whitespace-nowrap group"
                                :class="selectedSection === section.id ? 'border-blue-500 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                            <span x-text="section.title || `Sekcia ${index + 1}`" class="max-w-[120px] truncate"></span>
                            <button @click.stop="deleteSectionById(section.id)" class="p-0.5 opacity-0 group-hover:opacity-100 hover:text-red-500 transition rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </button>
                    </template>
                    <button @click="addSection()" class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition border-b-2 border-transparent hover:border-blue-300">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        <span>Nová</span>
                    </button>
                </div>
            </div>

            <!-- Visual Editor -->
            <div x-show="editMode === 'visual'" class="flex-1 flex flex-col overflow-hidden" style="display: none;">
                <!-- Formatting Toolbar (visible in visual mode) -->
                <div id="toolbar" class="px-3 py-2 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex flex-wrap items-center gap-1" x-show="selectedChapter > 0">
                    <span class="ql-formats flex items-center gap-1">
                        <select class="ql-header text-xs border border-slate-300 dark:border-slate-600 rounded px-1 py-0.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                            <option value="1">Nadpis 1</option>
                            <option value="2">Nadpis 2</option>
                            <option value="3">Nadpis 3</option>
                            <option value="">Normálny</option>
                        </select>
                    </span>
                    <div class="w-px h-5 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <span class="ql-formats flex items-center gap-0.5">
                        <button class="ql-bold w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 font-bold text-sm" title="Tučné"><b>B</b></button>
                        <button class="ql-italic w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 italic text-sm" title="Kurzíva"><i>I</i></button>
                        <button class="ql-underline w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 underline text-sm" title="Podčiarknuté"><u>U</u></button>
                        <button class="ql-strike w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 line-through text-sm" title="Preškrtnuté"><s>S</s></button>
                    </span>
                    <div class="w-px h-5 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <span class="ql-formats flex items-center gap-0.5">
                        <select class="ql-color text-xs border border-slate-300 dark:border-slate-600 rounded px-1 py-0.5 bg-white dark:bg-slate-700 w-7 h-7"></select>
                        <select class="ql-background text-xs border border-slate-300 dark:border-slate-600 rounded px-1 py-0.5 bg-white dark:bg-slate-700 w-7 h-7"></select>
                    </span>
                    <div class="w-px h-5 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <span class="ql-formats flex items-center gap-0.5">
                        <button class="ql-list w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600" value="ordered" title="Číslovaný zoznam">🔢</button>
                        <button class="ql-list w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600" value="bullet" title="Odrážky">•</button>
                        <button class="ql-indent w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600" value="-1" title="Odsadenie vľavo">↩️</button>
                        <button class="ql-indent w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600" value="+1" title="Odsadenie vpravo">↪️</button>
                    </span>
                    <div class="w-px h-5 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <span class="ql-formats flex items-center gap-0.5">
                        <button class="ql-image w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 text-sm" title="Obrázok">🖼️</button>
                        <button class="ql-link w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 text-sm" title="Odkaz">🔗</button>
                        <button class="ql-code-block w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 text-sm" title="Kód">&lt;/&gt;</button>
                    </span>
                    <div class="w-px h-5 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                    <span class="ql-formats flex items-center gap-0.5">
                        <button class="ql-clean w-7 h-7 flex items-center justify-center rounded hover:bg-slate-200 dark:hover:bg-slate-600 text-sm" title="Vyčistiť formátovanie">🧹</button>
                    </span>
                </div>

                <!-- Quill Editor -->
                <div id="editor" class="flex-1 overflow-y-auto" style="height: auto; min-height: 300px;"></div>
            </div>

            <!-- HTML Editor -->
            <div x-show="editMode === 'html'" class="flex-1 flex flex-col overflow-hidden" style="display: none;">
                <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <p class="text-xs text-slate-500 dark:text-slate-400">📝 Priama editácia HTML kódu</p>
                </div>
                <textarea x-model="htmlContent" @input="onHtmlChange()" 
                          class="flex-1 w-full p-4 font-mono text-sm bg-slate-900 dark:bg-slate-950 text-green-400 border-0 resize-none focus:outline-none focus:ring-0" 
                          style="tab-size: 2;"
                          spellcheck="false"></textarea>
            </div>

            <!-- Preview Mode -->
            <div x-show="editMode === 'preview'" class="flex-1 overflow-y-auto p-6" style="display: none;">
                <div class="max-w-3xl mx-auto prose prose-slate dark:prose-invert" x-html="previewHtml"></div>
            </div>

            <!-- Empty State -->
            <div x-show="selectedChapter === 0 || selectedChapter === null" class="flex-1 flex items-center justify-center">
                <div class="text-center p-8">
                    <div class="text-6xl mb-4">📖</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Vitajte v editore</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-md">Vyberte kapitolu zľava alebo vytvorte novú pre začatie písania</p>
                    <button @click="showNewChapterModal = true" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                        ✨ Vytvoriť prvú kapitolu
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Panel - AI Tools & Stats -->
        <div class="col-span-3 bg-white dark:bg-slate-800 border-l border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
            <!-- AI Loading Overlay -->
            <div x-show="showAiLoading" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-[9998]" style="display: none;">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-2xl flex flex-col items-center">
                    <svg class="w-12 h-12 animate-spin text-purple-600 mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">AI spracúva požiadavku...</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Toto môže trvať niekoľko sekúnd</p>
                </div>
            </div>

            <!-- AI Result Panel -->
            <div x-show="showAiResult" class="absolute inset-0 z-50 bg-white dark:bg-slate-800 overflow-y-auto" style="display: none;" x-init="() => {}">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-slate-900 dark:text-white">🤖 Výsledok AI</h4>
                        <div class="flex gap-2">
                            <button @click="applyAiResult()" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                                ✅ Použiť
                            </button>
                            <button @click="showAiResult = false" class="px-3 py-1.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                ✕ Zavrieť
                            </button>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4 text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed" x-text="aiResult"></div>
                </div>
            </div>

            <!-- AI Tools Panel -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span>🤖</span> AI Nástroje
                    </h4>
                    <span class="text-xs text-slate-400">v2.0</span>
                </div>

                <div class="space-y-1.5">
                    <!-- Text Tools Group -->
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5 mt-2">Textové nástroje</p>
                    <button @click="aiExpand()" :disabled="isLoading" 
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 disabled:opacity-50">
                        <span>📖</span> Rozšíriť text
                    </button>
                    <button @click="aiShorten()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 disabled:opacity-50">
                        <span>✂️</span> Skrátiť text
                    </button>
                    <button @click="aiRewrite()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 disabled:opacity-50">
                        <span>✏️</span> Prepísať text
                    </button>
                    <button @click="aiContinue()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 disabled:opacity-50">
                        <span>➡️</span> Pokračovať v písaní
                    </button>

                    <!-- Analysis Tools -->
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5 mt-3">Analýza</p>
                    <button @click="aiCheckGrammar()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 disabled:opacity-50">
                        <span>📝</span> Kontrola gramatiky
                    </button>

                    <!-- Generation Tools -->
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5 mt-3">Generovanie</p>
                    <button @click="aiGenerateTitle()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 text-green-700 dark:text-green-300 disabled:opacity-50">
                        <span>💡</span> Generovať názov
                    </button>
                    <button @click="aiGenerateChapter()" :disabled="isLoading"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-300 disabled:opacity-50">
                        <span>📖</span> Generovať kapitolu
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <h4 class="font-semibold text-sm text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                    <span>📊</span> Štatistika
                </h4>
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-2.5 text-center">
                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400" x-text="stats.words">0</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Slov</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-2.5 text-center">
                        <div class="text-lg font-bold text-purple-600 dark:text-purple-400" x-text="stats.characters">0</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Znakov</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-2.5 text-center">
                        <div class="text-lg font-bold text-green-600 dark:text-green-400" x-text="stats.paragraphs">0</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Odstavcov</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-2.5 text-center">
                        <div class="text-lg font-bold text-amber-600 dark:text-amber-400" x-text="stats.readingTime">0</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Min. čítania</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="p-4">
                <h4 class="font-semibold text-sm text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                    <span>⚡</span> Akcie
                </h4>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="undo()" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-xl hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        ↩️ Späť
                    </button>
                    <button @click="redo()" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-xl hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        ↪️ Vpred
                    </button>
                    <button @click="insertImage()" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-xl hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        🖼️ Obrázok
                    </button>
                    <button @click="insertLink()" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-xl hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                        🔗 Odkaz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Chapter Modal -->
    <div x-show="showNewChapterModal" @click.self="showNewChapterModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6 w-96" @click.stop>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">📖 Nová kapitola</h2>
            <input x-model="newChapterTitle" type="text" placeholder="Názov kapitoly" 
                   class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition mb-4"
                   @keydown.enter="createChapter()"
                   x-init="$el.focus()">
            <div class="flex gap-3 justify-end">
                <button @click="showNewChapterModal = false" class="px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Zrušiť
                </button>
                <button @click="createChapter()" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                    ✨ Vytvoriť
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let quill;

function editor() {
    return {
        selectedChapter: {{ $book->chapters->first()?->id ?? 0 }},
        selectedSection: null,
        currentSections: [],
        editMode: 'visual',
        showNewChapterModal: false,
        showAiLoading: false,
        showAiResult: false,
        aiResult: '',
        newChapterTitle: '',
        isLoading: false,
        saveStatus: 'saved',
        htmlContent: '',
        previewHtml: '',
        stats: { words: 0, characters: 0, paragraphs: 0, readingTime: 0 },

        isSaving: false,
        autoSaveTimer: null,

        init() {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: '#toolbar',
                    clipboard: { matchVisual: false },
                    keyboard: { bindings: { tab: false } }
                },
                placeholder: 'Začnite písať...'
            });

            quill.on('text-change', () => {
                this.updateStats();
                this.markUnsaved();
            });

            if (this.selectedChapter > 0) {
                this.loadChapter(this.selectedChapter);
            }
        },

        markUnsaved() {
            if (this.saveStatus === 'saving') return;
            this.saveStatus = 'unsaved';
            clearTimeout(this.autoSaveTimer);
            this.autoSaveTimer = setTimeout(() => this.saveContent(), 30000);
        },

        selectChapter(chapterId) {
            this.selectedChapter = chapterId;
            this.loadChapter(chapterId);
        },

        async loadChapter(chapterId) {
            if (!chapterId || chapterId === 0) return;
            try {
                const response = await fetch(`/api/chapters/${chapterId}`);
                const data = await response.json();
                
                this.currentSections = data.sections || [];
                this.selectedSection = this.currentSections.length > 0 ? this.currentSections[0].id : null;
                
                if (this.selectedSection) {
                    this.loadSectionContent(this.selectedSection);
                } else {
                    quill.setText('');
                    this.htmlContent = '';
                    this.previewHtml = '';
                    this.updateStats();
                }
            } catch (e) {
                console.error('Failed to load chapter:', e);
                showToast('Chyba pri načítaní kapitoly', 'error');
            }
        },

        async loadSectionContent(sectionId) {
            try {
                const response = await fetch(`/api/sections/${sectionId}`);
                const data = await response.json();
                
                if (data.content) {
                    try {
                        const delta = JSON.parse(data.content);
                        // Check if it's a Quill delta (has ops array) or plain content
                        if (delta.ops) {
                            quill.setContents(delta);
                        } else {
                            quill.setText(data.content);
                        }
                    } catch (e) {
                        quill.setText(data.content);
                    }
                } else {
                    quill.setText('');
                }
                this.htmlContent = quill.root.innerHTML;
                this.previewHtml = quill.root.innerHTML;
                this.updateStats();
            } catch (e) {
                console.error('Failed to load section:', e);
            }
        },

        selectSectionById(sectionId) {
            this.selectedSection = sectionId;
            this.loadSectionContent(sectionId);
        },

        async addSection() {
            if (!this.selectedChapter) return;
            try {
                const response = await fetch('/api/sections', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ chapter_id: this.selectedChapter, content: '[]' })
                });
                const section = await response.json();
                this.currentSections.push(section);
                this.selectedSection = section.id;
                quill.setText('');
                this.updateStats();
                showToast('Nová sekcia vytvorená', 'success');
            } catch (e) {
                showToast('Chyba: ' + e.message, 'error');
            }
        },

        async deleteSectionById(sectionId) {
            if (!confirm('Zmazať túto sekciu?')) return;
            try {
                await fetch(`/api/sections/${sectionId}`, { method: 'DELETE' });
                this.currentSections = this.currentSections.filter(s => s.id !== sectionId);
                if (this.selectedSection === sectionId) {
                    this.selectedSection = this.currentSections.length > 0 ? this.currentSections[0].id : null;
                    if (this.selectedSection) {
                        this.loadSectionContent(this.selectedSection);
                    } else {
                        quill.setText('');
                    }
                }
                showToast('Sekcia zmazaná', 'success');
            } catch (e) {
                showToast('Chyba: ' + e.message, 'error');
            }
        },

        async saveContent() {
            if (!this.selectedChapter) {
                showToast('Vyberte kapitolu', 'warning');
                return;
            }
            if (this.isSaving) return; // Prevent parallel saves
            
            this.isSaving = true;
            this.saveStatus = 'saving';
            const content = JSON.stringify(quill.getContents());

            try {
                if (!this.selectedSection) {
                    // Create new section
                    const response = await fetch('/api/sections', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ chapter_id: this.selectedChapter, content })
                    });
                    const data = await response.json();
                    this.selectedSection = data.id;
                    this.currentSections.push(data);
                } else {
                    // Update existing section
                    const response = await fetch(`/api/sections/${this.selectedSection}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ content })
                    });
                    if (!response.ok) throw new Error('Save failed');
                }
                this.saveStatus = 'saved';
                showToast('Obsah uložený!', 'success');
            } catch (e) {
                this.saveStatus = 'unsaved';
                showToast('Chyba: ' + e.message, 'error');
            } finally {
                this.isSaving = false;
            }
        },

        async createChapter() {
            if (!this.newChapterTitle.trim()) {
                showToast('Zadajte názov kapitoly', 'warning');
                return;
            }
            try {
                const response = await fetch('{{ route("api.chapters.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        book_id: {{ $book->id }},
                        title: this.newChapterTitle
                    })
                });
                const data = await response.json();
                this.showNewChapterModal = false;
                this.newChapterTitle = '';
                showToast('Kapitola vytvorená!', 'success');
                location.reload();
            } catch (e) {
                showToast('Chyba: ' + e.message, 'error');
            }
        },

        deleteChapter(chapterId) {
            const modal = window['chapter-delete-modal'];
            if (modal) {
                modal.open(async () => {
                    try {
                        await fetch(`/api/chapters/${chapterId}`, { method: 'DELETE' });
                        showToast('Kapitola zmazaná', 'success');
                        location.reload();
                    } catch (e) {
                        showToast('Chyba: ' + e.message, 'error');
                    }
                });
            } else if (confirm('Naozaj chcete zmazať túto kapitolu?')) {
                fetch(`/api/chapters/${chapterId}`, { method: 'DELETE' })
                    .then(() => location.reload());
            }
        },

        onHtmlChange() {
            this.previewHtml = this.htmlContent;
            // Update Quill from HTML when switching back to visual mode
            if (quill && this.editMode === 'html') {
                try {
                    quill.root.innerHTML = this.htmlContent;
                    this.updateStats();
                } catch(e) {}
            }
        },

        // ===== AI Functions =====

        async callAiApi(endpoint, body) {
            this.isLoading = true;
            this.showAiLoading = true;
            try {
                const response = await fetch(`/api/ai/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                    },
                    body: JSON.stringify(body)
                });
                const data = await response.json();
                if (data.error) {
                    showToast('Chyba AI: ' + data.error, 'error');
                    return null;
                }
                return data;
            } catch (e) {
                showToast('Chyba pripojenia: ' + e.message, 'error');
                return null;
            } finally {
                this.isLoading = false;
                this.showAiLoading = false;
            }
        },

        showAiResultPanel(result) {
            this.aiResult = result;
            this.showAiResult = true;
        },

        applyAiResult() {
            if (this.aiResult && quill) {
                quill.setText(this.aiResult);
                this.updateStats();
                this.showAiResult = false;
                showToast('AI výsledok aplikovaný!', 'success');
            }
        },

        async aiExpand() {
            const text = quill.getText();
            if (!text.trim()) { showToast('Najprv napíšte text', 'warning'); return; }
            const data = await this.callAiApi('expand-text', { text, ratio: 1.5 });
            if (data && data.expanded) {
                this.showAiResultPanel(data.expanded);
            }
        },

        async aiShorten() {
            const text = quill.getText();
            if (!text.trim()) { showToast('Najprv napíšte text', 'warning'); return; }
            const data = await this.callAiApi('shorten-text', { text, ratio: 0.7 });
            if (data && data.shortened) {
                this.showAiResultPanel(data.shortened);
            }
        },

        async aiRewrite() {
            const text = quill.getText();
            if (!text.trim()) { showToast('Najprv napíšte text', 'warning'); return; }
            const style = prompt('Vyberte štýl:\n• formal\n• casual\n• academic\n• poetic\n• technical', 'formal');
            if (!style) return;
            const data = await this.callAiApi('rewrite-text', { text, style });
            if (data && data.rewritten) {
                this.showAiResultPanel(data.rewritten);
            }
        },

        async aiContinue() {
            const text = quill.getText();
            if (!text.trim()) { showToast('Najprv napíšte text', 'warning'); return; }
            const data = await this.callAiApi('continue-writing', { text, words: 500 });
            if (data && data.continued) {
                this.showAiResultPanel(data.continued);
            }
        },

        async aiCheckGrammar() {
            const text = quill.getText();
            if (!text.trim()) { showToast('Najprv napíšte text', 'warning'); return; }
            const data = await this.callAiApi('check-grammar', { text });
            if (data && data.issues) {
                const issues = typeof data.issues === 'string' ? JSON.parse(data.issues) : data.issues;
                if (Array.isArray(issues) && issues.length > 0) {
                    let message = '🔍 Nájdené problémy:\n';
                    issues.forEach((issue, i) => {
                        message += `\n${i + 1}. ${issue.message || issue.description || JSON.stringify(issue)}`;
                    });
                    showToast(message, 'info');
                } else {
                    showToast('✅ Gramatika je v poriadku!', 'success');
                }
            }
        },

        async aiGenerateTitle() {
            const topic = prompt('Zadajte tému knihy:');
            if (!topic) return;
            const genre = prompt('Zadajte žáner (fiction, fantasy, romance, thriller, educational):', 'fiction');
            if (!genre) return;
            const data = await this.callAiApi('generate-title', { topic, genre });
            if (data && data.title) {
                this.showAiResultPanel(data.title);
            }
        },

        async aiGenerateChapter() {
            const chapterTitle = prompt('Zadajte tému kapitoly:');
            if (!chapterTitle) return;
            
            const data = await this.callAiApi('generate-chapter/{{ $book->id }}', {
                title: chapterTitle,
                topic: chapterTitle,
                word_count: 2000
            });
            
            if (data && data.chapter) {
                showToast('Kapitola vygenerovaná!', 'success');
                location.reload();
            }
        },

        openAiAssistant() {
            // Scroll to AI panel
            document.querySelector('.col-span-3')?.scrollIntoView({ behavior: 'smooth' });
        },

        updateStats() {
            if (!quill) return;
            const text = quill.getText();
            const words = text.trim() ? text.trim().split(/\s+/).length : 0;
            this.stats.words = words;
            this.stats.characters = text.length;
            this.stats.paragraphs = text.split('\n').filter(p => p.trim()).length;
            this.stats.readingTime = Math.max(1, Math.ceil(words / 200));
            
            // Update HTML and preview
            if (quill.root) {
                this.htmlContent = quill.root.innerHTML;
                this.previewHtml = quill.root.innerHTML;
            }
        },

        undo() { if (quill) quill.history.undo(); },
        redo() { if (quill) quill.history.redo(); },
        
        insertImage() {
            const url = prompt('Zadajte URL obrázka:');
            if (url && quill) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
            }
        },
        
        insertLink() {
            const url = prompt('Zadajte URL odkazu:');
            if (url && quill) {
                const range = quill.getSelection(true);
                quill.format('link', url);
            }
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const app = editor();
    app.init();
    window.editorApp = app;
});
</script>
@endsection
