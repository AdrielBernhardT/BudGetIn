@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('nav.notifications') }}" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-800">
            <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ __('sentence.all_notifications') }}</h4>

            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit"
                    class="text-sm font-medium text-main hover:underline">
                    {{ __('sentence.mark_all_as_read') }}
                </button>
            </form>
        </div>

        <ul class="flex flex-col">
            @forelse ($notifications as $notification)
                <li>
                    <a href="{{ $notification->data['url'] ?? '#' }}"
                        @if (!$notification->read_at)
                            onclick="event.preventDefault(); document.getElementById('read-form-{{ $notification->id }}').submit();"
                        @endif
                        class="flex items-start gap-4 rounded-lg border-b border-gray-100 px-3 py-4 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/5 {{ $notification->read_at ? '' : 'bg-brand-50/40 dark:bg-white/[0.03]' }}">
                        <span class="flex items-center justify-center w-10 h-10 text-lg rounded-full shrink-0 bg-gray-100 dark:bg-gray-800">
                            @php
                                $emojiMap = [
                                    'goal_reached' => '🏆',
                                    'goal_deadline_approaching' => '⏰',
                                    'goal_missed' => '⚠️',
                                    'monthly_investment_reminder' => '💰',
                                    'transaction_recorded' => '📝',
                                ];
                            @endphp
                            {{ $emojiMap[$notification->data['category'] ?? ''] ?? '🔔' }}
                        </span>

                        <span class="flex-1">
                            <span class="block mb-1 text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $notification->data['title'] ?? __('sentence.notification_fallback_title') }}
                            </span>
                            <span class="block mb-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $notification->data['message'] ?? '' }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </span>

                        @if (!$notification->read_at)
                            <span class="w-2.5 h-2.5 mt-1 rounded-full bg-orange-400 shrink-0"></span>
                        @endif
                    </a>

                    @if (!$notification->read_at)
                        <form id="read-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification->id) }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ $notification->data['url'] ?? url()->previous() }}">
                        </form>
                    @endif
                </li>
            @empty
                <li class="py-12 text-sm text-center text-gray-400 dark:text-gray-500">
                    {{ __('sentence.no_notifications_yet') }}
                </li>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
