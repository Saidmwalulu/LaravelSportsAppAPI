<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //register function
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = Admin::where('email', $request->email)->exists();

        if (!$email) {
            $data = Admin::create([
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

    public function adminLogin() {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('admin-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $token;
    }


    public function adminMe()
    {
        return response()->json(auth()->user());
    }

    public function adminLogout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}
