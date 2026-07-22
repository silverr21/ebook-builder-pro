@props(['sections' => [], 'selectedId' => null, 'chapterId' => null])

<div class="border-b border-slate-200 dark:border-slate-700" x-data="{ 
    sections: @js($sections),
    selectedSection: @js($selectedId),
    newSectionTitle: ''
}">
    <div class="flex items-center overflow-x-auto scrollbar-hide gap-1 px-2">
        <template x-for="section in sections" :key="section.id">
            <button @click="selectSection(section.id)" 
                    class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-t-lg border-b-2 transition whitespace-nowrap"
                    :class="selectedSection === section.id ? 'border-blue-500 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                <span x-text="section.title || `Sekcia ${sections.indexOf(section) + 1}`"></span>
                <button @click.stop="deleteSection(section.id)" class="ml-1 opacity-0 group-hover:opacity-100 hover:text-red-500 transition">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </button>
        </template>
        
        <!-- Add Section Button -->
        <button @click="addSection()" class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition border-b-2 border-transparent hover:border-blue-300">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            <span>Pridať sekciu</span>
        </button>
    </div>
</div>

<script>
// These functions will be merged with the editor's Alpine component
window.selectSection = async function(sectionId) {
    if (window.editorApp) {
        await window.editorApp.selectSection(sectionId);
    }
};

window.deleteSection = async function(sectionId) {
    if (window.editorApp) {
        await window.editorApp.deleteSection(sectionId);
    }
};

window.addSection = async function() {
    if (window.editorApp) {
        const title = prompt('Názov sekcie (nepovinné):');
        await window.editorApp.addSection(title || '');
    }
};
</script>
