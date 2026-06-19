<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
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
