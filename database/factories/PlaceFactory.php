<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name'  => $name,
            'slug'  => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 100000),
            'city'  => $this->faker->city,
            'state' => strtoupper($this->faker->lexify('??')),
        ];
    }
}
