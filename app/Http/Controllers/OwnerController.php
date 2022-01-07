<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function store(UserRequest $request)
    {
        $input = $request->validated();

        $item = Owner::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return response()->json(['data' => $item], 200);
    }
}
