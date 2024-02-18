<?php

use App\Models\Master\Jabatan;
use App\Models\Pengurus;
use App\Models\Warga;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of Pengurus', function () {
    $response = $this->get('/api/pengurus');

    $response->assertStatus(200);
});

it('can create a Pengurus', function () {
    $data = [
        'warga_id' => Warga::factory()->create()->id,
        'jabatan_id' => [Jabatan::factory()->create()->id],
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];

    $this->postJson('/api/pengurus', $data)->assertStatus(201);
});

it('can fetch a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();

    $this->getJson('/api/pengurus/'.$pengurus->id)->assertStatus(200);
});

it('can update a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();

    $data = [
        'warga_id' => Warga::factory()->create()->id,
        'jabatan_id' => [Jabatan::factory()->create()->id],
    ];

    $this->putJson('/api/pengurus/'.$pengurus->id, $data)->assertStatus(200);
});

it('can delete a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();

    $this->deleteJson('/api/pengurus/'.$pengurus->id)->assertStatus(204);
});
