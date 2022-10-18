<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request){

        if(Auth::attempt($request->all())){
            $user = Auth::getLastAttempted();
            if($user->status==1){
                return response()->json([
                    'message' => 'Successfully logged in.',
                    'success' => true
                ]);
            }

            return response()->json([
                'message' => 'Your account is deactivated, please contact administrator.',
                'success' => false
            ]);
        }else{
            return response()->json([
                'message' => 'Invalid username or password!',
                'success' => false
            ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
