<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function signUp(Request $request)
    {

        $validator  =  Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|unique:users,email',
            'password' => 'sometimes|required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => $validator->errors()
                ]
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        /**al crear un usuario automaticamente le creamos una empresa */
        Enterprise::create([
            'logo' => null,
            'user_id' => $user->id,
        ]);

        return response()->json(
            [
                "data" => $user,
                "success" => true,
                "message" => "User created succesfully"
            ]
        );
    }
    public function signIn(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['user'] =  User::find($authUser->id);

            return response()->json(
                [
                    "data" => $success,
                    "success" => true,
                    "message" => "User logged in"
                ]
            );
        } else {
            return response()->json(
                [
                    "data" => null,
                    "success" => false,
                    "message" => "Bad credentials"
                ]
            );;
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
