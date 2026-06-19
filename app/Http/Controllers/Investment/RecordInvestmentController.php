<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Investment;
use App\Models\RecordInvestment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordInvestmentController extends Controller
{
    public function store(Request $request){
        $validated = $request->validateWithBag('record_investment', [
            'investment_id' => ['required'],
            'goal_id' => ['required'],
            'account_id' => ['required'],
            'date' => ['required'],
            'transaction_amount' => ['required'],
            'description' => ['nullable', 'string', 'max:200'],
        ]);

        $goal = Goal::with('investments.records')->findOrFail($validated['goal_id']);

        $investment = Investment::with('records')
            ->where('id', $validated['investment_id'])
            ->where('goal_id', $validated['goal_id'])
            ->firstOrFail();

        $newAmount = (float) $validated['transaction_amount'];

        $investmentCurrent = $investment->records->sum('transaction_amount');
        $investmentRemaining = $investment->planned_amount - $investmentCurrent;

        if ($newAmount > $investmentRemaining) {
            toast()->error(
                'Exceeds investment limit. Remaining for this investment: ' . number_format($investmentRemaining, 0, ',', '.')
            );

            return back()
                ->withErrors([
                    'transaction_amount' =>
                        'Exceeds investment limit. Remaining: ' . number_format($investmentRemaining, 0, ',', '.')
                ], 'record_investment')
                ->withInput();
        }

        $totalCurrentGoal = $goal->investments
            ->flatMap->records
            ->sum('transaction_amount');
        $goalRemaining = $goal->target_amount - $totalCurrentGoal;
        if ($newAmount > $goalRemaining) {
            toast()->error('Exceeds goal remaining budget: ' . number_format($goalRemaining, 0, ',', '.'));

            return back()
                ->withErrors([
                    'transaction_amount' =>
                        'Exceeds goal remaining budget: ' . number_format($goalRemaining, 0, ',', '.')
                ], 'record_investment')
                ->withInput();
        }

        RecordInvestment::create([
            'investment_id' => $validated['investment_id'],
            'goal_id' => $validated['goal_id'],
            'account_id' => $validated['account_id'],
            'date' => Carbon::createFromFormat('d-m-Y', $validated['date'])->format('Y-m-d'),
            'transaction_amount' => $validated['transaction_amount'],
            'description' => $validated['description'],
        ]);

        toast()->success('Record Investment created!');
        return redirect()->back()->with('success', 'Record Investment created!');
    }
}
