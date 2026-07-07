@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Profile" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        @if (!auth()->user()->email_verified_at)
            <x-profile.verify-card />
        @endif
        <x-profile.profile-card />
        <x-profile.personal-info-card />
        <x-profile.address-card />
    </div>
@endsection
