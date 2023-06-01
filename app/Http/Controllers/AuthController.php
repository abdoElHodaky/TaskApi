<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('phone', 'password');
        if (auth()->attempt($credentials)) {

            $user = auth()->user();

            if (!$user->is_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please verify your account'
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'phone' => 'required|unique:users',
            'password' => 'required'
        ]);
        $validatedData['password'] = bcrypt($request->password);
        $user = new \App\Models\User;
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];
        $user->password = $validatedData['password'];
        $user->save();
        $users = \App\Models\User::count();
        Cache::put('users', $users, 300);
        $usersHasNoPosts = \App\Models\User::doesntHave('posts')->get();
        Cache::put('usersHasNoPosts', $usersHasNoPosts,300);
        $token = $user->createToken('auth_token')->plainTextToken;
        $code = rand(100000, 999999);
        $user->verification_code = $code;
        $user->save();
        $message = "Your verification code is: " . $code;
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'message' => $message,
                'token' => $token
            ]
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Token deleted successfully'
        ]);
    }
    public function verify(Request $request)
    {
        $user = \App\Models\User::where('phone', $request->phone)->first();
        if ($user->verification_code == $request->verification_code) {
            $user->is_verified = true;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'User verified successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid verification code'
            ]);
        }
    }

}
