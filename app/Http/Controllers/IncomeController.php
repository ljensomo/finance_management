<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use App\Http\Controllers\IncomeSourceController;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){       
        return view('income');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncomeRequest $income_request, Income $income)
    {
        $income::create($income_request->all());

        return response()->json([
            'success' => true,
            'message' => 'Successfully added income.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(IncomeRequest $income_request, Income $income)
    {

        $record = $income->find($income_request->id);
        $record->source = $income_request->source;
        $record->description = $income_request->description;
        $record->amount = $income_request->amount;
        $record->income_date = $income_request->income_date;
        $updated = $record->save();

        return response()->json(array(
            'success' => $updated,
            'message' => $updated ? 'Successfully updated income.' : 'Failed to updated income.'.$income_request->amount
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        $deleted = $income->delete();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Income has been deleted.' : 'Failed to delete income.',
        ]);

    }

    public function list(){
        $incomes = Income::select('incomes.id', 'income_description', DB::raw('CONCAT("₱ ",FORMAT(amount,2)) amount'), 'income_date', 'description')
            ->join('income_sources', 'incomes.source', '=', 'income_sources.id')
            ->get();

        return DataTables::of($incomes)
            ->addColumn('action', function($income){
                $buttons = "<button type='button' class='btn btn-md btn-warning btn-edit' value='$income->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-md btn-danger btn-delete' value='$income->id'>Delete</button>";
                return $buttons;
            })
            ->make(true);
    }

    public function get($id){
        return Income::find($id);
    }
}
