<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->numberBetween(500, 5000),
            'description' => $this->faker->sentence,
            'img_url' => $this->faker->imageUrl(640, 480, 'fashion', true),
            'condition' => $this->faker->randomElement(['新品', '未使用に近い', '目立った傷なし', 'やや傷や汚れあり']),
            'is_sold' => false,
            'sold_out' => false,
            'seller_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
