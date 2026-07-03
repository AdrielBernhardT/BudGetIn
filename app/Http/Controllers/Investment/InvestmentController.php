<?php

namespace App\Http\Controllers\Investment;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Goal;
use App\Models\Investment;
use App\Models\RecordInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{
    public function index()
    {
        try {

            $targets = collect($this->getTargets())->map(function ($target) {

                $target->items = collect($target->items)->map(function ($item) use ($target) {

                    $item->target_amount = round(
                        $target->target_amount * $item->allocation
                    );

                    $item->percentage = $item->target_amount > 0
                        ? round(($item->current_amount / $item->target_amount) * 100)
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

            $goals = Goal::with('investments')
                ->where('user_id', Auth::id())
                ->get();

            $accounts = Account::where('user_id', Auth::id())->get();

            $histories = RecordInvestment::with([
            'investment.goal',
            'account',
        ])
        ->whereHas('investment', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->latest('date')
        ->get();

            confirmDelete('Are you sure you want to delete this investment?');

            return view(
                'pages.investment.investment',
                [
                    'title' => 'Investment',
                    'datas' => $datas,
                    'goals' => $goals,
                    'accounts' => $accounts,
                    'histories'=>$histories,
                ]
            );

        } catch (\Throwable $th) {

            Log::error('Failed to load investment page.', [
                'user_id' => Auth::id(),
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            report($th);

            toast()->error('Failed to load investment data.');

            return redirect()->back();
        }
    }

    public function getTargets()
    {
        try {

            $goals = Goal::with([
                    'investments' => function ($query) {
                        $query->where('user_id', Auth::id())
                            ->with('records');
                    }
                ])
                ->where('user_id', Auth::id())
                ->get();

            return $goals->map(function ($goal) {

                $items = $goal->investments->map(function ($investment) {

                    return (object) [
                        'id' => $investment->id,
                        'title' => $investment->name,
                        'allocation' => $investment->allocation_percent / 100,
                        'current_amount' => $investment->records->sum('transaction_amount'),
                    ];
                });

                $totalCurrent = $items->sum('current_amount');

                return (object) [
                    'id' => $goal->id,
                    'name' => $goal->name,      // untuk modal edit
                    'title' => $goal->name,     // tetap dipakai chart
                    'icon' => $goal->icon,
                    'target_amount' => $goal->target_amount,
                    'target_date' => $goal->target_date,
                    'days_left' => $goal->daysUntilDeadline(),

                    // tambahkan ini
                    'total_current' => $totalCurrent,
                    'percentage' => $goal->target_amount > 0
                        ? round(($totalCurrent / $goal->target_amount) * 100)
                        : 0,

                    'items' => $items,
                ];
            });

        } catch (\Throwable $th) {

            Log::error('Failed to get investment targets.', [
                'user_id' => Auth::id(),
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            report($th);

            return collect();
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('investment', [
            'name' => [
                'required',
                'string',
                'max:100'
            ],
            'goal_id' => ['required'],
            'allocation_percent' => ['required', 'numeric'],
            'planned_amount' => ['required', 'numeric']
        ]);

        try {

            $user = Auth::user();

            $goal = Goal::with('investments')
                ->where('id', $validated['goal_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $exists = Investment::where('goal_id', $goal->id)
                ->where('user_id', $user->id)
                ->where('name', $validated['name'])
                ->exists();

            if ($exists) {
                toast()->error('Investment name already exists in this goal.');

                return back()
                    ->withErrors([
                        'name' => 'Investment name already exists in this goal.'
                    ], 'investment')
                    ->withInput();
            }

            $totalAllocation = $goal->investments()->sum('allocation_percent');
            $newAllocation = (float) $validated['allocation_percent'];

            if (($totalAllocation + $newAllocation) > 100) {

                $remaining = max(0, 100 - $totalAllocation);

                toast()->error(
                    'Total allocation cannot exceed 100%. Remaining: ' . $remaining . '%'
                );

                return back()
                    ->withErrors([
                        'allocation_percent' =>
                            'Total allocation cannot exceed 100%. Remaining: ' . $remaining . '%'
                    ], 'investment')
                    ->withInput();
            }

            $totalPlanned = $goal->investments()->sum('planned_amount');
            $newPlanned = (float) $validated['planned_amount'];

            if (($totalPlanned + $newPlanned) > $goal->target_amount) {

                toast()->error('Total investment cannot exceed goal target amount.');

                return back()
                    ->withErrors([
                        'planned_amount' =>
                            'Total investment cannot exceed goal target amount.'
                    ], 'investment')
                    ->withInput();
            }

            Investment::create([
                'user_id' => $user->id,
                'goal_id' => $goal->id,
                'name' => $validated['name'],
                'allocation_percent' => $validated['allocation_percent'],
                'planned_amount' => $validated['planned_amount'],
            ]);

            toast()->success('Investment created!');

            return redirect()->back();

        } catch (\Throwable $th) {

            Log::error('Failed to create investment.', [
                'user_id' => Auth::id(),
                'goal_id' => $validated['goal_id'] ?? null,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            report($th);

            toast()->error('Failed to create investment.');

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validateWithBag('investment', [
            'name' => ['required', 'string', 'max:100'],
            'goal_id' => ['required'],
            'allocation_percent' => ['required', 'numeric'],
            'planned_amount' => ['required', 'numeric']
        ]);

        try {

            $user = Auth::user();

            $investment = Investment::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $goal = Goal::with('investments')
                ->where('id', $validated['goal_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $exists = Investment::where('goal_id', $goal->id)
                ->where('user_id', $user->id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $investment->id)
                ->exists();

            if ($exists) {

                toast()->error('Investment name already exists in this goal.');

                return back()
                    ->withErrors([
                        'name' => 'Investment name already exists in this goal.'
                    ], 'investment')
                    ->withInput();
            }

            $totalAllocation = $goal->investments()
                ->where('id', '!=', $investment->id)
                ->sum('allocation_percent');

            $newAllocation = (float) $validated['allocation_percent'];

            if (($totalAllocation + $newAllocation) > 100) {

                $remaining = max(0, 100 - $totalAllocation);

                toast()->error(
                    'Total allocation cannot exceed 100%. Remaining: ' . $remaining . '%'
                );

                return back()
                    ->withErrors([
                        'allocation_percent' =>
                            'Total allocation cannot exceed 100%. Remaining: ' . $remaining . '%'
                    ], 'investment')
                    ->withInput();
            }

            $totalPlanned = $goal->investments()
                ->where('id', '!=', $investment->id)
                ->sum('planned_amount');

            if (($totalPlanned + (float) $validated['planned_amount']) > $goal->target_amount) {

                toast()->error('Total investment exceeds goal target.');

                return back()
                    ->withErrors([
                        'planned_amount' => 'Total investment exceeds goal target.'
                    ], 'investment')
                    ->withInput();
            }

            $investment->update([
                'goal_id' => $goal->id,
                'name' => $validated['name'],
                'allocation_percent' => $validated['allocation_percent'],
                'planned_amount' => $validated['planned_amount'],
            ]);

            toast()->success('Investment updated!');

            return back();

        } catch (\Throwable $th) {

            Log::error('Failed to update investment.', [
                'user_id' => Auth::id(),
                'investment_id' => $id,
                'goal_id' => $validated['goal_id'] ?? null,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            report($th);

            toast()->error('Failed to update investment.');

            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {

            $investment = Investment::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $investment->delete();

            toast()->success('Investment deleted!');

            return redirect()->route('investment.index');

        } catch (\Throwable $th) {

            Log::error('Failed to delete investment.', [
                'user_id' => Auth::id(),
                'investment_id' => $id,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            report($th);

            toast()->error('Failed to delete investment.');

            return back();
        }
    }
}