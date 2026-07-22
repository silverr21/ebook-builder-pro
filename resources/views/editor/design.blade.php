@extends('layouts.editor')

@section('title', $book->title . ' - Design')

@section('content')
<div class="p-6" x-data="designManager()">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">🎨 Dizajn knihy</h2>
        <p class="text-slate-600 dark:text-slate-400 mt-1">Prispôsobte vzhľad svojej knihy</p>
    </div>

    <!-- Templates -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Šablóny</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($templates as $template)
            <button @click="applyTemplate('{{ $template }}')"
                class="group relative bg-white dark:bg-slate-800 rounded-xl border-2 p-6 text-left transition hover:shadow-lg"
                :class="{ 'border-blue-500 ring-2 ring-blue-500': activeTemplate === '{{ $template }}', 'border-slate-200 dark:border-slate-700': activeTemplate !== '{{ $template }}' }">
                <div class="text-2xl mb-3">
                    @switch($template)
                        @case('modern') 🎯 @break
                        @case('classic') 📜 @break
                        @case('minimal') ✨ @break
                        @case('academic') 🎓 @break
                        @default 📖
                    @endswitch
                </div>
                <h4 class="font-semibold text-slate-900 dark:text-white capitalize mb-1">{{ $template }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    @switch($template)
                        @case('modern') Moderný a čistý dizajn @break
                        @case('classic') Klasický knižný štýl @break
                        @case('minimal') Minimálny a elegantný @break
                        @case('academic') Akademický formát @break
                        @default Štandardný dizajn
                    @endswitch
                </p>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Custom Design Settings -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Vlastné nastavenia</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Typography -->
            <div>
                <h4 class="font-medium text-slate-900 dark:text-white mb-4">Typografia</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Písmo nadpisov</label>
                        <select x-model="settings.typography.heading.fontFamily"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="Poppins">Poppins</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Helvetica">Helvetica</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Inter">Inter</option>
                            <option value="Playfair Display">Playfair Display</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Písmo textu</label>
                        <select x-model="settings.typography.body.fontFamily"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="Inter">Inter</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Helvetica">Helvetica</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Lora">Lora</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Veľkosť písma</label>
                        <input type="range" x-model="settings.typography.body.fontSize" min="12" max="24"
                            class="w-full accent-blue-600">
                        <span class="text-xs text-slate-500" x-text="settings.typography.body.fontSize + 'px'"></span>
                    </div>
                </div>
            </div>

            <!-- Colors -->
            <div>
                <h4 class="font-medium text-slate-900 dark:text-white mb-4">Farby</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Primárna farba</label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="settings.colors.primary"
                                class="w-10 h-10 rounded-lg cursor-pointer border border-slate-300 dark:border-slate-600">
                            <span class="text-sm text-slate-600 dark:text-slate-400" x-text="settings.colors.primary"></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Sekundárna farba</label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="settings.colors.secondary"
                                class="w-10 h-10 rounded-lg cursor-pointer border border-slate-300 dark:border-slate-600">
                            <span class="text-sm text-slate-600 dark:text-slate-400" x-text="settings.colors.secondary"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spacing -->
            <div>
                <h4 class="font-medium text-slate-900 dark:text-white mb-4">Rozloženie</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Okraje (px)</label>
                        <input type="range" x-model="settings.spacing.margin" min="10" max="60"
                            class="w-full accent-blue-600">
                        <span class="text-xs text-slate-500" x-text="settings.spacing.margin + 'px'"></span>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Riadkovanie</label>
                        <input type="range" x-model="settings.spacing.lineSpacing" min="1" max="3" step="0.1"
                            class="w-full accent-blue-600">
                        <span class="text-xs text-slate-500" x-text="settings.spacing.lineSpacing"></span>
                    </div>
                </div>
            </div>

            <!-- Layout -->
            <div>
                <h4 class="font-medium text-slate-900 dark:text-white mb-4">Rozloženie stránky</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Zarovnanie</label>
                        <select x-model="settings.layout.alignment"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="left">Vľavo</option>
                            <option value="center">Na stred</option>
                            <option value="justify">Do bloku</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Maximálna šírka (px)</label>
                        <input type="number" x-model="settings.layout.maxWidth"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">📖 Ukážka</h3>
        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-8" :style="previewStyle">
            <h2 :style="{ fontFamily: settings.typography.heading.fontFamily, fontSize: settings.typography.heading.fontSize + 'px', color: settings.colors.primary }" class="mb-4">
                Ukážka nadpisu kapitoly
            </h2>
            <p :style="{ fontFamily: settings.typography.body.fontFamily, fontSize: settings.typography.body.fontSize + 'px', lineHeight: settings.spacing.lineSpacing, color: settings.colors.text }">
                Toto je ukážka textu, ako bude vyzerať vo vašej knihe. Môžete si prispôsobiť písmo, farby a rozloženie podľa svojich predstáv. Každá zmena sa okamžite prejaví v náhľade.
            </p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <button @click="saveDesign()"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
            💾 Uložiť dizajn
        </button>
        <button @click="resetDesign()"
            class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
            ↩️ Obnoviť predvolené
        </button>
    </div>
</div>

<script>
function designManager() {
    return {
        activeTemplate: '{{ $book->design?->template ?? 'modern' }}',
        settings: @json($book->design ? [
            'typography' => $book->design->typography,
            'colors' => $book->design->colors,
            'spacing' => $book->design->spacing,
            'layout' => $book->design->layout,
        ] : App\Models\Design::getDefaultDesign()),

        get previewStyle() {
            return {
                fontFamily: this.settings.typography.body.fontFamily,
                fontSize: this.settings.typography.body.fontSize + 'px',
                lineHeight: this.settings.spacing.lineSpacing,
                color: this.settings.colors.text,
            };
        },

        async applyTemplate(template) {
            this.activeTemplate = template;
            try {
                const response = await fetch('/api/design/apply-template/{{ $book->id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ template })
                });
                const data = await response.json();
                if (data.design) {
                    this.settings.typography = data.design.typography;
                    this.settings.colors = data.design.colors;
                    this.settings.spacing = data.design.spacing;
                    this.settings.layout = data.design.layout;
                    this.showToast('Šablóna aplikovaná!', 'success');
                }
            } catch (e) {
                this.showToast('Chyba pri aplikovaní šablóny', 'error');
            }
        },

        async saveDesign() {
            try {
                const response = await fetch('/api/design/{{ $book->id }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        typography: this.settings.typography,
                        colors: this.settings.colors,
                        spacing: this.settings.spacing,
                        layout: this.settings.layout,
                    })
                });
                if (response.ok) {
                    this.showToast('Dizajn bol uložený!', 'success');
                } else {
                    this.showToast('Chyba pri ukladaní dizajnu', 'error');
                }
            } catch (e) {
                this.showToast('Chyba: ' + e.message, 'error');
            }
        },

        resetDesign() {
            this.settings = @json(App\Models\Design::getDefaultDesign());
            this.activeTemplate = 'modern';
            this.showToast('Dizajn bol obnovený na predvolený', 'info');
        },

        showToast(message, type = 'info') {
            // Simple toast notification
            alert(message);
        }
    }
}
</script>
@endsection
