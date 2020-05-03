<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);
    }

    public function login(Request $request)
    {
        if ($token = Auth::attempt($request->only(['email', 'password']))) {
            return $this->responseWithToken($token);
        }

        return response()->json([
            'status' => 'auth failed',
            'message' => 'Login failed, check your email and password'
        ], 401);
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json([
            'user' => [
                'data' => $user,
                'profile' => $user->profile
            ]
        ]);
    }

    public function refresh()
    {
        return $this->responseWithToken(Auth::refresh());
    }

    public function responseWithToken($token)
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Login successfully',
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Logout successfully!', 'status' => 'ok']);
    }
}