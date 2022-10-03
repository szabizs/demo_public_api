<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
	public function run()
	{
        DB::table('products')->delete();
        Product::factory()
            ->count(10)
            ->create();
	}
}
