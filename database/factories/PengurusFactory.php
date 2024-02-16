<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengurus>
 */
class PengurusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warga_id' => $this->faker->numberBetween(1, 10),
            'jabatan_id' => $this->faker->numberBetween(1, 10),
            'created_by' => $this->faker->text(50),
            'updated_by' => $this->faker->text(50),
        ];
    }
}
