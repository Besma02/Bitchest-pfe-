<?php

namespace Database\Factories;
use App\Models\Client;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'balance' => 500,  // Balance initiale
            'type' => 'client',
        ];
    }

}
