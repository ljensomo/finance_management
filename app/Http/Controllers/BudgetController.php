<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\BudgetDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('budgets');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetRequest $request, Budget $budget){

        $record = $budget::create($request->all());
        $created = $record != null || $record != false ? true : false;

        return response()->json([
            'success' => $created,
            'message' => $created ? 'Successfully created budget.' : 'Failed to create budget.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetRequest $request, Budget $budget){
        $record = $budget::find($request->id);
        $record->name = $request->name;
        $record->type = $request->type;
        $record->date_from = $request->date_from;
        $record->date_to = $request->date_to;
        $updated = $record->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated budget.' : 'Failed to update budget'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget){
        $budget->delete();
        
        BudgetDetail::where('budget_id', $budget->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted budget.'
        ]);
    }

    public function list(){
        $budgets = Budget::select([
                'budgets.name', DB::raw('budget_types.name type'),'date_from','date_to','budgets.id','status','total_amount'
            ])
            ->leftJoin('budget_types', 'budget_types.id', '=', 'budgets.type');

        return DataTables::of($budgets)
            ->addColumn('status', function($budget){
                $status = $budget->status == 1 ? (Object) ['class' => 'success', 'label' => 'Active'] : (Object) ['class' => 'danger', 'label' => 'Inactive'];
                return "<span class='badge bg-$status->class'>$status->label</span>";
            })
            ->addColumn('action', function($budget){
                $url = "budget-details/$budget->id/view";
                $buttons = "<a href='$url' class='btn btn-md btn-info btn-more'>More</a>";
                $buttons .= " <button type='button' class='btn btn-md btn-warning btn-edit' value='$budget->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-md btn-danger btn-delete' value='$budget->id'>Delete</button>";
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function get($id){
        return Budget::find($id);
    }
}
