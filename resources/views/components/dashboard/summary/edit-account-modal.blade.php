<div x-data="dashboardPage()">
    <x-ui.modal x-data="{ open: {{ $errors->account->any() ? 'true' : 'false' }} }" @edit-account.window="open = true" :isOpen="$errors->account->any()" class="max-w-[700px]">
        <div x-data="{
            account: {
                id: '',
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
        }" @edit-account.window="
            account = {
                id: $event.detail.id,
                name: $event.detail.name,
                account_identifier: $event.detail.account_identifier,
                balance: $event.detail.balance,
                balance_display: formatRupiah($event.detail.balance.toString())
            }
            "
        "
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ __('sentence.edit_account') }}
                </h4>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    {{ __('sentence.edit_account_description') }}
                </p>
            </div>
            <form class="flex flex-col" method="POST" :action="`/account/update/${account.id}`">
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
