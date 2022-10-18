<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\TransferAmountRequest;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accounts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request, Account $account){
        
        $account = $account::create($request->all());
        $created = $account != null ? true : false;

        return response()->json([
            'success' => $created,
            'message' => $created ? 'Account successfully created.' : 'Failed to create account.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request, Account $account){
        
        $account = $account->find($request->id);
        $account->name = $request->name;
        $account->description = $request->description;
        $account->balance = $request->balance;
        $updated = $account->save();

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Successfully updated account.' : 'Failed to update account.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted account.'
        ]);
    }

    public function list(){
        $accounts = Account::select('*');

        return Datatables::of($accounts)
            ->addColumn('action', function($account){
                $buttons = "<button type='button' class='btn btn-primary btn-transfer' value='$account->id'>Transfer</button>";
                $buttons .= " <button type='button' class='btn btn-warning btn-edit' value='$account->id'>Edit</button>";
                $buttons .= " <button type='button' class='btn btn-danger btn-delete' value='$account->id'>Delete</button>";
                return $buttons;
            })
            ->make(true);
    }

    public function get($id){
        return Account::find($id);
    }

    public function jsonList(Account $account, $id){
        return $account->where('id', '!=', $id)->get();
    }

    public function transferAmount(TransferAmountRequest $request){
        $account_from = Account::find($request->account_id_from);

        $total_deduction = $request->amount + $request->fee;

        if($account_from->balance < $total_deduction || $account_from->balance === 0){
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance.'
            ]);
        }

        $account_from->decrement('balance', $total_deduction);

        $account_to = Account::find($request->account_id_to);
        $account_to->increment('balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Succesfully transferred amount.'
        ]);
    }
}
