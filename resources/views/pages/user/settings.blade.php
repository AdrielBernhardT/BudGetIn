@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Settings" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ __('settings.password') }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 py-3">
                    {{ __('settings.password_subtitle') }}
                </p>
            </div>
            <button @click="$dispatch('open-password-modal')"
                class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-white/90 dark:hover:bg-white/5">
                {{ __('settings.change_password') }}
            </button>
        </div>

        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-800"
            x-data="pushToggle()" x-init="init()">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90 pt-2">{{ __('settings.notifications') }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 py-3">
                    {{ __('settings.notifications_subtitle') }}
                </p>
                <p x-show="error" x-text="error" class="text-sm text-red-500 -mt-2 mb-2"></p>
            </div>

            <label class="relative inline-flex cursor-pointer items-center">
                <input type="checkbox" x-ref="checkbox" class="peer sr-only" :checked="subscribed" :disabled="loading" @change="toggle()">

                <div
                    class="peer h-7 w-12 rounded-full bg-gray-300 transition-all
                peer-checked:bg-main
                after:absolute after:left-[4px] after:top-[4px]
                after:h-5 after:w-5 after:rounded-full
                after:bg-white after:transition-all
                peer-checked:after:translate-x-5"
                :class="{ 'opacity-50': loading }">
                </div>
            </label>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90 pt-2">{{ __('settings.delete_account') }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 pt-2">
                    {{ __('settings.delete_account_subtitle') }}
                </p>
            </div>

            <a href="{{ route('settings.delete-account') }}" type="submit" data-confirm-delete="true"
                class="rounded-lg border border-red-300 px-5 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-500/10">
                {{ __('settings.delete_account') }}
            </a>
        </div>

    </div>
@endsection

<x-ui.modal
    @open-password-modal.window="open = true" :isOpen="$errors->any()" class="max-w-[500px]">
    <div class="p-6 lg:p-8">
        <div class="mb-6">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ __('settings.change_password') }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('settings.update_password') }}</p>
        </div>
        <form class="flex flex-col gap-5"
            method="POST"
            action="{{ route('settings.change-password') }}"
        >
            @csrf
            @method('POST')
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.current_password') }}</label>
                <input
                    name="currentPassword"
                    value="{{ old('currentPassword') }}"
                    type="password"
                    placeholder="{{ __('settings.current_password_placeholder') }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />

                    @error('currentPassword')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.new_password') }}</label>
                <input
                    name="newPassword"
                    value="{{ old('newPassword') }}"
                    type="password"
                    placeholder="{{ __('settings.new_password_placeholder') }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />

                    @error('newPassword')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('settings.confirm_password') }}</label>
                <input
                    name="confirmPassword"
                    value="{{ old('confirmPassword') }}"
                    type="password"
                    placeholder="{{ __('settings.confirm_password_placeholder') }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />

                    @error('confirmPassword')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
            </div>
            <div class="mt-4 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="open = false"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    {{ __('common.cancel') }}
                </button>
                <button
                    type="submit"
                    class="rounded-lg bg-main px-4 py-2.5 text-sm font-medium text-white hover:bg-main-hover">
                    {{ __('settings.update_password') }}
                </button>
            </div>
        </form>
    </div>
</x-ui.modal>

@push('scripts')
<script>
    function pushToggle() {
        return {
            subscribed: false,
            loading: true,
            error: '',

            async init() {
                if (!window.PushNotifications || !window.PushNotifications.isSupported()) {
                    this.error = @json(__('settings.push_not_supported'));
                    this.loading = false;
                    return;
                }

                this.subscribed = await window.PushNotifications.isSubscribed();
                this.loading = false;
                this.$refs.checkbox.checked = this.subscribed;
            },

            async toggle() {
                this.loading = true;
                this.error = '';

                try {
                    if (this.subscribed) {
                        await window.PushNotifications.unsubscribe();
                        this.subscribed = false;
                    } else {
                        await window.PushNotifications.subscribe();
                        this.subscribed = true;
                    }
                } catch (e) {
                    this.error = e.message || @json(__('settings.push_toggle_failed'));
                    // Re-check the real browser subscription state in case it desynced
                    this.subscribed = await window.PushNotifications.isSubscribed();
                } finally {
                    this.loading = false;
                    // Force the checkbox DOM to match the real state (native click can flip it early)
                    this.$refs.checkbox.checked = this.subscribed;
                }
            },
        };
    }
</script>
@endpush
