<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Menu;



class PurchaseController extends Controller
{
    public function store(PurchaseRequest $request)
    {
        $input = $request->validated();

        $item = Purchase::create([
            'user_id' => $input['user_id'],
            'menu_id' => $input['menu_id'],
            'quantity' => $input['quantity'],
            'display' => 0,
        ]);

        return response()->json(['data' => $item]);
    }

    public function show(Request $request)
    {
        $item = Purchase::find($request->id);

        if ($item) {
            return response()->json([ 'data' => $item ], 200);
        } else {
            return response()->json([ 'message' => 'Not found',], 404);
        }
    }

    public function update(Request $request)
    {
        // $url = URL::signedRoute('makeUrl')

        // $domain = config('services.stripe.domain_url');
        // $url = $domain.Str::random(48);

        $update = [
            'display' => $request->display,
            'url' => $url
        ];

        Purchase::where('id', $request->id)->update($update);

        $item = Purchase::where('id', $request->id)->first();

        $menu = Menu::where('id', $item->menu_id)->first();
        $item->menu_name = $item->name;
        $item->menu_description = $item->description;
        $item->menu_price = $item->price;
        $item->menu_image = $menu->image;


        return response()->json(['data' => $item]);


    }

    // public function makeUrl(Request $request)
    // {
    //     // $id = $request->id;
    //     // return URL::signedRoute('makeUrl', ['purchase_id' => $id]);
    //     return URL::signedRoute('makeUrl');
    // }

    public function searchPurchase(Request $request)
    {
        $item = Purchase::where('url', 'LIKE', "%{$request->signature}%")->first();
        return response()->json([ 'data' => $item ]);
    }

}
