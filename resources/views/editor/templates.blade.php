@extends('layouts.app')

@section('title', 'Šablóny - eBook Builder Pro')

@section('content')
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 dark:from-slate-900 dark:to-slate-800 py-12" x-data="templates()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="text-6xl mb-4">🎨</div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-3">Šablóny</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Vyberte si z profesionálne navrhnutých šablón pre vašu knihu
            </p>
        </div>

        <!-- Template Categories -->
        <div class="flex justify-center gap-2 mb-10 flex-wrap">
            <template x-for="cat in categories" :key="cat">
                <button @click="selectedCategory = cat; filteredTemplates = templates.filter(t => cat === 'all' || t.category === cat)" 
                        class="px-4 py-2 rounded-full text-sm font-medium border-2 transition-all duration-200"
                        :class="selectedCategory === cat ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-500/25' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-500 hover:text-blue-600'">
                    <span x-text="cat === 'all' ? 'Všetky' : cat.charAt(0).toUpperCase() + cat.slice(1)"></span>
                </button>
            </template>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="(template, index) in filteredTemplates" :key="index">
                <div @click="applyTemplate(template)" 
                     class="group bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-1"
                     :class="selectedTemplate === template.name ? 'border-blue-500 ring-2 ring-blue-500' : ''">
                    <div class="h-48 flex items-center justify-center relative overflow-hidden" :style="'background: ' + template.gradient">
                        <span class="text-6xl relative z-10 group-hover:scale-110 transition-transform duration-300" x-text="template.emoji"></span>
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="template.name"></h3>
                            <span x-show="template.popular" class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">✨ Populárne</span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3" x-text="template.description"></p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span x-text="'🎨 ' + template.schemes + ' farebných schém'"></span>
                            <span>•</span>
                            <span x-text="'📐 ' + template.variants + ' varianty'"></span>
                        </div>
                        <div x-show="selectedTemplate === template.name" class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                ✅ Vybraté
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Apply Template Button (when selected) -->
        <div x-show="selectedTemplate" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50" style="display: none;">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-4 flex items-center gap-4">
                <span class="text-sm text-slate-600 dark:text-slate-400">
                    Vybratá šablóna: <strong class="text-slate-900 dark:text-white" x-text="selectedTemplate"></strong>
                </span>
                <div class="flex gap-2">
                    <a :href="'{{ route('editor.new') }}?template=' + selectedTemplate.toLowerCase()" 
                       class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                        ✨ Použiť šablónu
                    </a>
                    <button @click="selectedTemplate = null" class="px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Zrušiť
                    </button>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-12 text-center">
            <a href="{{ route('editor.new') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-500/25">
                ✨ Vytvoriť knihu so šablónou
            </a>
        </div>
    </div>
</div>

<script>
function templates() {
    return {
        selectedCategory: 'all',
        selectedTemplate: null,
        categories: ['all', 'beletria', 'náučná', 'akademická', 'detská', 'poézia'],
        templates: [
            { name: 'Modern', category: 'beletria', description: 'Čistý, moderný dizajn s výraznými farbami', gradient: 'linear-gradient(135deg, #3B82F6, #1D4ED8)', emoji: '🎯', popular: true, schemes: 4, variants: 2 },
            { name: 'Classic', category: 'beletria', description: 'Klasický knižný dizajn s tradičným vzhľadom', gradient: 'linear-gradient(135deg, #D97706, #92400E)', emoji: '📜', popular: false, schemes: 3, variants: 2 },
            { name: 'Minimal', category: 'náučná', description: 'Minimalistický a elegantný dizajn', gradient: 'linear-gradient(135deg, #94A3B8, #475569)', emoji: '✨', popular: false, schemes: 2, variants: 1 },
            { name: 'Academic', category: 'akademická', description: 'Formát vhodný pre akademické publikácie', gradient: 'linear-gradient(135deg, #334155, #0F172A)', emoji: '🎓', popular: false, schemes: 2, variants: 2 },
            { name: 'Children', category: 'detská', description: 'Farebný a hravý dizajn pre detské knihy', gradient: 'linear-gradient(135deg, #EC4899, #7C3AED)', emoji: '🧒', popular: true, schemes: 6, variants: 3 },
            { name: 'Poetry', category: 'poézia', description: 'Elegantný dizajn pre básne a poetické texty', gradient: 'linear-gradient(135deg, #F43F5E, #BE185D)', emoji: '🌸', popular: false, schemes: 4, variants: 2 },
        ],
        filteredTemplates: [],

        init() {
            this.filteredTemplates = this.templates;
        },

        applyTemplate(template) {
            this.selectedTemplate = this.selectedTemplate === template.name ? null : template.name;
            showToast(`Šablóna "${template.name}" ${this.selectedTemplate ? 'vybratá' : 'zrušená'}`, 'success');
        }
    }
}
</script>
@endsection
