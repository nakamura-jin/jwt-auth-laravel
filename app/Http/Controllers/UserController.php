<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $input = $request->validated();

        $item = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return response()->json(['data' => $item], 200);
    }

    public function qrcode(Request $request)
    {
        $item = User::where('id', $request->id)->first();
        $qr = QrCode::generate($item);
        return response()->json($qr);
    }
}
