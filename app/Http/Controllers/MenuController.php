<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Owner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


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


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'owner_id' => 'required',
            'area_id' => 'required',
            'genre_id' => 'required',
            'image' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => '登録に失敗しました']);
        }

        $url = $this->upload($request);

        Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->owner_id,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'price' => $request->price,
            'image' => $url,
        ]);
    }


    public function upload(Request $request)
    {
        $image = $request->file('image');

        $path = Storage::disk('s3')->putFile('/', $image, 'public');

        $url = Storage::disk('s3')->url($path);

        return $url;
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
}
