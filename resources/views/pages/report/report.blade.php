@extends('layouts.app')

@section('content')
    <div x-data="reportPage()">
        <x-common.page-breadcrumb pageTitle="Report" />

        <div class="mt-8 mb-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex gap-10 overflow-x-auto whitespace-nowrap no-scrollbar">
                <template x-for="type in reportTypes" :key="type.value">
                    <button type="button" @click="selectedReportType = type.value"
                        :class="selectedReportType === type.value ?
                            'border-b-2 border-main text-main' :
                            'text-gray-500 dark:text-gray-400'"
                        class="pb-2 text-theme-sm font-medium transition-colors duration-300" x-text="type.label">
                    </button>
                </template>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <x-report.header />
            <x-report.table />
            <x-report.pagination />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function reportPage() {
            const today = new Date();
            const todayString = today.toISOString().slice(0, 10);
            const monthString = today.toISOString().slice(0, 7);
            const yearString = today.getFullYear().toString();

            return {
                reports: @js($reports),

                itemsPerPage: 5,
                currentPage: 1,
                search: '',

                filterType: 'day',

                selectedReportType: 'all',

                reportTypes: [{
                        label: 'All',
                        value: 'all'
                    },
                    {
                        label: 'Income',
                        value: 'income'
                    },
                    {
                        label: 'Expense',
                        value: 'expense'
                    },
                    {
                        label: 'Transfer',
                        value: 'transfer'
                    },
                ],

                selectedDate: todayString,
                selectedMonth: monthString,
                selectedYear: yearString,

                get filteredReports() {
                    return this.reports.filter(r => {
                        const reportType = (r.type ?? '').toLowerCase();

                        const matchSearch = !this.search ||
                            (r.category?.name ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            reportType.includes(this.search.toLowerCase()) ||
                            (r.amount ?? '').toString().includes(this.search);

                        const matchType =
                            this.selectedReportType === 'all' ||
                            reportType === this.selectedReportType;

                        const reportDate = new Date(r.date);
                        let matchDate = true;

                        if (this.filterType === 'day') {
                            const selected = new Date(this.selectedDate);

                            matchDate =
                                reportDate.getDate() === selected.getDate() &&
                                reportDate.getMonth() === selected.getMonth() &&
                                reportDate.getFullYear() === selected.getFullYear();
                        }

                        if (this.filterType === 'month') {
                            const [year, month] = this.selectedMonth.split('-');

                            matchDate =
                                reportDate.getFullYear() === Number(year) &&
                                reportDate.getMonth() + 1 === Number(month);
                        }

                        if (this.filterType === 'year') {
                            matchDate =
                                reportDate.getFullYear() === Number(this.selectedYear);
                        }

                        return matchSearch && matchType && matchDate;
                    });
                },

                get totalEntries() {
                    return this.filteredReports.length;
                },

                get totalPages() {
                    return this.totalEntries === 0 ? 1 : Math.ceil(this.totalEntries / this.itemsPerPage);
                },

                get paginatedReports() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;

                    return this.filteredReports.slice(start, end);
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

                printReport() {
                    const params = new URLSearchParams({
                        type: this.selectedReportType,
                        filter: this.filterType,
                        date: this.selectedDate,
                        month: this.selectedMonth,
                    });

                    window.open(`/report/print?${params.toString()}`, "_blank");
                },

                init() {
                    this.$watch('search', () => {
                        this.currentPage = 1;
                    });

                    this.$watch('selectedReportType', () => {
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
