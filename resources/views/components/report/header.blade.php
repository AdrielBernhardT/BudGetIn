<style>
.flatpickr-calendar{
    transform: scale(0.9) !important;
    transform-origin: top left !important;
}
</style>

<div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

    <div class="flex items-center gap-3">
        <span class="text-gray-500 dark:text-gray-400">Show</span>

        <div class="relative">
            <select x-model.number="itemsPerPage" @change="currentPage = 1"
                class="w-full py-2 pl-3 pr-8 appearance-none text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg h-9 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>

            <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-2 top-1/2 dark:text-gray-400">
                <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke-width="1.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </div>

        <span class="text-gray-500 dark:text-gray-400">reports</span>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

        <div class="relative">
            <select x-model="filterType"
                class="h-[42px] appearance-none rounded-lg border border-gray-300 bg-transparent pl-4 pr-8 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="day">Day</option>
                <option value="month">Month</option>
            </select>

            <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-2 top-1/2 dark:text-gray-400">
                <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke-width="1.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </div>

        {{-- <div x-show="filterType === 'day'">
            <x-form.date-picker id="report_day" name="report_day" placeholder="Select Date" x-model="selectedDate"
                defaultDate="{{ now()->format('d-m-Y') }}" />
        </div>

        <div x-show="filterType === 'month'">
            <x-form.date-picker id="report_month" name="report_month" placeholder="Select Date" x-model="selectedDate"
                defaultDate="{{ now()->format('d-m-Y') }}" />
        </div>

        <div x-show="filterType === 'year'">
            <x-form.date-picker id="report_year" name="report_year" placeholder="Select Date" x-model="selectedDate"
                defaultDate="{{ now()->format('d-m-Y') }}" />
        </div> --}}
        <div x-show="filterType === 'day'">
            <x-form.date-picker 
                id="report_day" 
                name="report_day" 
                picker="day"
                placeholder="Select Date"
                x-model="selectedDate"
                dateFormat="Y-m-d"
                altFormat="d F Y"
                defaultDate="{{ now()->format('Y-m-d') }}" />
        </div>

        <div x-show="filterType === 'month'">
            <x-form.date-picker 
                id="report_month" 
                name="report_month" 
                picker="month"
                placeholder="Select Month"
                x-model="selectedMonth"
                dateFormat="Y-m-d"
                altFormat="F Y"
                defaultDate="{{ now()->format('Y-m-d') }}" />
        </div>

        {{-- <input x-show="filterType === 'month'" type="month" x-model="selectedMonth"
            onclick="this.showPicker && this.showPicker()"
            class="h-[42px] w-[165px] rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">

        <div x-show="filterType === 'year'" class="relative">
            <button type="button" @click="yearOpen = !yearOpen"
                class="h-[42px] w-[120px] rounded-lg border border-gray-300 bg-transparent px-4 text-left text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <span x-text="selectedYear"></span>
            </button>

            <div x-show="yearOpen" @click.away="yearOpen = false"
                class="absolute right-0 z-50 mt-2 w-[330px] rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-900">

                <div class="mb-3 flex items-center justify-between text-sm text-gray-800 dark:text-white/90">
                    <button type="button" @click="yearStart -= 12" class="px-2 py-1 text-gray-500 hover:text-gray-800">
                        ‹
                    </button>

                    <span>
                        <span x-text="yearStart"></span> - <span x-text="yearStart + 11"></span>
                    </span>

                    <button type="button" @click="yearStart += 12" class="px-2 py-1 text-gray-500 hover:text-gray-800">
                        ›
                    </button>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <template x-for="year in Array.from({ length: 12 }, (_, i) => yearStart + i)"
                        :key="year">
                        <button type="button" @click="selectedYear = year.toString(); yearOpen = false"
                            :class="selectedYear == year ? 'bg-main text-white' :
                                'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800'"
                            class="rounded-lg px-3 py-2 text-sm" x-text="year">
                        </button>
                    </template>
                </div>
            </div>
        </div> --}}

        <button type="button" @click="printReport()"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-main px-4 py-3 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-main-hover hover:text-white/90">
            Print Report
        </button>

    </div>

</div>
