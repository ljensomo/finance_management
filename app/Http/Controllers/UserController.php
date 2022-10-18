<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller{

    public function index(){
        return view('users');
    }

    public function edit($user){

        $user = User::find($user);

        if($user != null){
            return view('update-user')->with(['user' => $user]);
        }
    }

    public function store(UserRequest $request) {

        User::create($request->all());

        return response()->json([
            'message' => 'Successfully created user <b>'.$request->username.'</b>.',
            'success' => true,
        ]);
    }

    public function changeStatus(Request $request){

        $status_message = $request->status == 1 ? 'activated' : 'de-activated';

        $user = User::where('id', $request->user_id)->first();
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => "Successfully $status_message user <b>".$user->first_name.' '.$user->last_name.'</b>!',
            'success' => true,
        ]);
    }

    public function update(UserUpdateRequest $request){

        $user = User::find($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => 'Successfully updated user details.',
            'success' => true,
        ]);
    }

    public function list(){
        $users = User::select([
            DB::raw('CONCAT(first_name," ",last_name) name'), 'email', 'username', 'status','id'
        ]);

        return DataTables::of($users)
            ->addColumn('status', function($user){
                $status = $user->status == 0 ? (Object) ['class' => 'danger', 'label' => 'Inactive'] : (Object) ['class' => 'success', 'label' => 'Active'];
                $user_status = "<span class='badge bg-$status->class'>$status->label</span>";
                return $user_status;
            })
            ->addColumn('action', function($user){
                $button = $user->status == 0 ? (Object) ['class' => 'success', 'name' => 'Activate'] : (Object) ['class' => 'danger', 'name' => 'Deactivate'];
                $buttons = "<a href='users/".$user->id."/edit' class='btn btn-md btn-warning'>Edit</a>";
                $buttons .= " <button type='button' class='btn btn-md btn-$button->class btn-change' status='$user->status' value='$user->id'>$button->name</button>";
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}