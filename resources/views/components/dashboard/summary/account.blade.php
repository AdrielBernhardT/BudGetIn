@props(['account'])

<div class="col-span-8 rounded-2xl bg-linear-to-br from-[#0F2A5F] to-[#1E40AF] p-5 md:p-4">
    <div class="flex flex-col justify-between gap-4">
        <div class="flex items-center justify-between">
            @if (!empty($account->account_identifier))
                <div x-data="{ copied: false }" class="flex items-center gap-2">
                    <span class="text-sm font-normal text-white">
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
                <span class="text-md font-semibold text-white">
                    {{ $account->name }}
                </span>
            @endif

            <x-common.dropdown-menu activeClass="text-white" inactiveClass="text-white hover:text-gray-200">
                <button type="button"
                    @click="
                    openDropDown = false;

                    window.dispatchEvent(new CustomEvent('edit-account', {
                        detail: {
                            id: {{ $account->id }},
                            name: @js($account->name),
                            account_identifier: @js($account->account_identifier),
                            balance: {{ $account->balance }}
                        }
                    }));
                "
                    class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/5 dark:hover:text-gray-300">
                    {{ __('common.edit') }}
                </button>

                <a href="{{ route('account.delete', $account->id) }}"
                    class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-red-500 hover:bg-gray-100 hover:text-red-600 dark:hover:bg-white/5 dark:hover:text-red-500"
                    data-confirm-delete="true">
                    {{ __('common.delete') }}
                </a>
            </x-common.dropdown-menu>
        </div>

        <div class="flex flex-col gap-1">
            <span class="text-sm font-light text-white">
                {{ __('common.balance') }}
            </span>

            <div class="flex items-center justify-between gap-2">
                <span class="text-md font-semibold text-white">
                    {{ __('common.idr') }}
                    {{ number_format($account->balance, 0, ',', '.') }}
                </span>

                @if (!empty($account->account_identifier))
                    <div x-data="{ show: false }" class="relative">
                        <span @mouseenter="show = true" @mouseleave="show = false"
                            class="cursor-pointer text-md font-semibold text-white">
                            {{ \Illuminate\Support\Str::limit($account->name, 4, '..') }}
                        </span>

                        <div x-show="show" x-transition
                            class="absolute bottom-full right-0 mb-2 whitespace-nowrap rounded-lg bg-gray-900 px-3 py-2 text-xs text-white shadow-lg">
                            {{ $account->name }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
