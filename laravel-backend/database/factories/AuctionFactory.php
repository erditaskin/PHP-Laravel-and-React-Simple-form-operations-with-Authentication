<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Auction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = $this->faker->numberBetween(10, 200);

        return [
            'name' => $this->faker->lastName . " " . ucwords($this->faker->unique()->word),
            'description' => $this->faker->text,
            'image' => sprintf('item%s.jpg', $this->faker->numberBetween(1,20)),
            'price' => $price,
            'last_price' => $price,
            'close_datetime' => $this->faker->dateTimeBetween('+10 days','+1 year'),
        ];
    }
}
