<div x-data="investmentPage()">
    <x-ui.modal
        x-data="{ open: false }"
        @edit-goal.window="open = true"
        :isOpen="false"
        class="max-w-[700px]"
    >
        <div
            x-data="{
                baseUrl: '{{ url('/investment/update/goal') }}',
                action: '',
                goal: {
                    id: '',
                    name: '',
                    amount: '',
                    amount_display: '',
                    icon: 'home',
                    target_date: '',
                },

                formatRupiahEdit(value) {
                    let number = String(value ?? '').replace(/\D/g, '');
                    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },

                loadGoal(data) {
                    let amount = String(data.target_amount ?? '').replace(/\D/g, '');

                    this.goal = {
                        id: data.id ?? '',
                        name: data.name ?? '',
                        amount: amount,
                        amount_display: this.formatRupiahEdit(amount),
                        icon: data.icon ?? 'home',
                        target_date: data.target_date ?? '',
                    };

                    this.action = `${this.baseUrl}/${data.id}`;
                    this.$nextTick(() => {
                        const input = document.getElementById('edit_goal_target_date');
                        if (input && input._flatpickr) {
                            input._flatpickr.setDate(this.goal.target_date, true);
                        }
                    });

                    this.$nextTick(() => {
                        this.$dispatch('target-icon-set', this.goal.icon || 'home');
                    });
                }
            }"
            @edit-goal.window="loadGoal($event.detail.goal)"
            class="no-scrollbar relative w-full max-w-[700px] max-h-[80vh] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11 overflow-y-auto"
        >
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Edit Goal
                </h4>

                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    Update your financial goal information.
                </p>
            </div>

            <form class="flex flex-col" method="POST" :action="action">
                @csrf
                @method('POST')

                <input type="hidden" name="id" x-model="goal.id">

                <div class="custom-scrollbar max-h-[40vh] lg:max-h-[60vh] flex flex-col gap-5 overflow-y-auto p-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Goal Name
                        </label>

                        <div class="relative flex items-center gap-2">
                            <x-icon.icon-picker @target-icon-set.window="selected = $event.detail; refresh()" />

                            <input
                                type="text"
                                name="name"
                                x-model="goal.name"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />
                        </div>

                        @error('name', 'goal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Target Amount
                        </label>

                        <div class="relative">
                            <span
                                class="absolute top-1/2 left-0 inline-flex h-11 -translate-y-1/2 items-center justify-center border-r border-gray-200 py-3 pr-3 pl-3.5 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                IDR
                            </span>

                            <input
                                type="text"
                                x-model="goal.amount_display"
                                @input="
                                    goal.amount_display = formatRupiahEdit($event.target.value);
                                    goal.amount = $event.target.value.replace(/\D/g, '');
                                "
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-16 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            />

                            <input type="hidden" name="target_amount" :value="goal.amount" />
                        </div>

                        @error('target_amount', 'goal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Deadline (Optional)
                        </label>

                        <x-form.date-picker
                            id="edit_goal_target_date"
                            name="target_date"
                            placeholder="Select goal deadline"
                            x-model="goal.target_date"
                        />

                        <p class="mt-1 text-xs text-gray-400">
                            Used for progress reminders &amp; notifications when the deadline is approaching.
                        </p>

                        @error('target_date', 'goal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button
                            @click="open = false"
                            type="button"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
                        >
                            Close
                        </button>

                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover sm:w-auto"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>