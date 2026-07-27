<div x-data="iconPicker()" class="relative icon-picker" @click.stop @click.capture.window="if (open && !$el.contains($event.target)) open = false" @modal-closed.window="open = false" @category-icon-set.window="selected = $event.detail; refresh()" @target-icon-set.window="selected = $event.detail; refresh();">

    <button type="button" @click.stop="toggle()" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 rounded-lg border border-gray-300 bg-transparent w-11 h-11 flex items-center justify-center focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900">
        <i :data-lucide="selected" class="w-5 h-5 text-gray-800 dark:text-white/90"></i>
    </button>

    <div x-show="open" x-transition @click.stop
        class="absolute z-[9999] mt-2 w-64 bg-white rounded-xl shadow p-3 dark:bg-gray-900">

        <div class="grid grid-cols-6 gap-2">
            <template x-for="icon in icons" :key="icon">
                <button type="button" @click.stop="select(icon)"
                    class="p-2 rounded hover:bg-gray-100 flex items-center justify-center dark:hover:bg-gray-700">
                    <i :data-lucide="icon" class="w-4 h-4 text-gray-800 dark:text-white/90"></i>
                </button>
            </template>
        </div>

    </div>

    <input type="hidden" name="icon" :value="selected">
</div>
<script>
    function iconPicker() {
        return {
            open: false,
            selected: 'home',

            icons: [
                'home',
                'user',
                'calendar',
                'tag',
                'star', 'shopping-cart',
                'gift',
                'shirt',
                'heart',
                'coffee', 'truck',
                'car',
                'plane',
                'map',
                'navigation', 'wallet',
                'credit-card',
                'banknote',
                'piggy-bank',
                'receipt', 'settings',
                'briefcase',
                'phone',
                'laptop',
                'bell'
            ],

            init() {
                this.refresh();
            },

            toggle() {
                this.open = !this.open;
                this.refresh();
            },

            select(icon) {
                this.selected = icon;
                this.open = false;
                this.refresh();
            },

            refresh() {
                this.$nextTick(() => {
                    if (typeof window.createIcons === 'function') {
                        window.createIcons();
                    } else if (typeof createIcons === 'function') {
                        createIcons();
                    }
                });
            }
        }
    }
</script>
