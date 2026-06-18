<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\Investment;
use App\Models\RecordInvestment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ambil user pertama
        $user = User::first();

        if (!$user) {
            return;
        }

        $emergency = Goal::create([
            'user_id' => $user->id,
            'name' => 'Emergency Fund',
            'icon' => 'shield',
            'target_amount' => 25000000,
        ]);

        $cash = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $emergency->id,
            'name' => 'Cash Savings',
            'allocation_percent' => 40,
            'planned_amount' => 10000000,
        ]);

        $moneyMarket = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $emergency->id,
            'name' => 'Money Market Fund',
            'allocation_percent' => 60,
            'planned_amount' => 15000000,
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $cash->id,
                'goal_id' => $emergency->id,
                'date' => now(),
                'transaction_amount' => 2000000,
                'description' => 'Initial emergency savings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'investment_id' => $cash->id,
                'goal_id' => $emergency->id,
                'date' => now(),
                'transaction_amount' => 1500000,
                'description' => 'Monthly savings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'investment_id' => $cash->id,
                'goal_id' => $emergency->id,
                'date' => now(),
                'transaction_amount' => 500000,
                'description' => 'Extra income',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $moneyMarket->id,
                'goal_id' => $emergency->id,
                'date' => now(),
                'transaction_amount' => 3000000,
                'description' => 'Money market deposit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'investment_id' => $moneyMarket->id,
                'goal_id' => $emergency->id,
                'date' => now(),
                'transaction_amount' => 2000000,
                'description' => 'Additional deposit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        $laptop = Goal::create([
            'user_id' => $user->id,
            'name' => 'Buy a Laptop',
            'icon' => 'laptop',
            'target_amount' => 18000000,
        ]);

        $blu = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $laptop->id,
            'name' => 'Blu Savings',
            'allocation_percent' => 50,
            'planned_amount' => 9000000,
        ]);

        $bitcoin = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $laptop->id,
            'name' => 'Bitcoin',
            'allocation_percent' => 50,
            'planned_amount' => 9000000,
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $blu->id,
                'goal_id' => $laptop->id,
                'date' => now(),
                'transaction_amount' => 2000000,
                'description' => 'Savings deposit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'investment_id' => $blu->id,
                'goal_id' => $laptop->id,
                'date' => now(),
                'transaction_amount' => 1000000,
                'description' => 'Freelance income',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $bitcoin->id,
                'goal_id' => $laptop->id,
                'date' => now(),
                'transaction_amount' => 4500000,
                'description' => 'Crypto investment growth',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $japan = Goal::create([
            'user_id' => $user->id,
            'name' => 'Vacation Japan',
            'icon' => 'plane',
            'target_amount' => 30000000,
        ]);

        $usd = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $japan->id,
            'name' => 'USD Savings',
            'allocation_percent' => 70,
            'planned_amount' => 21000000,
        ]);

        $gold = Investment::create([
            'user_id' => $user->id,
            'goal_id' => $japan->id,
            'name' => 'Gold Investment',
            'allocation_percent' => 30,
            'planned_amount' => 9000000,
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $usd->id,
                'goal_id' => $japan->id,
                'date' => now(),
                'transaction_amount' => 5000000,
                'description' => 'USD exchange savings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'investment_id' => $usd->id,
                'goal_id' => $japan->id,
                'date' => now(),
                'transaction_amount' => 2500000,
                'description' => 'Additional USD purchase',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        RecordInvestment::insert([
            [
                'investment_id' => $gold->id,
                'goal_id' => $japan->id,
                'date' => now(),
                'transaction_amount' => 1500000,
                'description' => 'Gold investment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
