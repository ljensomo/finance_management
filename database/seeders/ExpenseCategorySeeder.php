<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reference https://localfirstbank.com/article/budgeting-101-personal-budget-categories/
        ExpenseCategory::insert([
            ['name' => 'Housing'],
            ['name' => 'Transportation'],
            ['name' => 'Food'],
            ['name' => 'Utilities'],
            ['name' => 'Clothing'],
            ['name' => 'Medical/Healthcare'],
            ['name' => 'Insurance'],
            ['name' => 'Household Items/Supplies'],
            ['name' => 'Personal'],
            ['name' => 'Debt'],
            ['name' => 'Retirement'],
            ['name' => 'Education'],
            ['name' => 'Savings'],
            ['name' => 'Gifts/Donations'],
            ['name' => 'Entertainment'],
        ]);
    }
}
