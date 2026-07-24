<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 110px 30px 65px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #374151;
            font-size: 11px;
            line-height: 1.5;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            height: 70px;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 10px;
            color: #6B7280;
        }

        .page-number:after {
            content: counter(page);
        }

        .page-total:after {
            content: counter(pages);
        }

        .watermark {
            position: fixed;
            top: 38%;
            left: 18%;
            font-size: 85px;
            color: #EEF2FF;
            transform: rotate(-35deg);
            z-index: -1000;
            font-weight: bold;
        }

        .layout-table {
            width: 100%;
            border-collapse: collapse;
        }

        .layout-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .summary-box {
            padding: 12px;
            border-radius: 6px;
        }

        .summary-box b {
            font-size: 11px;
        }

        .summary-box .value {
            margin-top: 6px;
            font-size: 14px;
            font-weight: bold;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table thead {
            display: table-header-group;
        }

        .report-table tr {
            page-break-inside: avoid;
        }

        .report-table th {
            background: #6C99FA;
            color: white;
            border: 1px solid #D1D5DB;
            padding: 10px 6px;
            text-align: center;
            font-size: 11px;
        }

        .report-table td {
            border: 1px solid #E5E7EB;
            padding: 8px 6px;
            font-size: 10px;
            word-wrap: break-word;
            vertical-align: middle;
        }

        .report-table tbody tr:nth-child(even) {
            background: #F8FAFC;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            background: #EFF6FF;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #6B7280;
        }
    </style>

</head>

<body>

@php(\Carbon\Carbon::setLocale(app()->getLocale()))

<div class="watermark">BUDGETIN</div>

<header>

    <table class="layout-table">
        <tr>

            <td width="70">
                <img src="{{ public_path('images/logo/logo-icon.png') }}" width="48">
            </td>

            <td>
                <div style="font-size:24px;font-weight:bold;color:#2563EB;">
                    Budgetin
                </div>

                <div style="font-size:12px;color:#6B7280;">
                    {{ __('sentence.investment_history_report') }}
                </div>
            </td>

            <td align="right">
                <strong>{{ __('common.generated') }} :</strong><br>
                {{ now()->translatedFormat('d F Y H:i') }}
            </td>

        </tr>
    </table>

    <hr style="border:none;border-top:2px solid #6C99FA;margin-top:12px;">

</header>

<footer>

    <table class="layout-table" style="border-top:1px solid #E5E7EB;padding-top:8px;">
        <tr>
            <td>{{ __('sentence.generated_by_budgetin') }}</td>

            <td align="center">
                <strong style="color:#9CA3AF;">{{ __('common.confidential') }}</strong>
            </td>

            <td align="right">
                {{ __('common.page') }} <span class="page-number"></span> {{ __('common.of') }}
                <span class="page-total"></span>
            </td>
        </tr>
    </table>

</footer>

<table class="layout-table" style="margin-bottom:20px;">

    <tr>

        <td width="35%">
            <strong>{{ __('common.filter') }} :</strong>
            {{ $filter == 'day' ? __('common.day') : __('common.month') }}
        </td>

        <td width="65%">
            <strong>{{ __('common.period') }} :</strong>

            @if($filter=="day")
                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
            @else
                {{ \Carbon\Carbon::parse($selectedMonth.'-01')->translatedFormat('F Y') }}
            @endif

        </td>

    </tr>

</table>

<table class="layout-table" style="margin-bottom:25px;">

    <tr>

        <td width="24%">
            <div class="summary-box" style="background:#DBEAFE;">
                <b>{{ __('sentence.total_investment') }}</b>

                <div class="value" style="color:#2563EB;">
                    {{ __('common.idr') }} {{ number_format($totalInvestment,0,',','.') }}
                </div>
            </div>
        </td>

        <td width="1%"></td>

        <td width="24%">
            <div class="summary-box" style="background:#DCFCE7;">
                <b>{{ __('common.total') }} {{ __('common.record') }}</b>

                <div class="value" style="color:#16A34A;">
                    {{ $records->count() }}
                </div>
            </div>
        </td>

        <td width="1%"></td>

        <td width="24%">
            <div class="summary-box" style="background:#FEF3C7;">
                <b>{{ __('common.total') }} {{ __('common.goals') }}</b>

                <div class="value" style="color:#D97706;">
                    {{ $records->pluck('goal_id')->unique()->count() }}
                </div>
            </div>
        </td>

        <td width="1%"></td>

        <td width="24%">
            <div class="summary-box" style="background:#F3F4F6;">
                <b>{{ __('common.total') }} {{ __('common.account') }}</b>

                <div class="value" style="color:#4B5563;">
                    {{ $records->pluck('account_id')->unique()->count() }}
                </div>
            </div>
        </td>

    </tr>

</table>

<table class="report-table">

    <thead>

    <tr>

        <th width="4%">{{ __('common.no') }}</th>
        <th width="10%">{{ __('common.date') }}</th>
        <th width="16%">{{ __('nav.investment') }}</th>
        <th width="16%">{{ __('common.goal') }}</th>
        <th width="14%">{{ __('common.account') }}</th>
        <th width="14%">{{ __('common.amount') }}</th>
        <th width="26%">{{ __('common.description') }}</th>

    </tr>

    </thead>

    <tbody>

    @forelse($records as $index=>$record)

        <tr>

            <td class="text-center">
                {{ $index+1 }}
            </td>

            <td class="text-center">
                {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}
            </td>

            <td>
                {{ $record->investment->name ?? '-' }}
            </td>

            <td>
                {{ $record->goal->name ?? '-' }}
            </td>

            <td>
                {{ $record->account->name ?? '-' }}
            </td>

            <td class="text-right">
                {{ __('common.idr') }} {{ number_format($record->transaction_amount,0,',','.') }}
            </td>

            <td>
                {{ $record->description ?: '-' }}
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="7" class="empty">
                {{ __('sentence.no_investment_history_found') }}
            </td>
        </tr>

    @endforelse

    @if($records->count())

        <tr class="total-row">

            <td colspan="5">
                {{ __('sentence.total_investment') }}
            </td>

            <td class="text-right">
                {{ __('common.idr') }} {{ number_format($totalInvestment,0,',','.') }}
            </td>

            <td class="text-center">
                {{ $records->count() }} {{ __('common.record') }}
            </td>

        </tr>

    @endif

    </tbody>

</table>

</body>
</html>
