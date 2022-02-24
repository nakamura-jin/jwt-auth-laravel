<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseMail;
use App\Mail\AdminSendMail;
use App\Http\Requests\MailRequest;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $url = $request->url;
        Mail::to('pgm_eng@yahoo.co.jp')->send(new PurchaseMail($url));

    }

    public function admin_send(MailRequest $request)
    {
        $input = $request->validated();

        $sendData = [
            'name' => $input['name'],
            'title' => $input['title'],
            'text' => $input['text']
        ];

        Mail::to($input['email'])->send(new AdminSendMail($sendData));
    }
}
