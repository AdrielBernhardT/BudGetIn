<div x-data="investmentPage()">
    <x-ui.modal
        x-data="{
            open: {{ $errors->investment->any() ? 'true' : 'false' }},
            showErrors: {{ $errors->investment->any() ? 'true' : 'false' }},
            errors: {
                allocation: @js($errors->investment->first('allocation_percent') ?? '')
            },
            resetErrors() {
                this.errors = { allocation: '' }
            }
        }"
        @edit-investment-item.window="open = true"
        :isOpen="$errors->investment->any()"
        class="max-w-[700px]"
    >
        <div x-data="{
            goals: @js($goals),
            selectedGoal: null,
            investment: {
                id: '',
                name: '',
                goal: '',
                amount: '',
                amount_display: '',
                allocation: ''
            },
            fillData(data) {
                this.investment.id = data.id;
                this.investment.name = data.name;
                this.investment.goal = data.goal_id;
                this.investment.amount = data.amount;
                this.investment.amount_display = new Intl.NumberFormat('id-ID').format(data.amount);
                this.investment.allocation = data.allocation;
                this.selectedGoal = data.goal;
            },
            resetModal() {
                this.investment = {
                    name: '',
                    goal: '',
                    allocation: '',
                    amount: '',
                    amount_display: ''
                };
                this.selectedGoal = null;
                this.resetErrors()
            }
        }"
        @edit-investment-item.window="fillData($event.detail)"
        class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto"
    >
        <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                {{ __('common.edit_investment') }}
            </h4>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                {{ __('common.edit_investment_description')     }}
            </p>
        </div>

        <form class="flex flex-col" method="POST" :action="`/investment/update/${investment.id}`">
            @csrf
            @method('POST')

            <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        {{ __('common.investment_name') }}:
                    </label>
                    <input type="text" name="name" x-model="investment.name"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                    />
                </div>

                <div x-show="selectedGoal">
                    <x-ui.alert variant="info" :showLink="false">
                        <div>
                            <p class="font-medium text-blue-800 dark:text-blue-200">
                                <span x-text="selectedGoal.name"></span> {{ __('common.target_amount') }}:
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                {{ __('common.idr') }} <span x-text="formatRupiah(parseInt(selectedGoal.target_amount))"></span>
                            </p>
                        </div>
                    </x-ui.alert>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-5">

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('common.allocation') }} (%)
                            </label>

                            <div class="relative">
                                <input type="number" name="allocation_percent"
                                    x-model="investment.allocation"
                                    @input="
                                        investment.allocation = investment.allocation.replace(/[^0-9.]/g, '');

                                        if (investment.allocation > 100) {
                                            investment.allocation = 100;
                                            errors.allocation = 'Maximum allocation is 100%';
                                        } else {
                                            errors.allocation = '';
                                        }

                                        if (selectedGoal && investment.allocation != '') {
                                            investment.amount = Math.round((parseInt(selectedGoal.target_amount) * investment.allocation) / 100);
                                            investment.amount_display = formatRupiah(investment.amount);
                                        }
                                    "
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-12 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                                />

                                <span class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-500">
                                    %
                                </span>
                            </div>

                            @error('allocation_percent', 'investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            <p x-show="errors.allocation" class="mt-1 text-sm text-red-500" x-text="errors.allocation"></p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('common.allocation_amount') }}
                            </label>

                            <div class="relative">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 border-r px-3 text-gray-500">
                                    {{ __('common.idr') }}
                                </span>

                                <input type="text"
                                    x-model="investment.amount_display"
                                    @input="
                                        investment.amount_display = formatRupiah($event.target.value);
                                        investment.amount = $event.target.value.replace(/\D/g, '');

                                        if (selectedGoal && investment.amount != '') {
                                            investment.allocation = Math.round((parseInt(investment.amount) / parseInt(selectedGoal.target_amount)) * 100);

                                            if (investment.allocation > 100) {
                                                investment.allocation = 100;
                                            }
                                        } else {
                                            investment.allocation = '';
                                        }
                                    "
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-16 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90"
                                />

                                <input type="hidden" name="planned_amount" :value="investment.amount" />
                                <input type="hidden" name="goal_id" :value="investment.goal" />
                                <input type="hidden" name="id" :value="investment.id" />
                            </div>

                            @error('planned_amount', 'investment')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <button type="button" @click="open = false; resetErrors()"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 sm:w-auto">
                        {{ __('common.close') }}
                    </button>

                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                        {{ __('common.save_changes') }}
                    </button>
                </div>

            </div>
        </form>
    </x-ui.modal>
</div>
