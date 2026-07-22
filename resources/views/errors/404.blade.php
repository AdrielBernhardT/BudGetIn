@extends('layouts.fullscreen-layout')

@section('content')
    @php
        $currentYear = date('Y');
    @endphp
    <div class="relative flex flex-col items-center justify-center min-h-screen p-6 overflow-hidden z-1">
        {{-- common grid shape --}}
        <x-common.common-grid-shape />
        <!-- Centered Content -->
        <div class="mx-auto w-full max-w-[242px] text-center sm:max-w-[472px]">
            <h1 class="mb-8 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-2xl">
                {{ __('errors.error_title') }}
            </h1>

            {{-- <img src="/images/error/404.svg" alt="404" class="dark:hidden" /> --}}
            {{-- <img src="/images/error/404-dark.svg" alt="404" class="hidden dark:block" />
           --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="472" height="158" viewBox="0 0 472 158" fill="none">
                <rect x="203.103" y="41.7015" width="22.1453" height="20.7141" rx="2.63433" fill="#097AEC"
                    stroke="#097AEC" stroke-width="0.752667" />
                <rect x="246.752" y="41.7015" width="22.1453" height="20.7141" rx="2.63433" fill="#097AEC"
                    stroke="#097AEC" stroke-width="0.752667" />
                <rect x="258.201" y="98.2303" width="22.1453" height="20.7141" rx="2.63433" fill="#097AEC"
                    stroke="#097AEC" stroke-width="0.752667" />
                <rect x="191.654" y="98.2303" width="22.1453" height="20.7141" rx="2.63433" fill="#097AEC"
                    stroke="#097AEC" stroke-width="0.752667" />
                <rect x="207.396" y="82.847" width="57.5655" height="20.7141" rx="2.63433" fill="#097AEC" stroke="#097AEC"
                    stroke-width="0.752667" />
                <rect x="152.769" y="15.167" width="166.462" height="130.311" rx="28" stroke="#097AEC"
                    stroke-width="24" />
                <rect x="0.0405273" y="0.522461" width="32.6255" height="77.5957" rx="6.26271" fill="#097AEC" />
                <rect x="0.0405273" y="0.522461" width="32.6255" height="77.5957" rx="6.26271" stroke="#097AEC" />
                <rect x="75.8726" y="3.16748" width="32.6255" height="154.31" rx="6.26271" fill="#097AEC" />
                <rect x="75.8726" y="3.16748" width="32.6255" height="154.31" rx="6.26271" stroke="#097AEC" />
                <rect x="16.7939" y="91.3442" width="32.6255" height="77.5957" rx="6.26271"
                    transform="rotate(-90 16.7939 91.3442)" fill="#097AEC" />
                <rect x="16.7939" y="91.3442" width="32.6255" height="77.5957" rx="6.26271"
                    transform="rotate(-90 16.7939 91.3442)" stroke="#097AEC" />
                <rect x="363.502" y="0.522461" width="32.6255" height="77.5957" rx="6.26271" fill="#097AEC" />
                <rect x="363.502" y="0.522461" width="32.6255" height="77.5957" rx="6.26271" stroke="#097AEC" />
                <rect x="439.334" y="3.16748" width="32.6255" height="154.31" rx="6.26271" fill="#097AEC" />
                <rect x="439.334" y="3.16748" width="32.6255" height="154.31" rx="6.26271" stroke="#097AEC" />
                <rect x="380.255" y="91.3442" width="32.6255" height="77.5957" rx="6.26271"
                    transform="rotate(-90 380.255 91.3442)" fill="#097AEC" />
                <rect x="380.255" y="91.3442" width="32.6255" height="77.5957" rx="6.26271"
                    transform="rotate(-90 380.255 91.3442)" stroke="#097AEC" />
            </svg>

            <p class="mt-10 mb-6 text-base text-gray-700 dark:text-gray-400 sm:text-lg">
                {{ __('errors.page_not_found_message') }}
            </p>

            <a href="/"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                {{ __('errors.back_to_home') }}
            </a>
        </div>
    </div>
@endsection
