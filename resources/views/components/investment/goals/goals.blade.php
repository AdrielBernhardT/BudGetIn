@props(['targets'])

<div x-data>
    @if ($targets->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center">
            <p class="text-gray-500">
                No Goals Found
            </p>
        </div>
    @else
        <div class="max-h-[700px] overflow-y-auto custom-scrollbar">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($targets as $goal)
                    <div
                        class="rounded-2xl border border-gray-200 bg-white h-40 p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-5">

                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <i data-lucide="{{ $goal->icon }}"
                                    class="h-9 w-9 p-1 rounded-lg shrink-0 dark:text-white"></i>

                                <div>
                                    <h3 class="font-semibold text-gray-800 dark:text-white/90">
                                        {{ $goal->name }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        IDR {{ number_format($goal->target_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <x-common.dropdown-menu>
                                <button type="button"
                                    @click="openDropDown = false;
                                    window.dispatchEvent(new CustomEvent('edit-goal', {
                                        detail: {
                                            goal: @js([
                                                'id' => $goal->id,
                                                'name' => $goal->name,
                                                'icon' => $goal->icon,
                                                'target_amount' => $goal->target_amount,
                                                'target_date' => $goal->target_date?->format('Y-m-d'),
                                            ])
                                        }
                                    }))"
                                    class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                    Edit
                                </button>

                                <form action="{{ route('investment.goal.delete', $goal->id) }}" method="POST" class="js-delete-form"
                                    data-confirm-title="Are you sure you want to delete this goal?">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-red-500 hover:bg-gray-100 hover:text-red-600 dark:hover:bg-white/5 dark:hover:text-red-500">
                                        Delete
                                    </button>
                                </form>
                            </x-common.dropdown-menu>
                        </div>

                        <div class="mt-1">
                            <div class="mb-2 flex items-center justify-between">
                                <span></span>

                                <span class="font-semibold dark:text-white">
                                    {{ $goal->percentage }}%
                                </span>
                            </div>

                            <div class="relative h-2 rounded-sm bg-gray-200 dark:bg-gray-800">
                                <div class="absolute left-0 top-0 h-full rounded-sm bg-main"
                                    style="width: {{ $goal->percentage }}%">
                                </div>
                            </div>

                            <p class="mt-3 text-sm font-medium text-gray-800 dark:text-white/90">
                                IDR {{ number_format($goal->total_current, 0, ',', '.') }}
                                /
                                IDR {{ number_format($goal->target_amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @once
            <script>
                document.addEventListener('submit', function(event) {
                    const form = event.target.closest('.js-delete-form');

                    if (!form) return;

                    event.preventDefault();

                    const title = form.dataset.confirmTitle || 'Are you sure you want to delete this data?';

                    Swal.fire({
                        icon: 'warning',
                        title: title,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#FF3B30',
                        cancelButtonColor: '#667085',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            </script>
        @endonce
    @endif
</div>

<x-investment.goals.edit-modal />
