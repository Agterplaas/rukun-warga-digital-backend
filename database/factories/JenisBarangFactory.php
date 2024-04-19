<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisBarang>
 */
class JenisBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'm_kategori_barang_id' => $this->faker->numberBetween(1, 10),
			'nama' => $this->faker->text(255),
			'created_by' => $this->faker->numberBetween(1, 10),
			'updated_by' => $this->faker->numberBetween(1, 10),
		];
    }
}
