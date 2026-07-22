@extends('layouts.editor')

@section('title', $book->title . ' - Nastavenia')

@section('content')
<div class="p-6" x-data="settingsManager()">
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
                    <button type="button" @click="togglePublish()"
                        class="px-6 py-2.5 rounded-lg font-medium transition"
                        :class="form.status === 'published' ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white'">
                        <span x-text="form.status === 'published' ? '📥 Vrátiť do konceptu' : '📤 Publikovať'"></span>
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
                <div class="w-32 h-44 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-lg flex items-center justify-center text-4xl border-2 border-dashed border-slate-300 dark:border-slate-600">
                    {{ $book->cover_image ? '📖' : '📕' }}
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                        Nahrajte obrázok obálky pre svoju knihu (odporúčaná veľkosť: 1600 x 2560 px)
                    </p>
                    <div class="flex items-center gap-3">
                        <label class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-600 transition text-sm font-medium">
                            📁 Vybrať obrázok
                            <input type="file" accept="image/*" class="hidden">
                        </label>
                        <button type="button" @click="generateCover()"
                            class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition text-sm font-medium shadow-md">
                            🤖 Generovať s AI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                💾 Uložiť nastavenia
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
            author: '{{ $book->author }}',
            description: '{{ $book->description }}',
            tags: '{{ is_array($book->tags) ? implode(', ', $book->tags) : $book->tags }}',
            status: '{{ $book->status }}',
        },

        async saveSettings() {
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
                alert('Nastavenia boli uložené!');
            } else {
                alert('Chyba pri ukladaní nastavení');
            }
        },

        async togglePublish() {
            if (this.form.status === 'published') {
                // Unpublish
                const response = await fetch('/api/books/{{ $book->id }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: 'draft' })
                });
                if (response.ok) this.form.status = 'draft';
            } else {
                // Publish
                const response = await fetch('/api/books/{{ $book->id }}/publish', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (response.ok) this.form.status = 'published';
            }
        },

        async deleteBook() {
            if (confirm('Ste si istí, že chcete zmazať túto knihu? Táto akcia je nenávratná.')) {
                const response = await fetch('/api/books/{{ $book->id }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (response.ok) {
                    window.location.href = '{{ route("books.index") }}';
                }
            }
        },

        generateCover() {
            alert('Funkcia generovania obálky bude čoskoro k dispozícii!');
        }
    }
}
</script>
@endsection
