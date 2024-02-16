<?php

use App\Models\Pengurus;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of Pengurus', function () {
    $response = $this->get('/api/penguruses');

    $response->assertStatus(200);
});

it('can create a Pengurus', function () {
    $data = [
        'warga_id' => $this->faker->numberBetween(1, 10),
        'jabatan_id' => $this->faker->numberBetween(1, 10),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];
    $this->postJson('/api/penguruses', $data)->assertStatus(201);
    $this->assertDatabaseHas('pengurus', $data);
});

it('can fetch a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();

    $this->getJson('/api/penguruses/'.$pengurus->id)->assertStatus(200);
});

it('can update a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();

    $data = [
        'warga_id' => $this->faker->numberBetween(1, 10),
        'jabatan_id' => $this->faker->numberBetween(1, 10),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];

    $this->putJson('/api/penguruses/'.$pengurus->id, $data)->assertStatus(200);

    $this->assertDatabaseHas('pengurus', $data);
});

it('can delete a Pengurus', function () {
    $pengurus = Pengurus::factory()->create();
    $this->deleteJson('/api/penguruses/'.$pengurus->id)->assertStatus(204);
    $this->assertSoftDeleted('pengurus', ['id' => $pengurus->id]);
});
