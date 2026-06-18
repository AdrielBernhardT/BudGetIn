<x-ui.modal @open-category-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
    <div x-data="{
        mode: 'create',
        category: {
            id: null,
            name: '',
            icon: 'home',
            monthly_budget: '',
            monthly_budget_display: '',
            expense_this_month: '',
            usage: '',
        },
        openModal(detail) {
            this.mode = detail?.mode || 'create';
            this.category = detail?.category ? { ...detail.category } : {
                id: null,
                name: '',
                icon: 'home',
                monthly_budget: '',
                monthly_budget_display: '',
                expense_this_month: '',
                usage: '',
                slug: ''
    
            };
            if (this.category.monthly_budget) {
                this.category.monthly_budget_display = this.formatRupiah(this.category.monthly_budget);
            }
    
            this.$nextTick(() => {
                this.$dispatch('category-icon-set', this.category.icon || 'home');
            });
        },
    }" @open-category-modal.window="openModal($event.detail)"
        class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <div class="px-2 pr-14">
            <template x-if="mode === 'create'">
                <div>
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Create New Category
                    </h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                        Add a category to better organize and track your transactions.
                    </p>
                </div>
            </template>

            <template x-if="mode === 'edit'">
                <div>
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Edit Category
                    </h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                        Update your category details to keep your transactions organized.
                    </p>
                </div>
            </template>
        </div>

        <form class="flex flex-col" method="POST"
            :action="mode === 'create'
                ?
                '{{ route('category.store') }}' :
                '/category/update/' + category.slug">
            @csrf
            @method('POST')

            <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Category Name
                    </label>
                    <div class="relative flex items-center gap-2">
                        <x-icon.icon-picker @category-icon-set.window="selected = $event.detail; refresh()" />
                        <input type="text" name="name" x-model="category.name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Monthly Budget
                    </label>
                    <div class="relative">
                        <span
                            class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                            IDR
                        </span>
                        <input type="text" x-model="category.monthly_budget_display   "
                            @input="category.monthly_budget_display = formatRupiah($event.target.value); category.monthly_budget = $event.target.value.replace(/\D/g, '');"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        <input type="hidden" name="monthly_budget" :value="category.monthly_budget" />
                        @error('monthly_budget')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 px-2 lg:justify-end">
                    <button @click="open = false" type="button"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Close
                    </button>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto">
                        <span x-text="mode === 'create' ? 'Save Changes' : 'Update Category'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-ui.modal>
