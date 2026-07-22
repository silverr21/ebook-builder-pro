@extends('layouts.editor')

@section('title', $book->title . ' - Editor')

@section('content')
<div class="h-full flex flex-col p-6" x-data="editor()">
    <div class="flex-1 grid grid-cols-12 gap-6 h-full">
        <!-- Left Panel - Chapters -->
        <div class="col-span-3 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">Kapitoly</h3>
                    <button @click="showNewChapterModal = true" class="text-blue-600 hover:text-blue-700" title="Nová kapitola">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="flex gap-2">
                    <button @click="aiGenerateChapter()" class="flex-1 px-2 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 transition">
                        🤖 Generovat
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                @forelse($book->chapters as $chapter)
                <div @click="selectChapter({{ $chapter->id }})" class="group p-3 border-b border-slate-100 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition" :class="{ 'bg-blue-100 dark:bg-blue-900': selectedChapter === {{ $chapter->id }} }">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-sm line-clamp-2">{{ $chapter->title }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $chapter->sections()->count() }} sekcí</p>
                        </div>
                        <button @click.stop="deleteChapter({{ $chapter->id }})" class="text-red-600 hover:text-red-700 opacity-0 group-hover:opacity-100">
                            🗑️
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                    <p>Žádné kapitoly</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Center Panel - Editor -->
        <div class="col-span-6 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-lg">Editor</h3>
                    <div class="flex gap-2">
                        <button @click="saveContent()" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition flex items-center gap-1">
                            <span>💾</span> Uložit
                        </button>
                    </div>
                </div>

                <!-- Quill Toolbar -->
                <div id="toolbar" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded mb-2">
                    <span class="ql-formats">
                        <select class="ql-header" defaultValue=""></select>
                        <select class="ql-font"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-strike"></button>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-color"></select>
                        <select class="ql-background"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                        <button class="ql-indent" value="-1"></button>
                        <button class="ql-indent" value="+1"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-image"></button>
                        <button class="ql-video"></button>
                        <button class="ql-link"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-clean"></button>
                    </span>
                </div>
            </div>

            <!-- Quill Editor -->
            <div id="editor" class="flex-1 overflow-y-auto" style="height: 500px;"></div>
        </div>

        <!-- Right Panel - AI Tools & Properties -->
        <div class="col-span-3 space-y-4 overflow-y-auto">
            <!-- AI Tools -->
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
                <h4 class="font-semibold mb-3 flex items-center gap-2">
                    <span>🤖</span> AI Nástroje
                </h4>

                <div class="space-y-2">
                    <button @click="aiExpand()" class="w-full text-left px-3 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded text-sm transition">
                        📖 Rozšířit text
                    </button>
                    <button @click="aiShorten()" class="w-full text-left px-3 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded text-sm transition">
                        ✂️ Zkrátit text
                    </button>
                    <button @click="aiRewrite()" class="w-full text-left px-3 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded text-sm transition">
                        ✏️ Přepsat text
                    </button>
                    <button @click="aiContinue()" class="w-full text-left px-3 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded text-sm transition">
                        ➡️ Pokračovat
                    </button>
                    <button @click="aiCheckGrammar()" class="w-full text-left px-3 py-2 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded text-sm transition">
                        📝 Kontrola gramatiky
                    </button>
                </div>
            </div>

            <!-- Word Stats -->
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
                <h4 class="font-semibold mb-3">📊 Statistika</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Slova:</span>
                        <span class="font-semibold" x-text="stats.words">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Znaky:</span>
                        <span class="font-semibold" x-text="stats.characters">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Odstavce:</span>
                        <span class="font-semibold" x-text="stats.paragraphs">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Čas čtení:</span>
                        <span class="font-semibold" x-text="stats.readingTime">0 min</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
                <h4 class="font-semibold mb-3">Akce</h4>
                <div class="space-y-2">
                    <button @click="undo()" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-sm rounded hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                        ↶ Vrátit
                    </button>
                    <button @click="redo()" class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-sm rounded hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                        ↷ Opakovat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div x-show="showNewChapterModal" @click.self="showNewChapterModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 w-96">
            <h2 class="text-xl font-semibold mb-4">Nová kapitola</h2>
            <input x-model="newChapterTitle" type="text" placeholder="Název kapitoly" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 rounded mb-4">
            <div class="flex gap-3 justify-end">
                <button @click="showNewChapterModal = false" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-700">Zrušit</button>
                <button @click="createChapter()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Vytvořit</button>
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
        showNewChapterModal: false,
        showAiLoading: false,
        aiResult: null,
        showAiResult: false,
        newChapterTitle: '',
        stats: { words: 0, characters: 0, paragraphs: 0, readingTime: 0 },

        init() {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: '#toolbar',
                    clipboard: { matchVisual: false }
                },
                placeholder: 'Začněte psát...'
            });

            quill.on('text-change', () => this.updateStats());
            
            if (this.selectedChapter > 0) {
                this.loadContent();
            }
        },

        selectChapter(chapterId) {
            this.selectedChapter = chapterId;
            this.loadContent();
        },

        async loadContent() {
            if (!this.selectedChapter || this.selectedChapter === 0) return;
            try {
                const response = await fetch(`/api/chapters/${this.selectedChapter}`);
                const data = await response.json();
                
                // Get first section content or empty
                const sections = data.sections || [];
                this.selectedSection = sections.length > 0 ? sections[0].id : null;
                
                if (sections.length > 0 && sections[0].content) {
                    try {
                        quill.setContents(JSON.parse(sections[0].content));
                    } catch (e) {
                        // If content is plain HTML, set it as text
                        quill.setText(sections[0].content);
                    }
                } else {
                    quill.setText('');
                }
                this.updateStats();
            } catch (e) {
                console.error('Failed to load content:', e);
            }
        },

        async saveContent() {
            if (!this.selectedSection) {
                // Create a new section for this chapter
                try {
                    const content = JSON.stringify(quill.getContents());
                    const response = await fetch('/api/sections', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            chapter_id: this.selectedChapter,
                            content: content
                        })
                    });
                    const data = await response.json();
                    this.selectedSection = data.id;
                    this.showToast('Obsah uložen!', 'success');
                } catch (e) {
                    this.showToast('Chyba: ' + e.message, 'error');
                }
            } else {
                // Update existing section
                try {
                    const content = JSON.stringify(quill.getContents());
                    const response = await fetch(`/api/sections/${this.selectedSection}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ content })
                    });
                    if (response.ok) {
                        this.showToast('Obsah uložen!', 'success');
                    }
                } catch (e) {
                    this.showToast('Chyba: ' + e.message, 'error');
                }
            }
        },

        createChapter() {
            fetch('{{ route("api.chapters.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    book_id: {{ $book->id }},
                    title: this.newChapterTitle
                })
            })
            .then(r => r.json())
            .then(data => {
                location.reload();
            });
        },

        deleteChapter(chapterId) {
            if (confirm('Opravdu smazat kapitolu?')) {
                fetch(`/api/chapters/${chapterId}`, { method: 'DELETE' })
                    .then(() => location.reload());
            }
        },

        async callAiApi(endpoint, body) {
            this.showAiLoading = true;
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
                    this.showToast('Chyba AI: ' + data.error, 'error');
                    return null;
                }
                
                return data;
            } catch (e) {
                this.showToast('Chyba pripojenia: ' + e.message, 'error');
                return null;
            } finally {
                this.showAiLoading = false;
            }
        },

        async aiExpand() {
            const text = quill.getText();
            if (!text.trim()) {
                this.showToast('Najprv napíšte text na rozšírenie', 'warning');
                return;
            }
            const data = await this.callAiApi('expand-text', { text, ratio: 1.5 });
            if (data && data.expanded) {
                this.showAiResult = true;
                this.aiResult = data.expanded;
                // Replace content
                quill.setText(data.expanded);
                this.showToast('Text bol rozšírený!', 'success');
            }
        },

        async aiShorten() {
            const text = quill.getText();
            if (!text.trim()) {
                this.showToast('Najprv napíšte text na skrátenie', 'warning');
                return;
            }
            const data = await this.callAiApi('shorten-text', { text, ratio: 0.7 });
            if (data && data.shortened) {
                quill.setText(data.shortened);
                this.showToast('Text bol skrátený!', 'success');
            }
        },

        async aiRewrite() {
            const text = quill.getText();
            if (!text.trim()) {
                this.showToast('Najprv napíšte text na prepísanie', 'warning');
                return;
            }
            const style = prompt('Vyberte štýl (formal, casual, academic, poetic, technical):', 'formal');
            if (!style) return;
            const data = await this.callAiApi('rewrite-text', { text, style });
            if (data && data.rewritten) {
                quill.setText(data.rewritten);
                this.showToast('Text bol prepísaný!', 'success');
            }
        },

        async aiContinue() {
            const text = quill.getText();
            if (!text.trim()) {
                this.showToast('Najprv napíšte text na pokračovanie', 'warning');
                return;
            }
            const data = await this.callAiApi('continue-writing', { text, words: 500 });
            if (data && data.continued) {
                // Append to existing content using Quill API properly
                const currentLength = quill.getLength();
                quill.insertText(currentLength, '\n\n' + data.continued);
                this.showToast('Text bol rozšírený!', 'success');
            }
        },

        async aiCheckGrammar() {
            const text = quill.getText();
            if (!text.trim()) {
                this.showToast('Najprv napíšte text na kontrolu', 'warning');
                return;
            }
            const data = await this.callAiApi('check-grammar', { text });
            if (data && data.issues) {
                const issues = typeof data.issues === 'string' ? JSON.parse(data.issues) : data.issues;
                if (Array.isArray(issues) && issues.length > 0) {
                    let message = '🔍 Nájdené problémy:\n';
                    issues.forEach((issue, i) => {
                        message += `\n${i + 1}. ${issue.message || issue.description || JSON.stringify(issue)}`;
                    });
                    this.showToast(message, 'info');
                } else {
                    this.showToast('✅ Gramatika je v poriadku!', 'success');
                }
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
                this.showToast('Kapitola bola vygenerovaná!', 'success');
                location.reload();
            }
        },

        updateStats() {
            const text = quill.getText();
            const words = text.trim() ? text.trim().split(/\s+/).length : 0;
            this.stats.words = words;
            this.stats.characters = text.length;
            this.stats.paragraphs = text.split('\n').filter(p => p.trim()).length;
            this.stats.readingTime = Math.max(1, Math.ceil(words / 200));
        },

        undo() { quill.history.undo(); },
        redo() { quill.history.redo(); },

        showToast(message, type = 'info') {
            // Simple toast using alert for now - can be enhanced
            if (type === 'info' || type === 'warning') {
                alert(message);
            } else {
                alert(message);
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
