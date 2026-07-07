<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Account::insert([
                [
                    'user_id' => $user->id,
                    'account_identifier' => '3790933400',
                    'name' => 'BCA',
                    'balance' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $user->id,
                    'account_identifier' => '3790933400',
                    'name' => 'BRI',
                    'balance' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $user->id,
                    'account_identifier' => '3790933400',
                    'name' => 'BLU',
                    'balance' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $user->id,
                    'name' => 'Cash',
                    'account_identifier' => NULL,
                    'balance' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
