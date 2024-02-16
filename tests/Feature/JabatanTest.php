<?php

use App\Models\Master\Jabatan;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of jabatan', function () {
    $response = $this->get('/api/m-jabatans');

    $response->assertStatus(200);
});

it('can create a jabatan', function () {
    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];
    $this->postJson('/api/m-jabatans', $data)->assertStatus(201);
    $this->assertDatabaseHas('m_jabatan', $data);
});

it('can fetch a jabatan', function () {
    $jabatan = Jabatan::factory()->create();

    $this->getJson('/api/m-jabatans/'.$jabatan->id)->assertStatus(200);
});

it('can update a jabatan', function () {
    $jabatan = Jabatan::factory()->create();

    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];

    $this->putJson('/api/m-jabatans/'.$jabatan->id, $data)->assertStatus(200);

    $this->assertDatabaseHas('m_jabatan', $data);
});

it('can delete a jabatan', function () {
    $jabatan = Jabatan::factory()->create();
    $this->deleteJson('/api/m-jabatans/'.$jabatan->id)->assertStatus(204);
    $this->assertSoftDeleted('m_jabatan', ['id' => $jabatan->id]);
});
