@props(['account'])

<div class="col-span-8 rounded-2xl bg-linear-to-br from-[#0F2A5F] to-[#1E40AF] p-5 md:p-4">
    <div class="flex flex-col justify-between gap-4">
        <div class="flex items-center justify-between">
            @if (!empty($account->account_identifier))
                <div
                    x-data="{ copied: false }"
                    class="flex items-center gap-2"
                >
                    <span class="text-sm text-white font-normal">
                        {{ $account->account_identifier }}
                    </span>

                    <button
                        type="button"
                        class="cursor-pointer"
                        @click="
                            navigator.clipboard.writeText('{{ $account->account_identifier }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        "
                    >
                        <i
                            x-show="!copied"
                            data-lucide="copy"
                            class="w-3 h-3 text-white"
                        ></i>

                        <i
                            x-show="copied"
                            data-lucide="check"
                            class="w-3 h-3 text-green-400"
                        ></i>
                    </button>
                </div>
            @endif
            <span class="text-md text-white font-semibold">{{ $account->name }}</span>
        </div>

        <div class="flex flex-col gap-1">
            <span class="text-sm text-white font-light">Balance</span>
            <span class="text-md text-white font-semibold">IDR {{ number_format($account->balance,0,',','.') }}</span>
        </div>
    </div>
</div>