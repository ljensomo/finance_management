<?php

namespace App\Http\Controllers;

use App\Models\BudgetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller{

    public function getExpenseSummary(){
        $query = 'SELECT date_expend, SUM(amount) total FROM expenses GROUP BY date_expend';
        $result = DB::select($query);

        $chart_data = [];
        $data = [];

        foreach ($result as $value) {
            $chart_data['labels'][] = $value->date_expend;
            $data['data'][] = $value->total;
        }
        $chart_data['datasets'][] = $data;

        return (Object) $chart_data;
    }
}