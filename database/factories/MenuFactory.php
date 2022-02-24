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
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'owner_id' => $this->faker->numberBetween(1,10),
            'area_id' => $this->faker->numberBetween(1,3),
            'genre_id' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->numberBetween(100, 999),
            'quantity' => 0,
            'image' => $this->faker->imageUrl(),
            'product_code' => $this->faker->unique()->numerify('#####')
        ];
    }
}
