<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->name();

        return [
            'brand_id' => Brand::factory(),
            'sku' => $this->faker->shuffleString('1q2w3e4r5t6y7u8i9o0p1a2s3d4f5g6h7j8k9l'),
            'name' => $name,
            'slug' => (string) Str::of($name)->slug(),
            'description' => $this->faker->text(),
            'quantity' => $this->faker->randomDigit(),
            'price' => $this->faker->randomFloat(2),
            'sale_price' => $this->faker->randomFloat(2),
            'status' => $this->faker->randomElement([0,1]),
            'featured' => $this->faker->randomElement([0,1]),
        ];
    }
}
