<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Models\Menu;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
    //     // $user = User::where('id', $request->user_id)->first();
        $menu = Menu::where('id', $request->id)->first();

        $lineItems = [
            $lineItem = [
                'name' => $menu->name,
                'description' => $menu->description,
                'amount' => $menu->price,
                'currency' => 'jpy',
                'images' => [ $menu->image ],
                'quantity' => 1
            ]
        ];

        \Stripe\Stripe::setApiKey(config('stripe.ssk'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [$lineItems],
            'success_url' => 'http://localhost:8000',
            'cancel_url' => 'http://localhost:8000/login',
        ]);

        // $publickey = config('stripe.public');

        return $session;
    }

}
