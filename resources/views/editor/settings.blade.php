@extends('layouts.editor')

@section('title', $book->title . ' - Nastavenia')

@section('content')
<x-toast />
<div class="p-6" x-data="settingsManager()" x-init="init()">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">⚙️ Nastavenia knihy</h2>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Spravujte nastavenia a vlastnosti svojej knihy</p>
    </div>

    <form @submit.prevent="saveSettings()" class="space-y-6 max-w-3xl">
        <!-- Basic Info -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Základné informácie</h3>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Názov knihy</label>
                    <input type="text" x-model="form.title"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Autor</label>
                    <input type="text" x-model="form.author"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Popis knihy</label>
                    <textarea x-model="form.description" rows="4"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tagy (oddelené čiarkou)</label>
                    <input type="text" x-model="form.tags" placeholder="napr. beletria, román, dobrodružstvo"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
        </div>

        <!-- Status & Visibility -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Stav a viditeľnosť</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Status</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Aktuálny stav knihy</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium"
                        :class="form.status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                        <span x-text="form.status === 'published' ? 'Publikované' : 'Koncept'"></span>
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" @click="togglePublish()" :disabled="saving"
                        class="px-6 py-2.5 rounded-lg font-medium transition flex items-center gap-2"
                        :class="form.status === 'published' ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white'">
                        <span x-text="form.status === 'published' ? '📥 Vrátiť do konceptu' : '📤 Publikovať'"></span>
                        <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>

                    <button type="button" @click="deleteBook()"
                        class="px-6 py-2.5 border border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition font-medium">
                        🗑️ Zmazať knihu
                    </button>
                </div>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">🖼️ Obálka knihy</h3>

            <div class="flex items-start gap-6">
                <div class="w-32 h-44 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-lg flex items-center justify-center text-4xl border-2 border-dashed border-slate-300 dark:border-slate-600 relative">
                    <div x-show="coverLoading" class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <img x-show="form.coverImage" :src="form.coverImage" class="w-full h-full object-cover rounded-lg" />
                    <span x-show="!form.coverImage && !coverLoading">📕</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                        Nahrajte obrázok obálky pre svoju knihu (odporúčaná veľkosť: 1600 x 2560 px)
                    </p>
                    <div class="flex items-center gap-3 flex-wrap">
                        <label class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm font-medium">
                            📁 Vybrať obrázok
                            <input type="file" accept="image/*" class="hidden" @change="uploadCover($event)">
                        </label>
                        <button type="button" @click="generateCover()" :disabled="coverLoading"
                            class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition text-sm font-medium shadow-md flex items-center gap-2">
                            <svg x-show="coverLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="coverLoading ? 'Generujem...' : '🤖 Generovať s AI'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" :disabled="saving"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25 flex items-center gap-2">
                <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="saving ? 'Ukladám...' : '💾 Uložiť nastavenia'"></span>
            </button>
            <a href="{{ route('editor.show', $book) }}"
                class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Späť do editora
            </a>
        </div>
    </form>
</div>

<script>
function settingsManager() {
    return {
        form: {
            title: '{{ $book->title }}',
            author: '{{ $book->author ?: "" }}',
            description: '{{ $book->description ?: "" }}',
            tags: '{{ is_array($book->tags) ? implode(", ", $book->tags) : $book->tags }}',
            status: '{{ $book->status }}',
            coverImage: null,
        },
        saving: false,
        coverLoading: false,

        init() {
            @if($book->cover_image)
            this.form.coverImage = '{{ Storage::url($book->cover_image) }}';
            @endif
        },

        async saveSettings() {
            this.saving = true;
            try {
                const response = await fetch('/api/books/{{ $book->id }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        title: this.form.title,
                        author: this.form.author,
                        description: this.form.description,
                        tags: this.form.tags,
                    })
                });

                if (response.ok) {
                    showToast('Nastavenia boli uložené! ✅', 'success');
                } else {
                    const err = await response.json();
                    showToast('Chyba: ' + (err.message || 'Pri ukladaní nastala chyba'), 'error');
                }
            } catch (e) {
                showToast('Chyba pripojenia: ' + e.message, 'error');
            } finally {
                this.saving = false;
            }
        },

        async togglePublish() {
            this.saving = true;
            try {
                if (this.form.status === 'published') {
                    const response = await fetch('/api/books/{{ $book->id }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: 'draft' })
                    });
                    if (response.ok) {
                        this.form.status = 'draft';
                        showToast('Kniha je späť v koncepte 📝', 'info');
                    }
                } else {
                    const response = await fetch('/api/books/{{ $book->id }}/publish', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    if (response.ok) {
                        this.form.status = 'published';
                        showToast('Kniha bola publikovaná! 🎉', 'success');
                    }
                }
            } catch (e) {
                showToast('Chyba: ' + e.message, 'error');
            } finally {
                this.saving = false;
            }
        },

        async deleteBook() {
            if (confirm('Ste si istí, že chcete zmazať túto knihu? Táto akcia je nenávratná.')) {
                try {
                    const response = await fetch('/api/books/{{ $book->id }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    if (response.ok) {
                        showToast('Kniha bola zmazaná 🗑️', 'info');
                        setTimeout(() => { window.location.href = '{{ route("books.index") }}'; }, 500);
                    }
                } catch (e) {
                    showToast('Chyba: ' + e.message, 'error');
                }
            }
        },

        async generateCover() {
            this.coverLoading = true;
            try {
                const response = await fetch('/api/design/generate-cover/{{ $book->id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ style: 'professional', mood: 'professional' })
                });
                const data = await response.json();
                if (data.image) {
                    this.form.coverImage = data.image;
                    showToast('Obálka vygenerovaná! 🎨', 'success');
                } else {
                    showToast('Popis obálky: ' + (data.description || 'Skúste znova'), 'info');
                }
            } catch (e) {
                showToast('Chyba pri generovaní: ' + e.message, 'error');
            } finally {
                this.coverLoading = false;
            }
        },

        async uploadCover(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Show local preview immediately
            const reader = new FileReader();
            reader.onload = (e) => {
                this.form.coverImage = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload to server
            const formData = new FormData();
            formData.append('cover_image', file);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch('/api/books/{{ $book->id }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                if (response.ok) {
                    showToast('Obálka nahraná a uložená! 📁✅', 'success');
                } else {
                    showToast('Obrázok zobrazený, ale serverové uloženie zlyhalo', 'warning');
                }
            } catch (e) {
                showToast('Obrázok zobrazený lokálne 📁', 'info');
            }
        }
    }
}
</script>
@endsection
