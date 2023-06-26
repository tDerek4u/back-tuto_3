<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        //email //password

        logger($request->all());

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $user = User::where('email',$request->email)->first();

        if(isset($user)){
            if(Hash::check($request->password, $user->password)){
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $user->createToken('tuto-3')->plainTextToken
                ]);

            }else{
                return response()->json([
                    'success' => false,
                    'user' => null,
                    'token' => null
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'user' => null,
                'token' => null
            ]);
        }
    }

    //register
    public function register(Request $request){

        logger($request->all());

        $request->validate([
            'name' => 'required|max:20',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);


        $token = $user->createToken('tuto-3')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ],200);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Loggout out'
        ];
    }

}
