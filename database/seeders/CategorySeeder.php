<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaji', 'type' => 'income'],
            ['name' => 'Bonus', 'type' => 'income'],
            ['name' => 'Investasi', 'type' => 'income'],
            ['name' => 'Makanan', 'type' => 'expense'],
            ['name' => 'Transport', 'type' => 'expense'],
            ['name' => 'Belanja', 'type' => 'expense'],
            ['name' => 'Hiburan', 'type' => 'expense'],
            ['name' => 'Tagihan', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
