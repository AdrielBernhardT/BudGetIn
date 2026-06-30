@props(['goals'])

@if($goals->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center">
        <p class="text-gray-500">
            No Goals Found
        </p>
    </div>
@else
    <div class="max-h-[700px] overflow-y-auto custom-scrollbar">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($goals as $goal)
                <div
                    class="rounded-2xl border border-gray-200 bg-white h-40 p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-5">

                    <div  x-data="{ open: false }" class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="{{ $goal->icon }}" class="h-9 w-9 p-1 rounded-lg shrink-0 dark:text-white"></i>

                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white/90">
                                    {{ $goal->name }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    IDR {{ number_format($goal->target_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <x-common.dropdown-menu />
                        {{-- <div class="relative">
                            <button
                                @click="open = !open"
                                class="rounded-md p-1 text-xl hover:bg-gray-100"
                            >
                                ⋮
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 top-10 z-50 w-32 rounded-2xl border border-gray-200 bg-white p-2"
                            >
                                <button class="w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-gray-800 hover:bg-gray-100">
                                    Edit
                                </button>

                                <button class="w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-red-500 hover:bg-gray-100">
                                    Delete
                                </button>
                            </div>
                        </div> --}}
                    </div>

                    <div class="mt-1">
                        <div class="mb-2 flex items-center justify-between">
                            <span></span>
                            <span class="font-semibold dark:text-white">
                                {{ $goal->percentage }}%
                            </span>
                        </div>
                        <div class="relative h-2 rounded-sm bg-gray-200 dark:bg-gray-800">
                            <div
                                class="absolute left-0 top-0 h-full rounded-sm bg-main"
                                style="width: {{ $goal->percentage }}%">
                            </div>
                        </div>

                        <p class="mt-3 text-sm font-medium text-gray-800 dark:text-white/90">
                            IDR {{ number_format($goal->current_amount, 0, ',', '.') }}
                            /
                            IDR {{ number_format($goal->target_amount, 0, ',', '.') }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endif