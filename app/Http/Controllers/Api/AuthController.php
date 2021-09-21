<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // not auto login
        // $success['token'] = $user->createToken(NULL)->accessToken;
        // $success['name'] = $user->name;

        return $this->sendResponse('User created successfully', $user);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $success['user_id'] = $user->id;
            $success['name']    = $user->name;
            $success['token']   = $user->createToken(NULL)->accessToken;

            return $this->sendResponse('User login successfully', $success);
        } else {
            return $this->sendError('Unauthorized', ['error' => "Username or Password doesn't match"]);
        }
    }
}
