<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NewsRequest;
use App\Models\News;

class NewsController extends Controller
{

    public function index()
    {
        $items = News::all();

        return response()->json(['data' => $items]);
    }

    public function store(NewsRequest $request)
    {
        $input = $request->validated();

        $item = News::create([
            'title' => $input['title'],
            'text' => $input['text']
        ]);

        return response()->json(['data' => $item]);
    }
}
