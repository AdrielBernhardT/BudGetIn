<div class="mb-6">
    <x-ui.alert
        variant="warning"
        :showLink="false"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full">
            <div>
                <h5 class="font-medium text-yellow-800 dark:text-yellow-200">
                    {{ __('sentence.no_accounts_found') }}
                </h5>

                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    {{ __('sentence.create_account_before_income') }}
                </p>
            </div>

            <a
                href="{{ route('dashboard') }}"
                class="rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                {{ __('sentence.add_account') }}
            </a>
        </div>
    </x-ui.alert>
</div>