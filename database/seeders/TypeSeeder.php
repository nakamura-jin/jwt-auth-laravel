<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            'name' => 'admin'
        ];

        $type = new Type;

        $type->fill($params)->save();

        $params = [
            'name' => 'owner'
        ];

        $type = new Type;

        $type->fill($params)->save();

        $params = [
            'name' => 'user'
        ];

        $type = new Type;

        $type->fill($params)->save();
    }
}
