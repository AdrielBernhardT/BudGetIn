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

        $currentYearIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->year)
            ->sum('amount');

        $lastYearIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->copy()->subYear()->year)
            ->sum('amount');

        $currentYearExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->year)
            ->sum('amount');

        $lastYearExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->copy()->subYear()->year)
            ->sum('amount');

        $currentSaving = $currentYearIncome - $currentYearExpense;
        $lastSaving = $lastYearIncome - $lastYearExpense;

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

        $currentYear = Carbon::now()->year;

        $monthlyTransactions = Transaction::where('user_id', $userId)
            ->whereYear('date', $currentYear)
            ->whereIn('type', ['income', 'expense'])
            ->selectRaw('MONTH(date) as month, type, SUM(amount) as total')
            ->groupBy('month', 'type')
            ->get();

        if ($monthlyTransactions->isNotEmpty()) {
            $monthsWithData = $monthlyTransactions
                ->pluck('month')
                ->unique()
                ->sort()
                ->values();

            $startMonth = $monthsWithData->first();
            $endMonth = $monthsWithData->last();

            // Kalau data cuma ada di 1 bulan
            if ($monthsWithData->count() === 1) {
                $onlyMonth = $monthsWithData->first();

                if ($onlyMonth == 1) {
                    // Kalau Januari, tambahin Februari 0
                    $startMonth = 1;
                    $endMonth = 2;
                } elseif ($onlyMonth == 12) {
                    // Kalau Desember, tambahin November 0
                    $startMonth = 11;
                    $endMonth = 12;
                } else {
                    // Kalau di tengah, tambahin bulan sebelum dan sesudah
                    $startMonth = $onlyMonth - 1;
                    $endMonth = $onlyMonth + 1;
                }
            }

            $incomeByMonth = $monthlyTransactions
                ->where('type', 'income')
                ->pluck('total', 'month');

            $expenseByMonth = $monthlyTransactions
                ->where('type', 'expense')
                ->pluck('total', 'month');

            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $labels[] = Carbon::create($currentYear, $month, 1)->format('M');

                $incomeSeries[] = (int) ($incomeByMonth[$month] ?? 0);
                $expenseSeries[] = (int) ($expenseByMonth[$month] ?? 0);
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

        $hasStatistics = collect($statistics['overview']['series'] ?? [])
            ->pluck('data')
            ->flatten()
            ->filter(fn ($value) => (float) $value > 0)
            ->isNotEmpty();

        // dd($statistics);

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
            'statistics',
            'hasStatistics',
            'recentTransactions',
            'monthlyBudgets'
        ));
    }

    private function calculateChange(float $current, float $previous): array
    {
        if ($previous == 0) {
            if ($current > 0) {
                return [
                    'direction' => 'up',
                    'percentage' => 100,
                ];
            }

            if ($current < 0) {
                return [
                    'direction' => 'down',
                    'percentage' => 100,
                ];
            }

            return [
                'direction' => 'neutral',
                'percentage' => 0,
            ];
        }

        $percentage = round((($current - $previous) / abs($previous)) * 100, 2);

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