<?php

use Illuminate\Database\Seeder;

use App\Product;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            'name' => 'product 1',
            'quantity' => 2,
            'created_at' => Carbon::now()
        ]);

        Product::insert([
            'name' => 'product 2',
            'quantity' => 5,
            'created_at' => Carbon::now()
        ]);
    }
}
