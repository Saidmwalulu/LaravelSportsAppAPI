<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //register function
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = User::where('email', $request->email)->exists();

        if (!$email) {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => 'registered successfully',
                'data' => $data
            ], 201);
        } else {
            return response()->json([
                'fail' => 'email already exists'
            ], 404);
        }


    }

    public function userLogin() {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('user-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $token;
    }

}
