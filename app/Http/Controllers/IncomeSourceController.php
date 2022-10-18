<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeSourceRequest;
use App\Models\IncomeSource;
use Yajra\DataTables\Facades\DataTables;
use DB;

class IncomeSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('income-sources');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncomeSourceRequest $incomeSourceRequest, IncomeSource $incomeSource)
    {
        $income_source = $incomeSource::create($incomeSourceRequest->all());
        $created = $income_source != null || $income_source != false ? true : false;

        return response()->json([
            'success' => $created,
            'message' => $created ? 'Successfully added income source.' : 'Failed to create income source.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IncomeSources  $incomeSources
     * @return \Illuminate\Http\Response
     */
    public function update(IncomeSourceRequest $request, IncomeSource $income_source)
    {
        $record = $income_source::find($request->id);
        $record->income_type = $request->income_type;
        $record->income_description = $request->income_description;
        $record->income_start = $request->income_start;
        $updated = $record->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated income source.' : 'Failed to update income source.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IncomeSources  $incomeSources
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomeSource $incomeSource)
    {
        $incomeSource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income source has been deleted.',
        ]);
    }

    // returns json list
    public function list(){

        $sources = IncomeSource::select([
                'income_sources.id', DB::raw('income_types.type_name income_type'),
                'income_description', 'income_start', 'income_status'
            ])
            ->join('income_types', 'income_sources.income_type', '=', 'income_types.id')
            ->where('income_status', 1)
            ->get();

        return DataTables::of($sources)
            ->addColumn('status', function($source){
                return $source->income_status == 1 ? "<span class='badge bg-success'>Active</span>" : "<span class='badge bg-danger'>Inactive</span>";
            })
            ->addColumn('action', function($source){
                $buttons = "<button type='button' class='btn btn-warning btn-edit' value='$source->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-danger btn-delete' value='$source->id'>Delete</button>";
                return $buttons;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function get($id){
        return IncomeSource::find($id);
    }

    public function jsonList(IncomeSource $source){
        return $source::where('income_status', 1)->get();
    }
}
