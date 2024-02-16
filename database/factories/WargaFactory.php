<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;

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
        App::setLocale('id');
        $prefix = '08';

        return [
            'no_kk' => $this->faker->numberBetween(1000000000000000, 9999999999999999),
            'nik' => $this->faker->numberBetween(1000000000000000, 9999999999999999),
            'nama' => $this->faker->name(50),
            'jenis_kelamin' => $this->faker->randomElement([0, 1]),
            'tgl_lahir' => $this->faker->date(),
            'alamat_ktp' => $this->faker->address(50),
            'blok' => $this->faker->regexify('[A-D]'),
            'nomor' => $this->faker->numberBetween(1, 30),
            'rt' => $this->faker->numberBetween(1, 5),
            'agama' => $this->faker->numberBetween(0, 4),
            'no_telp' => $prefix.$this->faker->numerify('#########'),
            'status_pekerjaan' => $this->faker->numberBetween(0, 7),
            'pekerjaan' => $this->faker->jobTitle(),
            'status_warga' => $this->faker->numberBetween(0, 1),
            'status_kawin' => $this->faker->numberBetween(0, 4),
            'status_sosial' => $this->faker->numberBetween(0, 2),
            'catatan' => $this->faker->text(30),
            'kk_pj' => $this->faker->numberBetween(0, 2),
        ];
    }
}
