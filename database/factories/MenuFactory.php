<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $request->name,
            'discription' => $request->discription,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'price' => $request->price,
            'image' => $url
        ];
    }
}
