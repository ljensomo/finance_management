<?php

namespace App\Http\Controllers;

use App\Models\IncomeType;

class IncomeTypeController extends Controller
{
    public function list(){

        $types = IncomeType::all();

        return response()->json($types);
    }
}
