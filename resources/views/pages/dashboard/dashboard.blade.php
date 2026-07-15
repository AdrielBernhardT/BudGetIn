@extends('layouts.app')

@section('content')
    <div x-data="dashboardPage()">
        <div class="pt-3 pl-2 flex flex-col gap-4 md:gap-6">
            <div class="gap-2 flex flex-col">
                <h1 class="text-xl text-gray-800 dark:text-white/90 font-semibold lg:text-4xl md:text-2xl">
                    {{ __('dashboard.hello') }}, {{ auth()->user()->fname }}
                </h1>

                <p class="text-gray-600 dark:text-white/70">
                    {{ __('dashboard.dashboard_welcome_message') }}
                </p>
            </div>

            @if ($budgetAlert['show'])
                <div
                    class="grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-3 2xl:grid-cols-[minmax(0,28fr)_minmax(0,52fr)_minmax(0,20fr)]">
                    {{-- Summary --}}
                    <div class="order-1 w-full min-w-0 lg:col-span-2 2xl:col-span-1">
                        <x-dashboard.summary.summary :summary="$summary" />
                    </div>

                    {{-- Metrics --}}
                    <div class="order-2 w-full min-w-0 lg:order-3 lg:col-span-3 2xl:order-2 2xl:col-span-1">
                        <x-dashboard.metrics :metrics="$metrics" />
                    </div>

                    {{-- Alert --}}
                    <div class="order-3 w-full min-w-0 lg:order-2 lg:col-span-1 2xl:order-3 2xl:col-span-1">
                        <x-dashboard.alert :budgetAlert="$budgetAlert" />
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-3 2xl:grid-cols-12">
                    {{-- Summary --}}
                    <div class="order-1 w-full min-w-0 lg:col-span-1 2xl:col-span-4">
                        <x-dashboard.summary.summary :summary="$summary" />
                    </div>

                    {{-- Metrics --}}
                    <div class="order-2 w-full min-w-0 lg:order-3 lg:col-span-2 2xl:order-2 2xl:col-span-8">
                        <x-dashboard.metrics :metrics="$metrics" />
                    </div>

                    {{-- Alert
                <div class="order-3 w-full min-w-0 lg:order-2 lg:col-span-1 2xl:order-3 2xl:col-span-1">
                    <x-dashboard.alert :budgetAlert="$budgetAlert" />
                </div> --}}
                </div>
            @endif

            <x-dashboard.statistics :statistics="$statistics" :hasStatistics="$hasStatistics" />

            <div class="flex flex-col xl:flex-row gap-4 md:gap-6">
                <div class="w-full xl:w-[55%] min-w-0">
                    <x-dashboard.recent.all-recent :recentTransactions="$recentTransactions" />
                </div>
                <div class="w-full xl:w-[45%] min-w-0">
                    <x-dashboard.budget :monthlyBudgets="$monthlyBudgets" />
                </div>
            </div>
            <x-dashboard.summary.add-account-modal />
            <x-dashboard.summary.edit-account-modal />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function dashboardPage() {
            return {
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
                }
            }
        }

        document.addEventListener('submit', function(event) {
            const form = event.target.closest('.js-delete-form');

            if (!form) return;

            if (form.dataset.submitted === 'true') {
                return;
            }

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: form.dataset.confirmTitle || 'Are you sure?',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#FF3B30',
                cancelButtonColor: '#667085',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.submitted = 'true';
                    form.submit();
                }
            });
        });
    </script>
@endpush
