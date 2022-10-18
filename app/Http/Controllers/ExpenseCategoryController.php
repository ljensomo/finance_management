<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseCategoryRequest;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expense-categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseCategoryRequest $request, ExpenseCategory $category){
        $record = $category::create($request->all());
        $created = $record != null || $record != false ? true : false;

        return response()->json([
            'success' => true,
            'message' => $created ? 'Successfully created expense category.' : 'Failed to create expense category.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseCategoryRequest $request, ExpenseCategory $category){
        $record = $category::find($request->id);
        $record->name = $request->name;
        $record->description = $request->description;
        $updated = $record->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated expense category.' : 'Failed to update expense category'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $category, $id){
        $deleted = $category::find($id)->delete();

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Successfully deleted expense category.' : 'Failed to delete expense category.'
        ]);
    }

    public function list(ExpenseCategory $category){
        $categories = $category::select('*');

        return DataTables::of($categories)
            ->addColumn('action', function($category){
                $buttons = "<button type='button' class='btn btn-warning btn-edit' value='$category->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-danger btn-delete' value='$category->id'>Delete</button>";
                return $buttons;
            })
            ->make(true);
    }

    public function get(ExpenseCategory $category, $id){
        return $category::find($id);
    }

    public function jsonList(ExpenseCategory $category){
        return $category::all();
    }
}
