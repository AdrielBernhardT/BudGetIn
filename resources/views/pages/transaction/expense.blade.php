@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('nav.expense') }}" />
    <div
        class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        @if ($categories->isEmpty())
            <x-expense.no-category-card />
        @endif
        <div x-data="expensePage()">
            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <x-expense.header />
                <x-expense.table />
                <x-expense.pagination />
            </div>
            <x-expense.modal :categories="$categories" :accounts="$accounts" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function expensePage() {
            return {
                expenses: @js($expenses),
                categories: @js($categories),
                itemsPerPage: 5,
                currentPage: 1,
                dropdownOpen: null,
                search: '',
                get totalPages() {
                    return this.totalEntries === 0 ? 1 : Math.ceil(this.totalEntries / this.itemsPerPage);
                },
                get paginatedexpenses() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    const data = this.filteredExpenses;

                    return data.slice(start, end);
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
                getStatusClass(status) {
                    const classes = {
                        'Success': 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500',
                        'Pending': 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-orange-400',
                        'Failed': 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
                    };
                    return classes[status] || '';
                },
                toggleDropdown(id) {
                    this.dropdownOpen = this.dropdownOpen === id ? null : id;
                },
                openCreateModal() {
                    this.$dispatch('open-expense-modal', {
                        mode: 'create'
                    });
                },
                openEditModal(expense) {
                    this.$dispatch('open-expense-modal', {
                        mode: 'edit',
                        expense: {
                            ...expense
                        },
                    });
                },
                get totalEntries() {
                    return this.filteredExpenses.length;
                },
                get start() {
                    return this.totalEntries === 0 ? 0 : (this.currentPage - 1) * this.itemsPerPage + 1;
                },
                get end() {
                    const end = this.currentPage * this.itemsPerPage;
                    return end > this.totalEntries ? this.totalEntries : end;
                },
                price: '',
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

                    this.$watch('currentPage', () => {
                        this.$nextTick(() => {
                            window.createIcons();
                        });
                    });

                    this.$watch('itemsPerPage', () => {
                        this.$nextTick(() => {
                            window.createIcons();
                        });
                    });

                    this.$watch('search', () => {
                        this.currentPage = 1;
                    });
                },
                get filteredExpenses(){
                    if(!this.search) return this.expenses;

                    return this.expenses.filter(t => {
                        return (
                            (t.title ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            (t.description ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            (t.from_account?.name ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            (t.category?.name ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            (t.amount ?? '').toString().includes(this.search) ||
                            (t.date ?? '').includes(this.search)
                        );
                    });
                },
            }
        }
    </script>
@endpush
