<div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div class="flex items-center gap-3">
        <span class="text-gray-500 dark:text-gray-400 ">{{ __('pagination.show') }}</span>
        <div class="relative">
            <select x-model.number="itemsPerPage" @change="currentPage = 1"
                class="w-full py-2 pl-3 pr-8 appearance-none text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg dark:bg-dark-900 h-9 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>
            <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-2 top-1/2 dark:text-gray-400">
                <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="" stroke-width="1.2"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        </div>
        <span class="text-gray-500 dark:text-gray-400 ">{{ __('nav.expenses') }}</span>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <form>
            <div class="relative">
                <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                            fill="" />
                    </svg>
                </button>
                <input type="text" placeholder="{{ __('common.search') }}..."
                    x-model="search"
                    class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]" />
            </div>
        </form>
        <button @click="openCreateModal()"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-main px-4 py-3 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-main-hover hover:text-white/90 dark:border-gray-700 dark:bg-main dark:text-white dark:hover:bg-main-hover dark:hover:text-white/90">
            {{ __('common.add_expense') }}
        </button>
    </div>
</div>
