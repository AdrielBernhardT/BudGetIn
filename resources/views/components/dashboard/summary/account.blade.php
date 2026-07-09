@props(['account'])

<div class="col-span-8 rounded-2xl bg-linear-to-br from-[#0F2A5F] to-[#1E40AF] p-5 md:p-4">
    <div class="flex flex-col justify-between gap-4">
        <div class="flex items-center justify-between">
            @if (!empty($account->account_identifier))
                <div x-data="{ copied: false }" class="flex items-center gap-2">
                    <span class="text-sm text-white font-normal">
                        {{ $account->account_identifier }}
                    </span>

                    <button type="button" class="cursor-pointer"
                        @click="
                            navigator.clipboard.writeText('{{ $account->account_identifier }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        ">
                        <i x-show="!copied" data-lucide="copy" class="w-3 h-3 text-white"></i>

                        <i x-show="copied" data-lucide="check" class="w-3 h-3 text-green-400"></i>
                    </button>
                </div>
            @else
                <span class="text-md text-white font-semibold">{{ $account->name }}</span>
            @endif

            <x-common.dropdown-menu
            activeClass="text-white"
            inactiveClass="text-white hover:text-gray-200">
                <button type="button"
                    @click="openDropDown = false;
                                    window.dispatchEvent(new CustomEvent('edit-account', {
                                        detail: {
                                        }
                                    }))"
                    class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/5 dark:hover:text-gray-300">
                    Edit
                </button>

                <button type="submit"
                    class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-red-500 hover:bg-gray-100 hover:text-red-600 dark:hover:bg-white/5 dark:hover:text-red-500">
                    Delete
                </button>
            </x-common.dropdown-menu>
        </div>

        <div class="flex flex-col gap-1">
            <span class="text-sm text-white font-light">Balance</span>
            <div class="flex justify-between items-center">
                <span class="text-md text-white font-semibold">IDR {{ number_format($account->balance, 0, ',', '.') }}</span>
                @if (!empty($account->account_identifier))
                    <span class="text-md text-white font-semibold">{{ $account->name }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
