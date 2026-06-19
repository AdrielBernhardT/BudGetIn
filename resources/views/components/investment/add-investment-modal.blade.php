<div x-data="investmentPage()">
    <x-ui.modal x-data="{ open: {{ $errors->investment->any() ? 'true' : 'false' }} }" @add-investment.window="open = true" :isOpen="$errors->investment->any()" class="max-w-[700px]">
        <div x-data="{
            goals: @js($goals),
            selectedGoal: null,
            investment: {
                name: '',
                goal: '',
                allocation: '',
                amount: '',
                amount_display: '',
            },
        
            resetModal() {
                this.investment = {
                    name: '',
                    goal: '',
                    allocation: '',
                    amount: '',
                    amount_display: '',
                };
                this.selectedGoal = null;
            }
        }" @add-investment.window="resetModal()"
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Add Investment
                </h4>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    Add an investment and set how much of your goal it should cover.
                </p>
            </div>
            <form class="flex flex-col" method="POST" action="{{ route('investment.store') }}">
                @csrf 
                @method('POST')
                <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Investment Name
                        </label>
                        <input type="text" name="name" x-model="investment.name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('name', 'investment')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Goal
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent mb-5">
                            <select
                                name="goal_id"
                                x-model="investment.goal"
                                @change="
                                    isOptionSelected = true;
                                    selectedGoal = goals.find(goal => goal.id == $event.target.value);
                                "
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                >
                                <option disabled value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    Select Option
                                </option>
                                @foreach ($goals as $goal)
                                <option value="{{ $goal->id }}" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    {{ $goal->name }}
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
                            @error('goal_id', 'investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div x-show="selectedGoal">
                            <x-ui.alert
                                variant="info"
                                :showLink="false"
                            >
                                <div>
                                    <p class="font-medium text-blue-800 dark:text-blue-200">
                                        <span x-text="selectedGoal.name"></span>
                                        Target Amount:
                                    </p>

                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        IDR <span x-text="formatRupiah(parseInt(selectedGoal.target_amount))"></span>
                                    </p>
                                </div>
                            </x-ui.alert>
                        </div>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Allocation
                            <p class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                Enter a percetange or amount to allocate towards this goal. The values will update
                                automatically.
                            </p>
                        </label>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Allocation (%)
                                </label>
                                <div class="relative">
                                    <input type="number" name="allocation_percent" x-model="investment.allocation"
                                        @input="
                                            investment.allocation = investment.allocation.replace(/[^00-9.]/g);
                                            if(investment.allocation > 100) {
                                                investment.allocation = 100
                                            }

                                            if(selectedGoal && investment.allocation != ''){
                                                investment.amount = (parseInt(selectedGoal.target_amount) * investment.allocation) / 100;
                                                investment.amount = Math.round(investment.amount);
                                                investment.amount_display = (formatRupiah(investment.amount));
                                            }
                                        "
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    <span
                                        class="absolute top-1/2 right-4 inline-flex -translate-y-1/2 items-center text-gray-500 dark:text-gray-400">
                                        %
                                    </span>
                                </div>
                                @error('allocation_percent', 'investment')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <i data-lucide="arrow-left-right" class="w-5 h-5 text-gray-900 dark:text-white self-end mb-3"></i>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Allocation Amount
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                        IDR
                                    </span>
                                    <input type="text" x-model="investment.amount_display"
                                        @input="
                                            investment.amount_display = formatRupiah($event.target.value);
                                            investment.amount = $event.target.value.replace(/\D/g, '');

                                            if(selectedGoal && investment.amount != ''){
                                                investment.allocation = Math.round((parseInt(investment.amount) / parseInt(selectedGoal.target_amount)) * 100);
                                                if(investment.allocation > 100){
                                                    investment.allocation = 100;
                                                }
                                            } else {
                                                investment.allocation = '';
                                            }
                                        "
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    <input type="hidden" name="planned_amount" :value="investment.amount" />
                                    
                                </div>
                                @error('planned_amount', 'investment')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
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
