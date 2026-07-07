<div class="overflow-hidden">
    <div class="max-w-full px-5 overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-gray-200 border-y dark:border-gray-700">
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">No</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Date</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Type</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Category</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Title</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Amount</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Account</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Description</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(report, index) in paginatedReports" :key="`${report.type}-${report.id}`">
                    <tr>
                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                                x-text="(currentPage - 1) * itemsPerPage + index + 1">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="report.date">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <span
                                :class="{
                                    'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500': (report.type ?? '').toLowerCase() === 'income',
                                    'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500': (report.type ?? '').toLowerCase() === 'expense',
                                    'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-500': (report.type ?? '').toLowerCase() === 'transfer'
                                }"
                                class="rounded-full px-2 py-1 text-xs font-medium capitalize"
                                x-text="report.type">
                            </span>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="report.category?.name ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="report.title ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="formatRupiah(report.amount)">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="report.account?.name ?? report.to_account?.name ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedReports.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="report.description ?? '-'">
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-if="paginatedReports.length === 0">
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 dark:text-gray-400">
                            No reports found
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>