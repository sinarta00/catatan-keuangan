<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $incomeCategories = Category::where('type', 'income')->pluck('id')->toArray();
        $expenseCategories = Category::where('type', 'expense')->pluck('id')->toArray();

        // Buat 50 transaksi dummy
        for ($i = 0; $i < 50; $i++) {
            $isIncome = rand(0, 1);
            
            Transaction::create([
                'category_id' => $isIncome 
                    ? $incomeCategories[array_rand($incomeCategories)]
                    : $expenseCategories[array_rand($expenseCategories)],
                'amount' => rand(10000, 5000000),
                'description' => 'Transaksi ' . ($i + 1),
                'transaction_date' => now()->subDays(rand(0, 365)),
            ]);
        }
    }
}
