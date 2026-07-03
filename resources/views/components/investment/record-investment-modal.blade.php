<style>
    .flatpickr-calendar {
        transform: scale(0.8) !important;
        transform-origin: top left !important;
    }
</style>

<div x-data="investmentPage()">
    <x-ui.modal x-data="{ open: {{ $errors->record_investment->any() ? 'true' : 'false' }} }" @record-investment.window="open = true" :isOpen="$errors->record_investment->any()" class="max-w-[700px]">
        <div
        x-data="{
            investment: {
                investment_id: '',
                goal: '{{ old("goal_id") ? (string) old("goal_id") : "" }}',
                account_id: '{{ old("account_id") ? (string) old("account_id") : "" }}',
                date: '{{ old("date") ? old("date") : "" }}',
                amount: '',
                amount_display: '',
                description: '{{ old("description") ? old("description") : "" }}',
            },

            goals: @js($goals),

            get filteredInvestments() {
                const selected = this.goals.find(g =>
                    String(g.id) === String(this.investment.goal)
                )
                return selected ? selected.investments : []
            },

            resetModal() {
                this.investment = {
                    investment_id: '',
                    goal: '',
                    account_id: '',
                    date: '',
                    amount: '',
                    amount_display: '',
                    description: '',
                };
            }
        }"
        @record-investment.window="resetModal()"
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ __('common.record_investment') }}
                </h4>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    {{ __('common.record_investment_description') }}
                </p>
            </div>
            <form class="flex flex-col" method="POST" action="{{ route('investment.record-investment.store') }}">
                @csrf
                @method('POST')
                <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('common.goal') }}
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                                name="goal_id"
                                x-model="investment.goal"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">
                                <option disabled value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    {{ __('common.select_option') }}
                                </option>
                                @foreach($goals as $goal)
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
                            @error('goal_id', 'record_investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('common.investment_name') }}
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                                name="investment_id"
                                x-model="investment.investment_id"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">

                                <option disabled value="">{{ __('common.select_option') }}</option>

                                <template x-for="inv in filteredInvestments" :key="inv.id">
                                    <option :value="inv.id" x-text="inv.name"></option>
                                </template>
                            </select>
                            <span
                                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            @error('investment_id', 'record_investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('common.account_bank') }}
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select
                                name="account_id"
                                x-model="investment.account_id"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">
                                <option disabled value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                    {{ __('common.select_option') }}
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
                            @error('account_id', 'record_investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('common.date') }}
                            </label>
                            <div class="relative w-full">
                                <x-form.date-picker 
                                id="date_pick" 
                                name="date" 
                                placeholder="Date Picker"
                                x-model="investment.date" 
                                dateFormat="Y-m-d"
                                altFormat="d F Y"
                                defaultDate="{{ now()->format('Y-m-d') }}"/>
                            </div>
                            @error('date', 'record_investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('common.allocation_amount') }}
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                    {{ __('common.idr') }}
                                </span>
                                <input type="text" x-model="investment.amount_display"
                                    @input="investment.amount_display = formatRupiah($event.target.value); investment.amount = $event.target.value.replace(/\D/g, '');"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                <input type="hidden" name="transaction_amount" :value="investment.amount" />
                            </div>
                            @error('transaction_amount', 'record_investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('common.description') }}
                        </label>
                        <textarea x-model="income.description" placeholder="{{ __('common.description_placeholder') }}" type="text" rows="6"
                            name="description"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                        @error('description', 'record_investment')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            {{ __('common.close') }}
                        </button>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto">
                            {{ __('common.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
