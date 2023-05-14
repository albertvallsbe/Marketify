<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Category::class;

    public function definition()
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Beauty',
            'Home & Garden',
            'Toys & Games',
            'Sports & Outdoors',
            'Books',
            'Jewelry',
            'Health & Wellness',
            'Baby & Kids',
            'Food & Beverage',
            'Office Supplies',
            'Automotive',
            'Pet Supplies',
            'Music & Movies',
            'Travel',
            'Art & Crafts',
            'Party Supplies',
            'Musical Instruments',
            'Industrial & Scientific'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
        ];
    }
}
