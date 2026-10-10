<?php

namespace Database\Factories;

use App\Models\LoginCarousel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginCarousel>
 */
class LoginCarouselFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'https://picsum.photos/1080/1080?random=' . $this->faker->unique()->numberBetween(1, 1000),
            'title' => $this->faker->sentence(4),
            'subtitle' => $this->faker->sentence(8),
            'is_active' => true,
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
