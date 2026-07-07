<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary
        $userId = Auth::id();

        $accounts = Account::where('user_id', $userId)
            ->orderByDesc('balance')
            ->get();

        $currentBalance = $accounts->sum('balance');

        $summary = [
            'current_balance' => $currentBalance,
            'accounts' => $accounts,
        ];

        // Metrics
        $now = Carbon::now();
        $currentMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $lastMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->sum('amount');

        $currentMonthExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $lastMonthExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->sum('amount');

        $currentSaving = $currentMonthIncome - $currentMonthExpense;
        $lastSaving = $lastMonthIncome - $lastMonthExpense;

        $currentHighest = Transaction::select(
                'category_id',
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->where('type','expense')
            ->whereMonth('date',$now->month)
            ->whereYear('date',$now->year)
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->first();

        $lastHighest = Transaction::select(
                'category_id',
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->where('type','expense')
            ->whereMonth('date',$now->copy()->subMonth()->month)
            ->whereYear('date',$now->copy()->subMonth()->year)
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->first();

        $metrics = [
            'income' => [
                'amount' => $currentMonthIncome,
                'change' => $this->calculateChange(
                    $currentMonthIncome,
                    $lastMonthIncome
                ),
            ],

            'expense' => [
                'amount' => $currentMonthExpense,
                'change' => $this->calculateChange(
                    $currentMonthExpense,
                    $lastMonthExpense
                ),
            ],

            'saving' => [
                'amount' => $currentSaving,
                'change' => $this->calculateChange(
                    $currentSaving,
                    $lastSaving
                ),
            ],

            'highest_expense' => [
                'title' => optional($currentHighest?->category)->name ?? '-',
                'amount' => $currentHighest->total ?? 0,
                'change' => $this->calculateChange(
                    $currentHighest->total ?? 0,
                    $lastHighest->total ?? 0
                ),
            ],
        ];

        // Alert
        $budgetAlert = null;
        $totalCategory = Category::where('user_id', $userId)->count();
        if ($totalCategory == 0) {
            $budgetAlert = [
                'show' => false,
                'type' => 'info',
                'title' => 'Budget Alert',
                'message' => 'Create your expense categories and monthly budgets to start tracking your spending.',
            ];

        } else {
            $categoryAlert = Category::where('user_id', $userId)
                ->where('monthly_budget', '>', 0)
                ->withSum([
                    'transaction as expense_this_month' => function ($query) use ($now, $userId) {
                        $query->where('user_id', $userId)
                            ->where('type', 'expense')
                            ->whereMonth('date', $now->month)
                            ->whereYear('date', $now->year);
                    }
                ], 'amount')
                ->get()
                ->map(function ($category) {
                    $expense = $category->expense_this_month ?? 0;
                    $category->remaining = $category->monthly_budget - $expense;
                    return $category;
                })
                ->sortBy('remaining')
                ->first();

            if (!$categoryAlert) {

                $budgetAlert = [
                    'show' => false,
                    'type' => 'info',
                    'title' => 'Budget Alert',
                    'message' => 'Set a monthly budget for your categories to receive budget alerts.',
                ];

            } else {
                $remaining = max(0, $categoryAlert->remaining);
                $budgetAlert = [
                    'show' => true,
                    'title' => 'Budget Alert',
                    'type' => $remaining <= 0 ? 'danger' : 'warning',
                    'category' => $categoryAlert->name,
                    'remaining' => $remaining,
                    'message' => $remaining <= 0
                        ? "Your {$categoryAlert->name} budget has been exceeded."
                        : sprintf(
                            "Only IDR %s left from your %s budget.",
                            number_format($remaining, 0, ',', '.'),
                            $categoryAlert->name
                        ),
                ];
            }
        }

        // Statistics
        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];

        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $firstIncomeOrExpense = Transaction::where('user_id', $userId)
            ->whereIn('type', ['income', 'expense'])
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->first();

        if ($firstIncomeOrExpense) {
            $start = Carbon::parse($firstIncomeOrExpense->date)->month;
            for ($i = $start; $i <= $month; $i++) {
                $date = Carbon::create($year, $month, 1);

                $labels[] = $date->format('M');

                $incomeSeries[] = Transaction::where('user_id', $userId)
                    ->where('type', 'income')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->sum('amount');

                $expenseSeries[] = Transaction::where('user_id', $userId)
                    ->where('type', 'expense')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->sum('amount');
            }
        }

        $statistics = [
            'overview' => [
                'labels' => $labels,
                'series' => [
                    [
                        'name' => 'Income',
                        'data' => $incomeSeries,
                    ],
                    [
                        'name' => 'Expense',
                        'data' => $expenseSeries,
                    ],
                ],
            ],

            'income' => [
                'labels' => $labels,
                'series' => [
                    [
                        'name' => 'Income',
                        'data' => $incomeSeries,
                    ],
                ],
            ],

            'expense' => [
                'labels' => $labels,
                'series' => [
                    [
                        'name' => 'Expense',
                        'data' => $expenseSeries,
                    ],
                ],
            ],
        ];

        // Recent Transactions
        $recentTransactions = [
            'incomes' => Transaction::with(['account', 'category'])
                ->where('user_id', $userId)
                ->where('type', 'income')
                ->latest('date')
                ->take(5)
                ->get(),

            'expenses' => Transaction::with(['account', 'category'])
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->latest('date')
                ->take(5)
                ->get(),

            'transfers' => Transaction::with(['account'])
                ->where('user_id', $userId)
                ->where('type', 'transfer')
                ->latest('date')
                ->take(5)
                ->get(),
        ];

        // Monthly Budget
        $monthlyBudgets =Category::where('user_id', $userId)
            ->where('monthly_budget', '>', 0)
            ->withSum([
                'transaction as expense_this_month' => function ($query) use ($now, $userId) {
                    $query->where('user_id', $userId)
                        ->where('type', 'expense')
                        ->whereMonth('date', $now->month)
                        ->whereYear('date', $now->year);
                }
            ], 'amount')
            ->get()
            ->map(function ($category) {

                $spent = $category->expense_this_month ?? 0;

                $percentage = $category->monthly_budget > 0
                    ? round(($spent / $category->monthly_budget) * 100)
                    : 0;

                return [
                    'category' => $category->name,
                    'budget' => $category->monthly_budget,
                    'spent' => $spent,
                    'percentage' => min($percentage, 100),
                ];
            });

        return view('pages.dashboard.dashboard', compact(
            'summary',
            'metrics',
            'budgetAlert',
            'firstIncomeOrExpense',
            'statistics', 
            'recentTransactions',
            'monthlyBudgets'
        ));
    }

    private function calculateChange(float $current, float $previous): array
    {
        if ($previous == 0) {
            return [
                'direction' => 'neutral',
                'percentage' => 0,
            ];
        }

        $percentage = round((($current - $previous) / $previous) * 100, 2);

        if ($percentage > 0) {
            $direction = 'up';
        } elseif ($percentage < 0) {
            $direction = 'down';
        } else {
            $direction = 'neutral';
        }

        return [
            'direction' => $direction,
            'percentage' => abs($percentage),
        ];
    }
}
