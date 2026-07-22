@extends('layouts.app')

@section('title', 'AI Asistent - eBook Builder Pro')

@section('content')
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-slate-900 dark:to-slate-800 py-12" x-data="aiExplorer()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="text-6xl mb-4">🤖</div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">AI Asistent</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Využite silu umelej inteligencie pre tvorbu obsahu, korektúry a kreatívne nápady
            </p>
        </div>

        <!-- API Key Warning -->
        <div x-show="!hasApiKey" class="max-w-2xl mx-auto mb-8 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
            <div class="flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <p class="text-sm text-amber-800 dark:text-amber-200">
                    Pre používanie AI funkcií nastavte OpenAI API kľúč v 
                    <a href="{{ route('profile.show') }}" class="font-medium underline hover:text-amber-900">Profile > AI Nastavenia</a>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Generate Title -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">💡</div>
                    <span x-show="loading === 'title'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Generujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Generovať názov</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vygenerujte kreatívne názvy kníh na základe témy a žánru</p>
                <div class="space-y-2">
                    <input type="text" x-model="titleTopic" placeholder="Téma knihy"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    <input type="text" x-model="titleGenre" placeholder="Žáner (fiction, fantasy, romance...)"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    <button @click="generateTitle()" :disabled="loading || !titleTopic || !titleGenre"
                            class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2">
                        <svg x-show="loading === 'title'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading !== 'title'">✨ Generovať</span>
                        <span x-show="loading === 'title'">Generujem...</span>
                    </button>
                    <div x-show="resultTitle" class="mt-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-sm text-purple-800 dark:text-purple-200" x-text="resultTitle"></div>
                </div>
            </div>

            <!-- SEO Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">🔍</div>
                    <span x-show="loading === 'seo'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Generujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">SEO Optimalizácia</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte SEO popisy a kľúčové slová pre lepšiu viditeľnosť</p>
                <div class="space-y-2">
                    <select x-model="seoBookId" class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                        <option value="">Vyberte knihu...</option>
                        @foreach(auth()->user()->books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                    <button @click="generateSEO()" :disabled="loading || !seoBookId"
                            class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition flex items-center justify-center gap-2">
                        <svg x-show="loading === 'seo'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading !== 'seo'">✨ Generovať SEO</span>
                        <span x-show="loading === 'seo'">Generujem...</span>
                    </button>
                    <div x-show="resultSeo" class="mt-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-sm">
                        <p class="font-medium text-purple-800 dark:text-purple-200 mb-1" x-text="resultSeo?.title"></p>
                        <p class="text-purple-700 dark:text-purple-300" x-text="resultSeo?.description"></p>
                        <p class="text-xs text-purple-500 dark:text-purple-400 mt-1" x-text="resultSeo?.keywords ? 'Kľúčové slová: ' + resultSeo.keywords : ''"></p>
                    </div>
                </div>
            </div>

            <!-- FAQ Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">❓</div>
                    <span x-show="loading === 'faq'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Generujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Generovať FAQ</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte zoznam častých otázok pre svoju knihu</p>
                <div class="space-y-2">
                    <input type="text" x-model="faqTopic" placeholder="Téma FAQ"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    <input type="number" x-model="faqCount" placeholder="Počet otázok (5-50)" min="5" max="50"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    <button @click="generateFAQ()" :disabled="loading || !faqTopic"
                            class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition flex items-center justify-center gap-2">
                        <svg x-show="loading === 'faq'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading !== 'faq'">✨ Generovať FAQ</span>
                        <span x-show="loading === 'faq'">Generujem...</span>
                    </button>
                    <div x-show="resultFaq" class="mt-3 space-y-2">
                        <template x-for="(item, i) in resultFaq" :key="i">
                            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-xs text-purple-800 dark:text-purple-200">
                                <p class="font-medium" x-text="'Q: ' + item.question"></p>
                                <p class="mt-0.5" x-text="'A: ' + item.answer"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Glossary Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">📖</div>
                    <span x-show="loading === 'glossary'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Generujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Slovník pojmov</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte slovník odborných pojmov z textu</p>
                <div class="space-y-2">
                    <textarea x-model="glossaryText" placeholder="Vložte text pre extrahovanie pojmov..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition resize-none"></textarea>
                    <button @click="generateGlossary()" :disabled="loading || !glossaryText"
                            class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition flex items-center justify-center gap-2">
                        <svg x-show="loading === 'glossary'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading !== 'glossary'">✨ Generovať slovník</span>
                        <span x-show="loading === 'glossary'">Generujem...</span>
                    </button>
                    <div x-show="resultGlossary" class="mt-3 space-y-1">
                        <template x-for="(item, i) in resultGlossary" :key="i">
                            <div class="flex items-start gap-2 p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <span class="font-medium text-xs text-purple-800 dark:text-purple-200" x-text="item.term + ':'"></span>
                                <span class="text-xs text-purple-700 dark:text-purple-300" x-text="item.definition"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Summary Generator -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">📝</div>
                    <span x-show="loading === 'summary'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Zhrňujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Zhrnutie textu</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Vytvorte stručné zhrnutie dlhého textu</p>
                <div class="space-y-2">
                    <textarea x-model="summaryText" placeholder="Vložte text na zhrnutie..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition resize-none"></textarea>
                    <div class="flex gap-2">
                        <input type="number" x-model="summarySentences" placeholder="Počet viet" min="3" max="20"
                            class="flex-1 px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                        <button @click="generateSummary()" :disabled="loading || !summaryText"
                                class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition flex items-center gap-2">
                            <svg x-show="loading === 'summary'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-show="loading !== 'summary'">Zhrnúť ✨</span>
                            <span x-show="loading === 'summary'">Zhrňujem...</span>
                        </button>
                    </div>
                    <div x-show="resultSummary" class="mt-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-sm text-purple-800 dark:text-purple-200" x-text="resultSummary"></div>
                </div>
            </div>

            <!-- Text Analysis -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-3xl">📊</div>
                    <span x-show="loading === 'grammar'" class="flex items-center gap-1 text-xs text-purple-600">
                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Analyzujem...
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Kontrola gramatiky</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Skontrolujte text z hľadiska gramatických chýb</p>
                <div class="space-y-2">
                    <textarea x-model="grammarText" placeholder="Vložte text na kontrolu..." rows="3"
                        class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition resize-none"></textarea>
                    <button @click="checkGrammar()" :disabled="loading || !grammarText"
                            class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition flex items-center justify-center gap-2">
                        <svg x-show="loading === 'grammar'" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="loading !== 'grammar'">Skontrolovať 📊</span>
                        <span x-show="loading === 'grammar'">Kontrolujem...</span>
                    </button>
                    <div x-show="resultGrammar" class="mt-3 space-y-1">
                        <template x-for="(issue, i) in resultGrammar" :key="i">
                            <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-xs">
                                <p class="text-red-800 dark:text-red-200" x-text="issue.message || issue.description"></p>
                                <p x-show="issue.suggestion" class="text-red-600 dark:text-red-400 mt-0.5" x-text="'→ ' + issue.suggestion"></p>
                            </div>
                        </template>
                        <p x-show="resultGrammar.length === 0" class="text-green-600 dark:text-green-400 text-xs font-medium">✅ Gramatika je v poriadku!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Tips -->
        <div class="mt-12 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-6">
            <div class="flex items-start gap-4">
                <span class="text-3xl">🎯</span>
                <div>
                    <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">Tipy pre prácu s AI</h3>
                    <ul class="space-y-2 text-sm text-purple-700 dark:text-purple-300">
                        <li>✓ Buďte čo najkonkrétnejší v požiadavkách pre lepšie výsledky</li>
                        <li>✓ Kombinujte AI generovaný obsah s vlastnými úpravami</li>
                        <li>✓ Vždy skontrolujte a upravte vygenerovaný text</li>
                        <li>✓ Používajte viacero nástrojov pre komplexnú tvorbu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function aiExplorer() {
    return {
        hasApiKey: {{ auth()->user()->hasAiApiKey() ? 'true' : 'false' }},
        loading: false,
        
        // Title
        titleTopic: '',
        titleGenre: '',
        resultTitle: '',
        
        // SEO
        seoBookId: '',
        resultSeo: null,
        
        // FAQ
        faqTopic: '',
        faqCount: 10,
        resultFaq: null,
        
        // Glossary
        glossaryText: '',
        resultGlossary: null,
        
        // Summary
        summaryText: '',
        summarySentences: 5,
        resultSummary: '',
        
        // Grammar
        grammarText: '',
        resultGrammar: null,

        init() {},

        async callApi(endpoint, body, loadingKey) {
            this.loading = loadingKey || endpoint;
            try {
                const response = await fetch(`/api/ai/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(body)
                });
                const data = await response.json();
                if (data.error) {
                    showToast('Chyba: ' + data.error, 'error');
                    return null;
                }
                return data;
            } catch (e) {
                showToast('Chyba pripojenia: ' + e.message, 'error');
                return null;
            } finally {
                this.loading = false;
            }
        },

        async generateTitle() {
            const data = await this.callApi('generate-title', { topic: this.titleTopic, genre: this.titleGenre }, 'title');
            if (data) this.resultTitle = data.title;
        },

        async generateSEO() {
            const data = await this.callApi(`generate-seo/${this.seoBookId}`, {}, 'seo');
            if (data) this.resultSeo = data;
        },

        async generateFAQ() {
            const data = await this.callApi('generate-faq', { topic: this.faqTopic, questions: parseInt(this.faqCount) || 10 }, 'faq');
            if (data) this.resultFaq = Array.isArray(data) ? data : (data.faq || data.questions || []);
        },

        async generateGlossary() {
            const data = await this.callApi('generate-glossary', { text: this.glossaryText }, 'glossary');
            if (data) this.resultGlossary = Array.isArray(data) ? data : (data.glossary || data.terms || []);
        },

        async generateSummary() {
            const data = await this.callApi('generate-summary', { text: this.summaryText, sentence_count: parseInt(this.summarySentences) || 5 }, 'summary');
            if (data) this.resultSummary = typeof data === 'string' ? data : (data.summary || data.content || '');
        },

        async checkGrammar() {
            const data = await this.callApi('check-grammar', { text: this.grammarText }, 'grammar');
            if (data) {
                this.resultGrammar = Array.isArray(data) ? data : (data.issues || data.errors || []);
            }
        }
    }
}
</script>
@endsection
