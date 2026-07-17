<div x-data="dashboardPage()">
    <x-ui.modal x-data="{ open: {{ $errors->account->any() ? 'true' : 'false' }} }" @add-account.window="open = true" :isOpen="$errors->account->any()" class="max-w-[700px]">
        <div x-data="{
            account: {
                name: '',
                account_identifier: '',
                balance: '',
                balance_display: '',
            },

            resetModal() {
                this.account = {
                    name: '',
                    account_identifier: '',
                    balance: '',
                    balance_display: '',
                };
            }
        }" @add-account.window="resetModal()"
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ __('sentence.add_account') }}
                </h4>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    {{ __('sentence.create_account_to_manage_balance') }}
                </p>
            </div>
            <form class="flex flex-col" method="POST" action="{{ route('account.store') }}">
                @csrf
                @method('POST')
                <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('sentence.account_name') }}<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="account.name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('name', 'account')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4 items-start">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('sentence.account_number') }}
                            </label>
                            <input type="number" name="account_identifier" x-model="account.account_identifier"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('account_identifier', 'account')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ __('sentence.initial_amount') }}<span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                    {{ __('common.idr') }}
                                </span>
                                <input type="text" x-model="account.balance_display"
                                    @input="account.balance_display = formatRupiah($event.target.value); account.balance = $event.target.value.replace(/\D/g, '');"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                <input type="hidden" name="balance" :value="account.balance" />
                            </div>
                            @error('balance', 'goal')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false; showErrors= false" type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            {{ __('common.close') }}
                        </button>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto">
                            {{ __('sentence.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
