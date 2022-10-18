<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetDetailRequest;
use App\Models\Budget;
use App\Models\BudgetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BudgetDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $budget = Budget::find($id);

        return view('budget-details', compact('budget'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetDetailRequest $request, BudgetDetail $detail)
    {
        $record = $detail::create($request->all());
        $created = $record != null || $record != false ? true : false;

        if($created){
            Budget::where('id', $request->budget_id)->increment('total_amount', $request->amount);
        }

        return response()->json([
            'success' => $created,
            'message' => $created ? 'Successfully created budget detail.' : 'Failed to create budget detail'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetDetailRequest $request, BudgetDetail $detail)
    {
        $record = $detail::find($request->id);
        $record->description = $request->description;
        $record->amount = $request->amount;
        $updated = $record->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated budget detail.' : 'Failed to update budget detail.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetDetail $budget_detail)
    {
        Budget::where('id', $budget_detail->budget_id)->decrement('total_amount', $budget_detail->amount);

        $budget_detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted budget detail.'
        ]);
    }

    public function list($budget_id){
        $details = BudgetDetail::select('*')
            ->where('budget_id', $budget_id)
            ->get();

        return DataTables::of($details)
            ->addColumn('action', function($detail){
                $buttons = "<button type='button' class='btn btn-md btn-warning btn-edit' value='$detail->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-md btn-danger btn-delete' value='$detail->id'>Delete</button>";
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function get($id){
        return BudgetDetail::find($id);
    }
}
