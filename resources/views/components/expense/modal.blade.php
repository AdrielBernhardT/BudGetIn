<style>
/* .flatpickr-calendar{
    transform: scale(0.8) !important;
    transform-origin: top left !important;
} */
</style>

<x-ui.modal @open-expense-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
    <div x-data="{
        mode: 'create',
        income: {
            id: null,
            title: '',
            category:'',
            amount: '',
            account_bank: '',
            date: '',
            description: '',
        },
        openModal(detail) {
            this.mode = detail?.mode || 'create';
            this.expense = detail?.expense ? { ...detail.expense } : {
                id: null,
                title: '',
                category:'',
                amount: '',
                account_bank: '',
                date: '',
                description: '',
            };
            if (this.expense.amount) {
                this.expense.amount = this.formatRupiah(this.expense.amount);
            }
        },
    }" @open-expense-modal.window="openModal($event.detail)"
        class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <div class="px-2 pr-14">
            <template x-if="mode === 'create'">
                <div>
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Add New Expense
                    </h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                        Enter the details of your new expense below.
                    </p>
                </div>
            </template>

            <template x-if="mode === 'edit'">
                <div>
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                        Edit Expense
                    </h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                        Update the details of your expense below.
                    </p>
                </div>
            </template>
        </div>

        <form class="flex flex-col" method="POST"
            :action="mode === 'create'
                ?
                '{{ route('expense.store') }}' :
                '/expense/update/' + expense.id"
        >
            @csrf 
            @method('POST')
            
            <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                <div class = "flex lg:flex-row flex-col lg:gap-10 gap-5">
                    <div class="lg:w-2/3">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Expense Title
                        </label>
                        <div class="relative flex items-center gap-2">
                            <input type="text" x-model="expense.title" name="title"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="lg:w-1/3">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Category
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                                name="category_id"
                                x-model="expense.category"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 
                                bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 
                                focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 
                                dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">
                                <option disabled value="">Select Category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            
                            <span
                                class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>

                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex lg:flex-row flex-col lg:gap-10 gap-5">
                    <div class="lg:w-2/3">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Amount
                        </label>
                        <div class="relative">
                            <span
                                class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                IDR
                            </span>
                            <input type="text" x-model="expense.amount" name="amount"
                                @input="expense.amount = formatRupiah($event.target.value)" placeholder="0"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>

                    <div class="lg:w-1/3">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Account Bank
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                                name="from_account_id"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">
                                <option disabled value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    Select Option
                                </option>
                                @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    {{ $account->name }}
                                </option>
                                @endforeach
                            </select>

                            <span
                                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>

                            @error('from_account_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date
                    </label>
                    <div class="relative w-full">
                        <x-form.date-picker id="date_pick" name="date" placeholder="Date Picker"
                            x-model="expense.date" defaultDate="{{ now()->format('d-m-Y') }}" />
                    </div>
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea name="description" x-model="expense.description" placeholder="Enter a description..." type="text" rows="6"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                    
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex items-center gap-3 px-2 lg:justify-end">
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Close
                    </button>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto">
                        <span x-text="mode === 'create' ? 'Save Changes' : 'Update Income'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-ui.modal>
