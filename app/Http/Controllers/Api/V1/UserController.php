<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'string|email|max:100|unique:users',
            'password' => 'string|max:100|confirmed'
        ]);

        if ($validator->fails()) {
            throw new StoreResourceFailedException("Error sending request", $validator->errors());
        }

        $data['password'] = app('hash')->make($data['password']);
        
        if (!isset($data['is_verified'])) {
            $data['is_verified'] = 0;
        }

        $user = User::create($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Register successfully!',
            'user' => $user
        ], 201);
    }
}
