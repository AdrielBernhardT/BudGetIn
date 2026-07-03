@props([
    'histories' => [],
])

<div x-data="investmentHistoryPage()">
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-investment.history.header />

        <x-investment.history.table />

        <x-investment.history.pagination />
    </div>
</div>

@once
    @push('scripts')
        <script>
            function investmentHistoryPage() {
                const today = new Date();
                const todayString = today.toISOString().slice(0, 10);
                const monthString = today.toISOString().slice(0, 7);
                const yearString = today.getFullYear().toString();

                return {
                    histories: @js($histories),

                    itemsPerPage: 5,
                    currentPage: 1,
                    search: '',

                    filterType: 'day',

                    selectedDate: todayString,
                    selectedMonth: monthString,
                    selectedYear: yearString,

                    get filteredHistories() {
                        return this.histories.filter(history => {
                            const matchSearch = !this.search ||
                                (history.name ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                                (history.title ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                                (history.goal?.name ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                                (history.amount ?? '').toString().includes(this.search);

                            const historyDate = new Date(history.date);
                            let matchDate = true;

                            if (this.filterType === 'day') {
                                const selected = new Date(this.selectedDate);

                                matchDate =
                                    historyDate.getDate() === selected.getDate() &&
                                    historyDate.getMonth() === selected.getMonth() &&
                                    historyDate.getFullYear() === selected.getFullYear();
                            }

                            if (this.filterType === 'month') {
                                const [year, month] = this.selectedMonth.split('-');

                                matchDate =
                                    historyDate.getFullYear() === Number(year) &&
                                    historyDate.getMonth() + 1 === Number(month);
                            }

                            if (this.filterType === 'year') {
                                matchDate =
                                    historyDate.getFullYear() === Number(this.selectedYear);
                            }

                            return matchSearch && matchDate;
                        });
                    },

                    get totalEntries() {
                        return this.filteredHistories.length;
                    },

                    get totalPages() {
                        return this.totalEntries === 0 ? 1 : Math.ceil(this.totalEntries / this.itemsPerPage);
                    },

                    get paginatedHistories() {
                        const start = (this.currentPage - 1) * this.itemsPerPage;
                        const end = start + this.itemsPerPage;

                        return this.filteredHistories.slice(start, end);
                    },

                    get start() {
                        return this.totalEntries === 0 ? 0 : (this.currentPage - 1) * this.itemsPerPage + 1;
                    },

                    get end() {
                        const end = this.currentPage * this.itemsPerPage;

                        return end > this.totalEntries ? this.totalEntries : end;
                    },

                    get displayedPages() {
                        const range = [];

                        for (let i = 1; i <= this.totalPages; i++) {
                            if (
                                i === 1 ||
                                i === this.totalPages ||
                                (i >= this.currentPage - 1 && i <= this.currentPage + 1)
                            ) {
                                range.push(i);
                            } else if (range[range.length - 1] !== '...') {
                                range.push('...');
                            }
                        }

                        return range;
                    },

                    prevPage() {
                        if (this.currentPage > 1) {
                            this.currentPage--;
                        }
                    },

                    nextPage() {
                        if (this.currentPage < this.totalPages) {
                            this.currentPage++;
                        }
                    },

                    goToPage(page) {
                        if (typeof page === 'number' && page >= 1 && page <= this.totalPages) {
                            this.currentPage = page;
                        }
                    },

                    formatRupiah(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value ?? 0);
                    },

                    formatDate(value) {
                        if (!value) return '-';

                        return new Date(value).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                    },

                    init() {
                        this.$watch('search', () => {
                            this.currentPage = 1;
                        });

                        this.$watch('itemsPerPage', () => {
                            this.currentPage = 1;
                        });

                        this.$watch('filterType', (type) => {
                            if (type === 'month') {
                                const month = this.selectedMonth.split('-')[1] ?? '01';
                                this.selectedMonth = `${this.selectedYear}-${month}`;
                            }

                            if (type === 'day') {
                                const month = this.selectedMonth.split('-')[1] ?? '01';
                                const day = this.selectedDate.split('-')[2] ?? '01';
                                this.selectedDate = `${this.selectedYear}-${month}-${day}`;
                            }

                            this.currentPage = 1;
                        });

                        this.$watch('selectedDate', (value) => {
                            if (!value) return;

                            const [year, month] = value.split('-');

                            this.selectedYear = year;
                            this.selectedMonth = `${year}-${month}`;
                            this.currentPage = 1;
                        });

                        this.$watch('selectedMonth', (value) => {
                            if (!value) return;

                            const [year] = value.split('-');

                            this.selectedYear = year;
                            this.currentPage = 1;
                        });

                        this.$watch('selectedYear', (value) => {
                            if (!value) return;

                            const month = this.selectedMonth.split('-')[1] ?? '01';
                            const day = this.selectedDate.split('-')[2] ?? '01';

                            this.selectedMonth = `${value}-${month}`;
                            this.selectedDate = `${value}-${month}-${day}`;
                            this.currentPage = 1;
                        });
                    }
                }
            }
        </script>
    @endpush
@endonce