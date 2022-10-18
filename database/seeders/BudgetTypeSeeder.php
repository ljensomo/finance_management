<?php

namespace Database\Seeders;

use App\Models\BudgetType;
use Illuminate\Database\Seeder;

class BudgetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetType::insert([
            ['name' => 'Daily'],
            ['name' => 'Weekly'],
            ['name' => 'Monthly'],
            ['name' => 'Custom'],
        ]);
    }
}
