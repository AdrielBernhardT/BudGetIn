<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'overview' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
                'series' => [
                    [
                        'name' => 'Income',
                        'data' => [5000000, 7000000, 6500000, 8000000],
                    ],
                    [
                        'name' => 'Expense',
                        'data' => [3000000, 4000000, 3500000, 4500000],
                    ],
                ],
            ],
            'income' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
                'series' => [
                    [
                        'name' => 'Income',
                        'data' => [5000000, 7000000, 6500000, 8000000],
                    ],
                ],
            ],
            'expense' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
                'series' => [
                    [
                        'name' => 'Expense',
                        'data' => [3000000, 4000000, 3500000, 4500000],
                    ],
                ],
            ],
        ];

        $incomes = [
            [
                'title' => 'uang masuk',
                'amount' => 50000,
                'account_bank' => 'BCA'
            ],
            [
                'title' => 'uang masuk',
                'amount' => 50000,
                'account_bank' => 'BCA'
            ],
            [
                'title' => 'uang masuk',
                'amount' => 50000,
                'account_bank' => 'BCA'
            ],
            [
                'title' => 'uang masuk',
                'amount' => 50000,
                'account_bank' => 'BCA'
            ],
            [
                'title' => 'uang masuk',
                'amount' => 50000,
                'account_bank' => 'BCA'
            ],
        ];

        return view('pages.dashboard.dashboard', compact('statistics', 'incomes'));
    }
}
