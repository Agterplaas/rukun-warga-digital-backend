<?php

use App\Models\Master\Jabatan;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of jabatan', function () {
    $response = $this->get('/api/master/jabatan');

    $response->assertStatus(200);
});

it('can create a jabatan', function () {
    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];
    $this->postJson('/api/master/jabatan', $data)->assertStatus(201);
});

it('can fetch a jabatan', function () {
    $jabatan = Jabatan::factory()->create();

    $this->getJson('/api/master/jabatan/'.$jabatan->id)->assertStatus(200);
});

it('can update a jabatan', function () {
    $jabatan = Jabatan::factory()->create();

    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];

    $this->putJson('/api/master/jabatan/'.$jabatan->id, $data)->assertStatus(200);
});

it('can delete a jabatan', function () {
    $jabatan = Jabatan::factory()->create();

    $this->deleteJson('/api/master/jabatan/'.$jabatan->id)->assertStatus(204);
});
