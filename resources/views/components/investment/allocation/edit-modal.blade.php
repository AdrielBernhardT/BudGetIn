<div x-data="investmentPage()">
    <x-ui.modal 
    x-data="{ open: false}" x-show="open"
    @edit-investment-item.window="open = true"
    :isOpen="$errors->goal->any()"
    class="max-w-[700px]">
        <div x-data="{
            target: {
                title: '',
                current_amount: '',
                target_amount: '',
                allocation_percentage: ''
            },

            fillData(data) {
                this.target.title = data.title;
                this.target.current_amount = new Intl.NumberFormat('id-ID').format(data.current_amount);
                this.target.target_amount = new Intl.NumberFormat('id-ID').format(data.target_amount);
                this.target.allocation_percentage = data.allocation_percentage;
                this.open = true;
            },

            resetModal() {
                this.target = {
                    name: '',
                    amount: '',
                    amount_display: '',
                    icon: 'home',
                };
                
                this.$nextTick(() => {
                    this.$dispatch('target-icon-set', this.target.icon || 'home');
                });
            }
        }" @edit-investment-item.window="fillData($event.detail)"
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Edit Investment List
                </h4>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    Edit your investment list to track your savings and investments.
                </p>
            </div>
            <form class="flex flex-col" method="POST" action="{{ route('investment.store-goal') }}">
                @csrf
                @method('POST')
                <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Investment Name:
                        </label>
                        <div class="relative flex items-center gap-2">
                            <input type="text" name="title" x-model="target.title"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Allocation (%):
                        </label>
                        <div class="relative flex items-center gap-2">
                            <input type="text" name="allocation_percentage" x-model="target.allocation_percentage"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            <span class="absolute top-1/2 right-4 inline-flex -translate-y-1/2 items-center text-gray-500 dark:text-gray-400">
                                %
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Target Amount:
                        </label>
                        <div class="relative flex items-center gap-2">
                            <span
                                class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                IDR
                            </span>
                            <input type="text" name="target_amount" x-model="target.target_amount"
                                @input="target.target_amount = formatRupiah($event.target.value); target.amount = $event.target.value.replace(/\D/g, '');"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-16 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Current Amount:
                        </label>
                        <div class="relative flex items-center gap-2">
                            <span
                                class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                IDR
                            </span>
                            <input type="text" name="current_amount" x-model="target.current_amount"
                                @input="target.current_amount = formatRupiah($event.target.value); target.amount = $event.target.value.replace(/\D/g, '');"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-16 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false; showErrors= false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Close
                        </button>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
