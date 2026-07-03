<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Investment;
use App\Models\RecordInvestment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RecordInvestmentController extends Controller
{
    public function store(Request $request)
    {
        try {

            $validated = $request->validateWithBag('record_investment', [
                'investment_id' => ['required', 'integer'],
                'goal_id'       => ['required', 'integer'],
                'account_id'    => ['required', 'integer'],
                'date' => ['required'],
                'transaction_amount' => ['required', 'numeric', 'min:1'],
                'description' => ['nullable', 'string', 'max:200'],
            ]);

            $userId = auth()->id();

            $goal = Goal::with('investments.records')
                ->where('id', $validated['goal_id'])
                ->where('user_id', $userId)
                ->firstOrFail();

            $investment = Investment::with('records')
                ->where('id', $validated['investment_id'])
                ->where('goal_id', $goal->id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $accountExists = \App\Models\Account::where('id', $validated['account_id'])
                ->where('user_id', $userId)
                ->exists();

            if (!$accountExists) {
                toast()->error('Invalid account selected.');

                return back()
                    ->withErrors([
                        'account_id' => 'Invalid account selected.'
                    ], 'record_investment')
                    ->withInput();
            }

            $newAmount = (float) $validated['transaction_amount'];

            $investmentCurrent = $investment->records->sum('transaction_amount');
            $investmentRemaining = $investment->planned_amount - $investmentCurrent;

            if ($newAmount > $investmentRemaining) {

                toast()->error(
                    'Exceeds investment limit. Remaining: ' .
                    number_format($investmentRemaining, 0, ',', '.')
                );

                return back()
                    ->withErrors([
                        'transaction_amount' =>
                            'Exceeds investment limit. Remaining: ' .
                            number_format($investmentRemaining, 0, ',', '.')
                    ], 'record_investment')
                    ->withInput();
            }

            $totalCurrentGoal = $goal->investments
                ->flatMap->records
                ->sum('transaction_amount');

            $goalRemaining = $goal->target_amount - $totalCurrentGoal;

            if ($newAmount > $goalRemaining) {

                toast()->error(
                    'Exceeds goal remaining budget: ' .
                    number_format($goalRemaining, 0, ',', '.')
                );

                return back()
                    ->withErrors([
                        'transaction_amount' =>
                            'Exceeds goal remaining budget: ' .
                            number_format($goalRemaining, 0, ',', '.')
                    ], 'record_investment')
                    ->withInput();
            }

            RecordInvestment::create([
                'investment_id' => $investment->id,
                'goal_id' => $goal->id,
                'account_id' => $validated['account_id'],
                'date' => Carbon::createFromFormat('Y-m-d', $validated['date'])->format('Y-m-d'),
                'transaction_amount' => $validated['transaction_amount'],
                'description' => $validated['description'],
            ]);

            toast()->success('Record investment created!');

            return redirect()->back();

        } catch (\Throwable $th) {

            report($th);

            toast()->error(
                app()->environment('local')
                    ? $th->getMessage()
                    : 'Failed to create investment record.'
            );

            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function print(Request $request)
    {
        try {
            $query = RecordInvestment::with([
                'investment',
                'goal',
                'account',
            ])
            ->whereHas('investment', function ($query) {
                $query->where('user_id', auth()->id());
            });

            if ($request->filter === 'day' && $request->filled('date')) {
                $query->whereDate(
                    'date',
                    Carbon::parse($request->date)
                );

            } elseif ($request->filter === 'month' && $request->filled('month')) {
                $date = Carbon::parse($request->month);
                $query->whereYear('date', $date->year)
                    ->whereMonth('date', $date->month);
            }

            $records = $query
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalInvestment = $records->sum('transaction_amount');
            $pdf = Pdf::loadView(
                'components.investment.history.pdf',
                [
                    'records' => $records,
                    'filter' => $request->filter,
                    'selectedDate' => $request->date,
                    'selectedMonth' => $request->month,
                    'totalInvestment' => $totalInvestment,
                ]
            )->setPaper('a4', 'landscape');

            $firstName = explode(' ', auth()->user()->name)[0];
            if ($request->filter === 'day' && $request->filled('date')) {
                $period = Carbon::parse($request->date)
                    ->format('Y-m-d');

            } elseif ($request->filter === 'month' && $request->filled('month')) {
                $period = Carbon::parse($request->month)
                    ->format('F_Y');
            } else {
                $period = now()->format('Y-m-d');
            }

            $fileName = "Budgetin_{$firstName}_Investment_History_{$period}.pdf";
            return $pdf->download($fileName);

        } catch (\Throwable $th) {
            report($th);
            toast()->error('Failed to generate investment history.');
            return redirect()->back();
        }
    }
}