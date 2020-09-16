<?php

namespace Database\Factories;

use App\Models\Rating;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
            return [
                'rated_user_id' => $this->faker->numberBetween($min = 1, $max = 25),
                'rating_user_id' => $this->faker->numberBetween($min = 26, $max = 50),
                'rating' => $this->faker->numberBetween($min = 1, $max = 10),
                'rating_comment' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
    }
}
