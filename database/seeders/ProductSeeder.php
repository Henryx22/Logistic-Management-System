<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Str;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$category1 = Category::create([
    		'id' => 1,
            'name' => "Electrodomestico",
        ]);

    	$category2 = Category::create([
    		'id' => 2,
            'name' => "Electronica",
        ]);

    	$supplier1 = Supplier::create([
    		'id' => 1,
            'name' => "Bosch",
        ]);
    	$supplier2 = Supplier::create([
    		'id' => 2,
            'name' => "LG",
        ]);

    	$purchase1 = Purchase::create([
    		'id' => 1,
            'product' => "Batidora",
            'category_id' => 1,
            'supplier_id' => 1,
            'cost_price' => 17,
            'quantity' => 400,
        ]);
		$purchase2 = Purchase::create([
    		'id' => 2,
            'product' => "Televisor",
            'category_id' => 2,
            'supplier_id' => 2,
            'cost_price' => 280,
            'quantity' => 500,
        ]);

		$product1 = Product::create([
    		'id' => 1,
            'purchase_id' => 1,
            'price' => 20,
        ]);
		$product2 = Product::create([
    		'id' => 2,
            'purchase_id' => 2,
            'price' => 300,
        ]);        


		$sale1 = Sale::create([
    		'id' => 1,
            'product_id' => 1,
            'user_id' => 1,
            'quantity' => 10,
            'total_price' => 200,
            'status' => "Pendiente",
        ]);
    }
}
