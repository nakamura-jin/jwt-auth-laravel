<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MenuRequest;


class MenuController extends Controller
{
    public function index()
    {
        $items = Menu::all();

        foreach ($items as $item) {
            $area = Area::where('id', $item->area_id)->first();
            $item->area_name = $area->name;

            $genre = Genre::where('id', $item->genre_id)->first();
            $item->genre_name = $genre->name;
        }

        return response()->json([
            'data' => $items
        ], 200);
    }


    public function store(MenuRequest $request)
    {
        $input = $request->validated();

        $url = $this->upload($request);

        $product_code = $this->code($request);
        $result_product_code = $product_code->content();
        $product_code_array = json_decode($result_product_code, true);

        $item = Menu::create([
            'name' => $input['name'],
            'description' => $input['description'],
            'owner_id' => $input['owner_id'],
            'area_id' => $input['area_id'],
            'image' => $url,
            'price' => $input['price'],
            'quantity' => 0,
            'product_code' => $product_code_array['code'],
            'genre_id' => $product_code_array['id'],
        ]);
        return response()->json(['data' => $item]);
    }


    public function upload(MenuRequest $request)
    {
        $image = $request->file('image');

        $path = Storage::disk('s3')->putFile('/', $image, 'public');

        $url = Storage::disk('s3')->url($path);

        return $url;
    }

    //make instore_code
    public function code(Request $request)
    {
        $genre = Genre::where('name', $request->genre)->first();
        if (!$genre) {
            return response()->json(['data' => "error"]);
        }
        $item = Menu::all();
        $menu = $item->pluck('id')->last() + 1;

        if ($genre->id == 1) {
            $product_code = 1;
        }

        $menu_id = str_pad($menu, 4, '0', STR_PAD_LEFT);

        $code = $product_code.$menu_id;

        $data = ['code' => $code, 'id' => $genre->id];

        return response()->json($data);
    }


    public function show($menu)
    {
        $item = Menu::find($menu);

        $area = Area::find($item->area_id);
        $item->area_name = $area->name;

        $genre = Genre::find($item->genre_id);
        $item->genre_name = $genre->name;

        if ($item) {
            return response()->json(['data' => $item], 201);
        } else {
            return response()->json(['message' => 'エラーです'], 404);
        }
    }


    public function update(Request $request, $id)
    {
        if ($request->image) {
            $url = $this->upload($request);
            $update = [
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => $request->owner_id,
                'area_id' => $request->area_id,
                'genre_id' => $request->genre_id,
                'price' => $request->price,
                'image' => $url
            ];
        } else {
            $update = [
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => $request->owner_id,
                'area_id' => $request->area_id,
                'genre_id' => $request->genre_id,
                'price' => $request->price,
            ];
        }

        $item = Menu::find($id)->update($update);


        if ($item) {
            return response()->json(['data' => $item], 201);
        } else {
            return response()->json(['message' => '変更に失敗しました'], 404);
        }
    }


    public function destroy(Request $request)
    {
        $item = Menu::where('id', $request->id)->delete();
        if ($item) {
            return response()->json(['message' => 'メニューを削除しました'], 201);
        } else {
            return response()->json(['message' => 'メニューを削除できませんでした'], 404);
        }
    }

    public function myMenu($menu)
    {
        $items = Menu::where('owner_id', $menu)->get();

        foreach ($items as $item) {
            $area = Area::where('id', $item->area_id)->first();
            $item->area_name = $area->name;

            $genre = Genre::where('id', $item->genre_id)->first();
            $item->genre_name = $genre->name;
        }

        if ($items) {
            return response()->json(['data' => $items], 201);
        } else {
            return response()->json(['message' => 'エラーです'], 404);
        }
    }


    //make return GenreName
    public function genre()
    {
        $genre = Genre::all();

        $items = [];
        foreach ($genre as $item) {
            array_push($items, $item->name);
        }

        return response()->json(['data' => $items]);
    }

    //update stock
    public function recievedStock(Request $request) {
        $items = $request->stock;

        foreach ($items as $item) {
            $menu = Menu::where('id', $item['id'])->first();
            $quantity = $item['recievedQuantity'] + $menu->quantity;
            $update = [
                'quantity' => $quantity
            ];

            $menu->update($update);
        }

        return response()->json(['data' => $menu]);
    }

}
