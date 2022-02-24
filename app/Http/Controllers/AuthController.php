<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotRequest;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Owner;


use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $credentials = [
            'email' => $input['email'],
            'password' => $input['password']
        ];


        if ($input['type'] === "user") {
            $guard = Auth::guard('user')->attempt($credentials);
        }

        if ($input['type'] === "owner") {
            $guard = Auth::guard('owner')->attempt($credentials);
        }

        if ($input['type'] === "admin") {
            $guard = Auth::guard('admin')->attempt($credentials);
        }

        if (!$token = $guard) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $item = auth($input['type'])->user();
        // $item = auth()->config();

        return response()->json([
            'loginData' => $item,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
        // return $this->respondWithToken($token);
    }

    public function me()
    {
        if (auth('user')->user()) {
            return response()->json(auth('user')->user());
        }

        if (auth('owner')->user()) {
            return response()->json(auth('owner')->user());
        }

        if (auth('admin')->user()) {
            return response()->json(auth('admin')->user());
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {

        return auth()->logout();
    }
}
