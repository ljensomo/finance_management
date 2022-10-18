<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\Account;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expenses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request, Expense $expense)
    {
        $account = Account::find($request->charged_from);

        if($account->balance < $request->amount){
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance!'
            ]);
        }

        $account->decrement('balance', $request->amount);
        $expense = $expense::create($request->all());

        $created = $expense != null || $expense === false ? true : false;

        return response()->json([
            'success' => $created,
            'message' => $created ? 'Successfully added expense.' : 'Failed to save expense.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, Expense $expense){

        $record = $expense::find($request->id);
        $record->date_expend = $request->date_expend;
        $record->description = $request->description;
        $record->amount = $request->amount;
        $record->category = $request->category;
        $record->charged_from = $request->charged_from;
        $updated = $record->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated expense.' : 'Failed to update expense.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense has been deleted.',
        ]);
    }

    public function list(){
        $expenses = Expense::select([
                'date_expend', 'expenses.amount', DB::raw('expense_categories.name category'),
                DB::raw('accounts.name charged_from'), 'expenses.id', 'expenses.description'
            ])
            ->join('expense_categories', 'expenses.category', '=', 'expense_categories.id')
            ->join('accounts', 'charged_from', '=', 'accounts.id')
            ->get();

        return Datatables::of($expenses)
            ->addColumn('action', function($expense){
                $buttons = "<button type='button' class='btn btn-md btn-warning btn-edit' value='$expense->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-md btn-danger btn-delete' value='$expense->id'>Delete</button>";
                return $buttons;
            })
            ->make(true);
    }

    public function get($id){
        return Expense::find($id);
    }
}
