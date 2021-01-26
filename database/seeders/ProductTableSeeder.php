<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create( [
            'title'         => 'Contrary to popular belief, Lorem Ipsum is not simply random text',
            'description'   => 'There are many variations of passages of Lorem Ipsum available,
                                but the majority have suffered alteration in some form, by injected humour,
                                or randomised words which dont look even slightly believable.',
            'price'         => 10.00,
            'image'         => 'dummy.jpg',
        ] );
    }
}
