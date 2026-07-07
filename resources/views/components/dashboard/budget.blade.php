@props(['monthlyBudgets'])

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex flex-col gap-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            This Month's Budget
        </h3>

        @forelse($monthlyBudgets->chunk(2) as $row)

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">

                @foreach($row as $budget)

                    <div class="mt-1">

                        <div class="mb-2 flex items-center justify-between">

                            <span class="text-gray-900 dark:text-white/90">
                                {{ $budget['category'] }}
                            </span>

                            <span class="font-semibold text-gray-900 dark:text-white/90">
                                {{ $budget['percentage'] }}%
                            </span>

                        </div>

                        <div class="relative h-2 rounded-sm bg-gray-200 dark:bg-gray-800">

                            <div
                                class="absolute left-0 top-0 h-full rounded-sm bg-main"
                                style="width: {{ $budget['percentage'] }}%">
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @empty

            <p class="text-center text-gray-500 py-8">
                No monthly budgets found
            </p>

        @endforelse

    </div>
</div>
