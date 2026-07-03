<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Transaction::with(['category', 'account', 'toAccount'])
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')       
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.report.report', compact('reports'));
    }

    public function print(Request $request)
    {
        try {

            $query = Transaction::with([
                    'category',
                    'fromAccount',
                    'toAccount'
                ])
                ->where('user_id', auth()->id());

            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->filter === 'day' && $request->filled('date')) {

                $query->whereDate('date', Carbon::parse($request->date));

            } elseif ($request->filter === 'month' && $request->filled('month')) {

                [$year, $month] = explode('-', $request->month);

                $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
            }

            $reports = $query
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalIncome = $reports
                ->where('type', 'income')
                ->sum('amount');

            $totalExpense = $reports
                ->where('type', 'expense')
                ->sum('amount');

            $totalTransfer = $reports
                ->where('type', 'transfer')
                ->sum('amount');

            $pdf = Pdf::loadView(
                'components.report.pdf',
                [
                    'reports' => $reports,
                    'filter' => $request->filter,
                    'type' => $request->type,
                    'selectedDate' => $request->date,
                    'selectedMonth' => $request->month,
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                    'totalTransfer' => $totalTransfer,
                ]
            )->setPaper('a4', 'landscape');

            $firstName = explode(' ', auth()->user()->name)[0];
            $type = ucfirst($request->type ?? 'All');
            if ($request->filter === 'day' && $request->filled('date')) {
                $period = Carbon::parse($request->date)->format('Y-m-d');
            } elseif ($request->filter === 'month' && $request->filled('month')) {
                $period = Carbon::parse($request->month . '-01')->format('F_Y');
            } else {
                $period = now()->format('Y-m-d');
            }
            $fileName = "Budgetin_{$firstName}_{$type}_Report_{$period}.pdf";
            return $pdf->download($fileName);

        } catch (\Throwable $th) {
            dd($th->getMessage());
            report($th);

            toast()->error('Failed to generate report.');

            return redirect()->back();
        }
    }
}