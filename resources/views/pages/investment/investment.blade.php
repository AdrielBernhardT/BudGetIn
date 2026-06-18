@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Investment" />

    <div x-data="investmentPage()">
        <x-investment.top-summary :datas="$datas" />

        <div class="mt-8 flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center">
            <div class="flex gap-10 overflow-x-auto whitespace-nowrap no-scrollbar ">
                <button @click="tab='allocation'"
                    :class="tab === 'allocation' ? 'border-b-2 border-main text-main' : 'text-gray-500 dark:text-gray-400'"
                    class="pb-2 text-theme-sm font-medium transition-colors duration-300">
                    Allocation
                </button>
                <button @click="tab='history'"
                    :class="tab === 'history' ? 'border-b-2 border-main text-main' : 'text-gray-500 dark:text-gray-400'"
                    class="pb-2 text-theme-sm font-medium transition-colors duration-300">
                    History
                </button>
            </div>
            <div class="flex flex-col gap-2 lg:flex-row lg:w-auto">
                <div class="grid grid-cols-2 gap-2 lg:flex">
                    <button @click="$dispatch('add-goal')"
                        class="w-full lg:w-auto whitespace-nowrap justify-center inline-flex items-center gap-3 rounded-lg border border-gray-300 bg-white/90 px-4 py-2 text-theme-xs md:text-theme-sm font-medium text-gray-800 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white dark:hover:bg-white/[0.07] dark:hover:text-white/90">
                        <i data-lucide="goal" class="w-3 h-3 md:w-4 md:h-4 shrink-0 text-gray-800 dark:text-white"></i>
                        Add Goal
                    </button>
                    <button @click="$dispatch('add-investment')"
                        class="w-full lg:w-auto whitespace-nowrap justify-center inline-flex items-center gap-3 rounded-lg border border-gray-300 bg-white/90 px-4 py-2 text-theme-xs md:text-theme-sm font-medium text-gray-800 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white dark:hover:bg-white/[0.07] dark:hover:text-white/90">
                        <i data-lucide="plus" class="w-3 h-3 md:w-4 md:h-4 shrink-0 text-gray-800 dark:text-white"></i>
                        Add Investment
                    </button>
                </div>
                <button @click="$dispatch('record-investment')"
                    class="w-full lg:w-auto whitespace-nowrap justify-center inline-flex items-center gap-3 rounded-lg border border-gray-300 bg-main px-4 py-2 text-theme-xs md:text-theme-sm font-medium text-white shadow-theme-xs hover:bg-main-hover hover:text-white/90 dark:border-gray-700 dark:bg-main dark:text-white dark:hover:bg-main-hover dark:hover:text-white/90">
                    <i data-lucide="pencil-line" class="w-3 h-3 md:w-4 md:h-4 shrink-0 text-white dark:text-white"></i>
                    Record Investment
                </button>
            </div>
        </div>

        <div class="mt-4">
            <div x-show="tab === 'allocation'">
                <x-investment.allocation.allocation :datas="$datas" />
            </div>
            <div x-show="tab === 'history'">
                Transaction History content goes here...
            </div>
        </div>

        <x-investment.add-goal-modal />
        <x-investment.add-investment-modal :goals="$goals" />
        <x-investment.record-investment-modal :goals="$goals" :investments="$investments" :accounts="$accounts" />
    </div>

@endsection

@push('scripts')
    <script>
        function investmentPage() {
            return {
                tab: 'allocation',
                formatRupiah(value) {
                    value = value.toString();
                    let number = value.replace(/[^,\d]/g, '').toString();
                    let split = number.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                },
                init() {
                    this.$nextTick(() => {
                        window.createIcons();
                    });
                },
            }
        }
    </script>
@endpush