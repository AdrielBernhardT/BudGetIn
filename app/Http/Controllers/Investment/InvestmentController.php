<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $targets = collect($this->getTargets())->map(function ($target) {

            $items = collect($target->items);

            $target->total_current = $items->sum('current_amount');

            $target->percentage = $target->target_amount > 0
                ? round(($target->total_current / $target->target_amount) * 100, 0)
                : 0;

            $target->items = $items->map(function ($item) use ($target) {

                $item->target_amount = round(
                    $target->target_amount * $item->allocation
                );

                $item->percentage = $item->target_amount > 0
                    ? round(($item->current_amount / $item->target_amount) * 100, 0)
                    : 0;

                $item->allocation_percentage = round($item->allocation * 100);

                return $item;
            });

            return $target;
        });

        $total_target = $targets->sum('target_amount');
        $total_investment = $targets->sum('total_current');
        $allocation_chart = $targets->map(function ($target) {
            return [
                'label' => $target->title,
                'value' => (float) $target->target_amount,
            ];
        })->values();

        $percentage = $total_target > 0
            ? round(($total_investment / $total_target) * 100, 0)
            : 0;

        $remaining_target = $total_target - $total_investment;

        $datas = [
            'summary' => [
                'total_target' => $total_target,
                'total_investment' => $total_investment,
                'remaining_target' => $remaining_target,
                'percentage' => $percentage,
            ],
            'targets' => $targets,
            'allocation_chart' => $allocation_chart,
        ];
        $goals = Goal::where('user_id', Auth::id())->get();
        $investments = Investment::where('user_id', Auth::id())->get();
        $accounts = Account::where('user_id', Auth::id())->get();

        return view('pages.investment.investment', ['title' => 'Investment'], compact('datas', 'goals', 'investments', 'accounts'));
    }

    public function getTargets()
    {
        $goals = Goal::with(['investments.records'])->where('user_id', Auth::id())->get();
        
        return $goals->map(function($goal){
            return (object) [
                'title' => $goal->name,
                'icon' => $goal->icon,
                'target_amount' => $goal->target_amount,
                'items' => $goal->investments->map(function($investment){
                    return (object)[
                        'title' => $investment->name,
                        'allocation' => $investment->allocation_percent/100,
                        'current_amount' => $investment->records->sum('transaction_amount')
                    ];
                }),
            ];
        });
    }

    public function store(Request $request){
        $validated = $request->validateWithBag('investment', [
            'name' => ['required'],
            'goal_id' => ['required'],
            'allocation_percent' => ['required'],
            'planned_amount' => ['required']
        ]);

        $user = Auth::user();
        Investment::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'goal_id' => $validated['goal_id'],
            'allocation_percent' => $validated['allocation_percent'],
            'planned_amount' => $validated['planned_amount'],
        ]);

        toast()->success('Investment created!');
        return redirect()->back()->with('success', 'Investment created!');
    }
}
