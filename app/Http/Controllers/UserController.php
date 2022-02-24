<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Auth\Events\Registered;

//test
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use JWTAuth;
use Carbon\Carbon;
use Log;
use App\Mail\RegisterMail;
use App\Mail\ChangePasswordMail;

class UserController extends Controller
{
    public function index () {
        $items = User::all();

        return response()->json(array('data' => $items));
    }


    public function store(UserRequest $request)
    {
        // バリデーション
        $input = $request->validated();


        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'type_id' => 3
        ]);


        //仮登録処理

        // 1.メールアドレスを仮登録へ移動する
        $user->verify_email_address = $user->email;
        // 2.仮のメールアドレスを入れる。　被らないためにユニークで。
        $user->email = Str::random(32) . '@temp.com';


        // 3.仮登録確認用の設定
        $user->verify_email = false;
        $user->verify_token = Str::random(32);
        $user->verify_date = Carbon::now()->toDateTimeString();

        // 4.保存
        $user->save();


        // 5.メール送信処理
        $data = [
            'type' => "register",
            'email' => $user->verify_email_address,
            'token' => $user->verify_token
        ];
        Mail::to($user->verify_email_address)->send(new RegisterMail($data));

        return response()->json(['data' => $user], 200);
    }


    public function verify($token)
    {
        //登録メールURLクリック後の処理

        $params['result'] = "error";

        // トークンの有効期限を30分とするため有効な時間を算出
        // 現在時間 -30分
        $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();

        $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

        if ($user) {
            // 本登録されていない
            if (User::where("email", $user->verify_email_address)->first()) {
                $params['result'] = "exist";
            } else {
                // 仮メールアドレスを本メールに移動
                $user->email = $user->verify_email_address;
                // 仮メールアドレスを削除
                $user->verify_email_address = null;
                // 有効なユーザーにする
                $user->verify_email = true;
                // その他クリーニング
                $user->verify_token = null;
                $user->verify_date = null;
                // 承認日登録
                $user->email_verified_at = Carbon::now()->toDateTimeString();

                // テーブル保存
                $user->save();
                $params['result'] = "success";
                // Log::info('Verify Success: ' . $user);
            }
        } else {
            // dd($token);
            $params['token'] = $token;
            return view('verify', $params);
            Log::info('Verify Not Found: token=' . $token);
        }

        return view('verify', $params);
    }


    public function resend($token)
    {
        $user = User::where('verify_token', $token)->first();

        $user->verify_token = Str::random(32);
        $user->verify_date = Carbon::now()->toDateTimeString();

        $user->save();

        $data = [
            'type' => "register",
            'email' => $user->verify_email_address,
            'token' => $user->verify_token
        ];
        Mail::to($user->verify_email_address)->send(new RegisterMail($data));
        return view('resend');
    }

    public function changePassword(Request $request)
    {
        // $params['result'] = "error";

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json(['message' => 'ユーザーが存在しません']);
        } else {

            $user->verify_token = Hash::make($request->password);
            $user->verify_date = Carbon::now()->toDateTimeString();
            $user->save();

            $token = base64_encode($user->verify_token);

            Mail::to($request->email)->send(new ChangePasswordMail($token));
        }
        return response()->json(['token' => $token]);
    }

    public function reminder($token)
    {
        $params['result'] = "error";


        // トークンの有効期限を30分とするため有効な時間を算出
        // 現在時間 -30分
        $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();
        // tokenが一致するか
        $user = User::where('verify_token', base64_decode($token))->where('verify_date', '>', $verify_limit)->first();

        if($user) {
            $user->password = base64_decode($token);
            $user->verify_token = null;
            $user->verify_date = null;
            $user->save();

            $params = ['result' => 'success'];
        }

        return view('reminder', $params);
    }

    public function password_change(Request $request)
    {

        $params['result'] = "error";

        // 入力情報のバリデーション
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $token = $request->token;

        // バリデーションエラーの場合レスポンス
        if ($validator->fails()) {
            $params['message'] = $validator->errors();
            Log::info('Reminder Error: ' . $validator->errors());
            return redirect('/reminder/' . $token)
                ->withErrors($validator)
                ->withInput();
        } else {

            // トークンの有効期限を30分とするため有効な時間を算出
            // 現在時間 -30分
            $verify_limit = Carbon::now()->subMinute(30)->toDateTimeString();
            // tokenが一致するか
            $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

            if ($user) {

                // パスワードを変更する
                $user->password = bcrypt($request->password);
                // その他クリーニング
                $user->verify_token = null;
                $user->verify_date = null;
                // 承認日登録
                $user->email_verified_at = Carbon::now()->toDateTimeString();

                // テーブル保存
                $user->save();
                Log::info('Reminder Success: ' . $user);
                $params = ['result' => 'success'];
            } else {
                Log::info('Reminder Error: Notfound User');
                $params = ['result' => 'error'];
            }
        }
        return view('jwt.reminder', $params);
    }
}
