<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;
    private $number = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unique_user = 'user' . $this->number++;
        return [
            'username' => $unique_user,
            'password' => $unique_user
        ];
    }

}
