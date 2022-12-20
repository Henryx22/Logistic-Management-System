<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'product_id' => Product::factory(),
            //'user_id' => User::factory(),
            'product_id' => random_int(1,2),
            'user_id' => random_int(2,3),
            'quantity' => random_int(0,300),
            'total_price' => random_int(5,500),
            'status' => "Pendiente"
        ];
    }
}
