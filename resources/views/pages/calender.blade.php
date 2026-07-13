@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('nav.calender') }}" />
    <x-calender-area />
@endsection
