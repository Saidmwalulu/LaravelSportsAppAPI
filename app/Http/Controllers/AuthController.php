<?php

namespace App\Http\Controllers;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Laravolt\Avatar\Facade as Avatar;
use App\Notifications\ResetPwdNotification;

class AuthController extends Controller
{
    private $otp;
    public function __construct() {

        $this->otp = new Otp;

    }

    //register function
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $image_url = "https://ui-avatars.com/api/?background=random&name=".$request->name;

        $email = User::where('email', $request->email)->exists();

        if (!$email) {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_image' => $image_url
            ]);

            return response()->json([
                'status' => 'success', //login successfully
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error' //email already exists
            ]);
        }


    }

    //login function
    public function login() {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error', //wrong email or password
                ]);
        }
        return response()->json([
            'status' => 'success', //login success or do something after logging in
            'token' => $token,
            'user' => Auth::user()
        ]);

    }

    public function getUsers(Request $request) {
        $users = User::orderBy('id','desc')->get();


        foreach ($users as $user) {
            $user;
        }

        $count = $user->count();

        return response()->json([
            'success' => true,
            'count' => $count,
            'users' => $users
        ]);
    }

    public function updateUsers(Request $request) {
        $user = User::find($request->id);
        $user->role = $request->role;
        $user->update();

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'user updated'
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out.'
        ]);
    }

    public function profileUser(Request $request) {
        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                $old_path = public_path().'/uploads/profile_images/'.$user->profile_photo;
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }
            $image_name = 'profile_image'.time().'.'.$request->profile_photo->extension();
            $request->profile_photo->move(public_path('/uploads/profile_images/'),$image_name);
        } else {
           $image_name = $user->profile_photo;
        }

        $user->update([
            'profile_photo' => $image_name
        ]);
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Photo updated.'
        ]);
    }

    public function userName(Request $request) {
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Name updated.'
        ]);
    }

    //change password function
    public function changePassword(Request $request) {
        $request->validate([
            'current_password' => 'required|min:6|max:10',
            'password' => 'required|min:6|max:10'
        ]);

        $user = Auth::user();

        if (Hash::check($request->current_password, $user->password)) {

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed.'
            ]);

        } else {

            return response()->json([
                'success' => false,
                'fail_message' => 'Current password is incorrect.'
            ]);

        }
    }


    //forgot password function sending OTP to your email
    public function forgotPassword(Request $request) {  //nulgxzkrdooaknel

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->notify(new ResetPwdNotification());

            return response()->json([
                'success' => true,
                'message' => 'Check your inbox email to get OTP reset code.'
            ]);

        } else {
            return response()->json([
                'success' => false,
                'fail_message' => 'This email is not registered.'
            ]);
        }

    }

    //reset password function
    public function resetPassword(Request $request) {

        $request->validate([
            'email' => 'required',
            'otp' => 'required',
            'password' => 'required'
        ]);

        $otp2 = $this->otp->validate($request->email, $request->otp);

        if (!$otp2->status) {

            return response()->json([
                'success' => false,
                'fail_message' => 'Invalid OTP.'
            ]);
        }

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Password reseted. Login'
        ]);
    }


}
