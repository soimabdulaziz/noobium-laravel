<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'picture' => env('AVATAR_GENERATOR_URL') . $request['name'],
        ]); 

        $token = auth()->login($user);

        if(!$token) 
        {
            return response()->json([
                'meta' => [
                    'code' =>500,
                    'status' => 'error',
                    'message' => 'Cannot add user'
                ],
                'data' => [],
            ], 500);//kode error server 500
        }

        return response()->json([
            'meta' => [
                'code' =>200,
                'status' => 'success',
                'message' => 'user created successfully'
            ],
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'picture' => $user->picture,
                ],
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => strtotime('+' . auth()->factory()->getTTL() . ' minutes'),// generate timestamp waktu skrang + 60 mnit
                ]
            ],
        ]);
    }
}
