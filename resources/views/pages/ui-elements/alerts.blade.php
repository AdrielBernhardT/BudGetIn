@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('nav.alerts') }}" />

    <div class="space-y-5 sm:space-y-6">
        {{-- Success Alert --}}
        <x-common.component-card title="{{ __('alerts.success_alert') }}">
            <div class="space-y-4">
                <x-ui.alert
                    variant="success"
                    title="{{ __('alerts.success_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="true"
                    linkHref="/"
                    linkText="{{ __('alerts.learn_more') }}"
                />

                <x-ui.alert
                    variant="success"
                    title="{{ __('alerts.success_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="false"
                />
            </div>
        </x-common.component-card>

        {{-- Warning Alert --}}
        <x-common.component-card title="{{ __('alerts.warning_alert') }}">
            <div class="space-y-4">
                <x-ui.alert
                    variant="warning"
                    title="{{ __('alerts.warning_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="true"
                    linkHref="/"
                    linkText="{{ __('alerts.learn_more') }}"
                />

                <x-ui.alert
                    variant="warning"
                    title="{{ __('alerts.warning_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="false"
                />
            </div>
        </x-common.component-card>

        {{-- Error Alert --}}
        <x-common.component-card title="{{ __('alerts.error_alert') }}">
            <div class="space-y-4">
                <x-ui.alert
                    variant="error"
                    title="{{ __('alerts.error_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="true"
                    linkHref="/"
                    linkText="{{ __('alerts.learn_more') }}"
                />

                <x-ui.alert
                    variant="error"
                    title="{{ __('alerts.error_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="false"
                />
            </div>
        </x-common.component-card>

        {{-- Info Alert --}}
        <x-common.component-card title="{{ __('alerts.info_alert') }}">
            <div class="space-y-4">
                <x-ui.alert
                    variant="info"
                    title="{{ __('alerts.info_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="true"
                    linkHref="/"
                    linkText="{{ __('alerts.learn_more') }}"
                />

                <x-ui.alert
                    variant="info"
                    title="{{ __('alerts.info_message') }}"
                    message="{{ __('alerts.be_cautious_message') }}"
                    :showLink="false"
                />
            </div>
        </x-common.component-card>

        {{-- Additional Examples --}}
        <x-common.component-card title="{{ __('alerts.alert_variations') }}">
            <div class="space-y-4">
                {{-- With Slot Content --}}
                <x-ui.alert variant="success" title="{{ __('alerts.custom_content_alert') }}">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {!! __('alerts.custom_content_description', [
                            'slot' => '<strong class="text-gray-900 dark:text-white">' . __('alerts.custom_content_slot_label') . '</strong>',
                        ]) !!}
                    </p>
                    <ul class="mt-2 text-sm text-gray-500 dark:text-gray-400 list-disc list-inside">
                        <li>{{ __('alerts.custom_content_point_1') }}</li>
                        <li>{{ __('alerts.custom_content_point_2') }}</li>
                        <li>{{ __('alerts.custom_content_point_3') }}</li>
                    </ul>
                </x-ui.alert>

                {{-- Minimal Alert --}}
                <x-ui.alert
                    variant="info"
                    title="{{ __('alerts.quick_info') }}"
                    message="{{ __('alerts.quick_info_message') }}"
                />

                {{-- Alert with Long Message --}}
                <x-ui.alert
                    variant="warning"
                    title="{{ __('alerts.important_notice') }}"
                    message="{{ __('alerts.important_notice_message') }}"
                    :showLink="true"
                    linkHref="/docs"
                    linkText="{{ __('alerts.view_documentation') }}"
                />
            </div>
        </x-common.component-card>

        {{-- Interactive Demo --}}
        <x-common.component-card title="{{ __('alerts.real_world_examples') }}">
            <div class="space-y-4">
                {{-- Payment Success --}}
                <x-ui.alert variant="success" title="{{ __('alerts.payment_successful') }}">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        {!! __('alerts.payment_successful_message', [
                            'amount' => '<strong class="text-gray-900 dark:text-white">$99.00</strong>',
                        ]) !!}
                    </p>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p><strong>{{ __('alerts.order_id') }}</strong> #TAILADMIN-0014</p>
                        <p><strong>{{ __('alerts.transaction_id') }}</strong> TXN-1234567890</p>
                    </div>
                    <a href="/orders" class="inline-block mt-3 text-sm font-medium text-green-600 dark:text-green-400 underline hover:text-green-700">
                        {{ __('alerts.view_order_details') }}
                    </a>
                </x-ui.alert>

                {{-- Account Warning --}}
                <x-ui.alert
                    variant="warning"
                    title="{{ __('alerts.trial_ending_soon') }}"
                    message="{{ __('alerts.trial_ending_message') }}"
                    :showLink="true"
                    linkHref="/billing"
                    linkText="{{ __('alerts.upgrade_now') }}"
                />

                {{-- Validation Error --}}
                <x-ui.alert variant="error" title="{{ __('alerts.form_validation_failed') }}">
                    <ul class="text-sm text-gray-500 dark:text-gray-400 list-disc list-inside space-y-1">
                        <li>{{ __('alerts.validation_email_required') }}</li>
                        <li>{{ __('alerts.validation_password_min') }}</li>
                        <li>{{ __('alerts.validation_accept_terms') }}</li>
                    </ul>
                </x-ui.alert>

                {{-- System Info --}}
                <x-ui.alert
                    variant="info"
                    title="{{ __('alerts.scheduled_maintenance') }}"
                    message="{{ __('alerts.scheduled_maintenance_message') }}"
                    :showLink="true"
                    linkHref="/status"
                    linkText="{{ __('alerts.check_status_page') }}"
                />
            </div>
        </x-common.component-card>
    </div>
@endsection
