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
        $menu = Menu::where('id', $request->menu_id)->first();

        $lineItems = [
            $lineItem = [
                'name' => $menu->name,
                'description' => $menu->description,
                'amount' => $menu->price,
                'currency' => 'jpy',
                'images' => [ $menu->image ],
                'quantity' => $request->quantity
            ]
        ];

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => 'pgm_eng@yahoo.co.jp',
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [$lineItems],
            'success_url' => 'http://localhost:8080/purchased',
            'cancel_url' => 'http://localhost:8080',
        ]);


        return $session;
    }

}
