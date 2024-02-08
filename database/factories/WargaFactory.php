<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warga>
 */
class WargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'no_kk' => $this->faker->text(255),
			'nik' => $this->faker->text(255),
			'nama' => $this->faker->text(255),
			'jenis_kelamin' => $this->faker->numberBetween(1, 10),
			'tgl_lahir' => null,
			'alamat_ktp' => $this->faker->text(255),
			'blok' => $this->faker->text(20),
			'nomor' => $this->faker->numberBetween(1, 10),
			'rt' => $this->faker->numberBetween(1, 10),
			'agama' => $this->faker->randomElement([0,1]),
			'pekerjaan' => $this->faker->text(255),
			'no_telp' => $this->faker->text(30),
			'status_warga' => $this->faker->text(255),
			'status_kawin' => $this->faker->numberBetween(1, 10),
			'status_sosial' => $this->faker->text(255),
			'catatan' => $this->faker->text(255),
			'kk_pj' => $this->faker->numberBetween(1, 10),
			'created_by' => $this->faker->text(50),
			'updated_by' => $this->faker->text(50),
		];
    }
}
