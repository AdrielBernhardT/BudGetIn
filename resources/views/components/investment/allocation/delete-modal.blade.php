<div x-data="">
    <x-ui.modal @delete-investment-item.window="open = true" :isOpen="$errors->has('password')" class="max-w-[500px]">
    <div @delete-investment-item.window="item = $event.detail"
        x-data="{
            item: {
                title: ''
            },
            clearModal() {
                this.item = {
                    title: ''
                };
            }
        }"
        class="p-6 lg:p-8">

            <div @delete-investment-item.window="clearModal()" class="mb-6">
                <h4 class="mb-2 text-2xl font-semibold text-red-600">
                    Delete Item
                </h4>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    This action cannot be undone.
                </p>
            </div>

            <form method="POST" action="" class="flex flex-col gap-5">

                @csrf
                @method('DELETE')
                {{-- <div> --}}

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete item ? 
                </p>

                <div class="mt-4 flex items-center justify-end gap-3">

                    <button type="button" @click="open = false"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Cancel
                    </button>

                    <button type="submit" {{-- onclick="event.preventDefault(); this.closest('form').submit();" --}} {{-- onclick="return confirm('Are you sure want to delete this account?')" --}} {{-- onclick="return {{ confirmDelete('Are you sure want to delete this account?') }}" --}}
                        data-confirm-delete="true"
                        class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                        Delete Item
                    </button>

                </div>

            </form>
            {{-- </div> --}}
        </div>
    </x-ui.modal>
</div>