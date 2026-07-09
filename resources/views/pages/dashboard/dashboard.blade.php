@extends('layouts.app')

@section('content')
    <div class="pt-3 pl-2 flex flex-col gap-4 md:gap-6">
        <div class="gap-2 flex flex-col">
            <h1 class="text-xl text-gray-800 dark:text-white/90 font-semibold lg:text-4xl md:text-2xl">
                Hello, {{ auth()->user()->fname }}
            </h1>

            <p class="text-gray-600 dark:text-white/70">
                Manage your money smarter, track expenses, and grow your savings.
            </p>
        </div>

        @if ($budgetAlert['show'])
            <div class="grid grid-cols-1 gap-4 md:gap-6 lg:grid-cols-3 2xl:grid-cols-[minmax(0,28fr)_minmax(0,52fr)_minmax(0,20fr)]">
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

        <x-dashboard.statistics :statistics="$statistics" :isStatistics="$firstIncomeOrExpense" />

        <div class="flex flex-col xl:flex-row gap-4 md:gap-6">
            <div class="w-full xl:w-[55%] min-w-0">
                <x-dashboard.recent.all-recent :recentTransactions="$recentTransactions" />
            </div>
            <div class="w-full xl:w-[45%] min-w-0">
                <x-dashboard.budget :monthlyBudgets="$monthlyBudgets" />
            </div>
        </div>
    </div>
@endsection
