<div x-data="{ toasts: [] }" x-init="window.toastComponent = { add(toast) { toasts.push(toast); setTimeout(() => { toasts.shift() }, toast.duration || 4000) } }" class="fixed top-4 right-4 z-[9999] space-y-2 w-80">
    <template x-for="(toast, index) in toasts" :key="index">
        <div x-show="toast.show" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0" class="flex items-start gap-3 p-4 rounded-xl shadow-xl border" :class="{
            'bg-green-50 dark:bg-green-900/40 border-green-200 dark:border-green-700': toast.type === 'success',
            'bg-red-50 dark:bg-red-900/40 border-red-200 dark:border-red-700': toast.type === 'error',
            'bg-blue-50 dark:bg-blue-900/40 border-blue-200 dark:border-blue-700': toast.type === 'info',
            'bg-amber-50 dark:bg-amber-900/40 border-amber-200 dark:border-amber-700': toast.type === 'warning'
        }">
            <span class="text-lg flex-shrink-0" x-text="toast.icon || 'ℹ️'"></span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium" :class="{
                    'text-green-800 dark:text-green-200': toast.type === 'success',
                    'text-red-800 dark:text-red-200': toast.type === 'error',
                    'text-blue-800 dark:text-blue-200': toast.type === 'info',
                    'text-amber-800 dark:text-amber-200': toast.type === 'warning'
                }" x-text="toast.message"></p>
            </div>
            <button @click="toast.show = false" class="flex-shrink-0 opacity-60 hover:opacity-100 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    </template>
</div>

<script>
window.showToast = function(message, type = 'info', duration = 4000) {
    const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
    const toast = window.toastComponent;
    if (toast && toast.add) {
        toast.add({ message, type, icon: icons[type] || 'ℹ️', show: true, duration });
    } else {
        // Fallback: queue until component is ready
        if (!window._toastQueue) window._toastQueue = [];
        window._toastQueue.push({ message, type, icon: icons[type] || 'ℹ️', show: true, duration });
        // Retry after component initializes
        setTimeout(() => {
            if (window.toastComponent && window._toastQueue) {
                window._toastQueue.forEach(t => window.toastComponent.add(t));
                window._toastQueue = [];
            }
        }, 500);
    }
};
</script>
