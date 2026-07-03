<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Fance Satria',
                'fname' => 'Fance',       
                'lname' => 'Satria',      
                'password' => bcrypt('password123'),
            ]
        );

        $accountBank = Account::create([
            'user_id' => $user->id,
            'name' => 'Bank BCA',
            'balance' => 15000000,
        ]);

        $accountCash = Account::create([
            'user_id' => $user->id,
            'name' => 'Dompet Tunai',
            'balance' => 500000,
        ]);

        $catFood = Category::create([
            'user_id' => $user->id,
            'name' => 'Food & Beverage',
            'slug' => Str::slug('Food & Beverage'),
            'icon' => 'home', 
            'monthly_budget' => 2000000,
        ]);

        $catSalary = Category::create([
            'user_id' => $user->id,
            'name' => 'Monthly Salary',
            'slug' => Str::slug('Monthly Salary'),
            'icon' => 'home',
            'monthly_budget' => 0,
        ]);

        $today = Carbon::now();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'income',
            'from_account_id' => null,
            'to_account_id' => $accountBank->id,
            'category_id' => $catSalary->id,
            'title' => 'Gaji Bulan ' . $today->format('F'),
            'amount' => 10000000,
            'date' => $today->format('Y-m-d'),
            'description' => 'Gaji bulanan dari kantor',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'expense',
            'from_account_id' => $accountBank->id,
            'to_account_id' => null,
            'category_id' => $catFood->id,
            'title' => 'Makan Siang Bareng Tim',
            'amount' => 150000,
            'date' => $today->format('Y-m-d'),
            'description' => 'Makan di restoran deket kantor',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'transfer',
            'from_account_id' => $accountBank->id,
            'to_account_id' => $accountCash->id,
            'category_id' => null, 
            'title' => 'Tarik Tunai ATM',
            'amount' => 500000,
            'date' => $today->format('Y-m-d'),
            'description' => 'Persiapan uang cash',
        ]);

        for ($i = 1; $i <= 25; $i++) {
            $randomDate = $today->copy()->subDays(rand(1, 60))->format('Y-m-d');
            $isincome = rand(1, 4) === 1;

            Transaction::create([
                'user_id' => $user->id,
                'type' => $isincome ? 'income' : 'expense',
                'from_account_id' => $isincome ? null : (rand(0, 1) ? $accountBank->id : $accountCash->id),
                'to_account_id' => $isincome ? $accountBank->id : null,
                'category_id' => $isincome ? $catSalary->id : $catFood->id,
                'title' => $isincome ? 'Bonus / Pemasukan Sampingan ' . $i : 'Pengeluaran Makan ' . $i,
                'amount' => $isincome ? rand(500, 2000) * 1000 : rand(15, 100) * 1000,
                'date' => $randomDate,
                'description' => 'Deskripsi dummy otomatis untuk transaksi ke-' . $i,
            ]);
        }
    }
}
