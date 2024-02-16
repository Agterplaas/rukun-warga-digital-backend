<?php

use App\Models\MJabatan;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of MJabatan', function () {
    $response = $this->get('/api/m-jabatans');

    $response->assertStatus(200);
});

it('can create a MJabatan', function () {
    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];
    $this->postJson('/api/m-jabatans', $data)->assertStatus(201);
    $this->assertDatabaseHas('m_jabatan', $data);
});

it('can fetch a MJabatan', function () {
    $mJabatan = MJabatan::factory()->create();

    $this->getJson('/api/m-jabatans/'.$mJabatan->id)->assertStatus(200);
});

it('can update a MJabatan', function () {
    $mJabatan = MJabatan::factory()->create();

    $data = [
        'nama' => $this->faker->text(255),
        'created_by' => $this->faker->text(50),
        'updated_by' => $this->faker->text(50),
    ];

    $this->putJson('/api/m-jabatans/'.$mJabatan->id, $data)->assertStatus(200);

    $this->assertDatabaseHas('m_jabatan', $data);
});

it('can delete a MJabatan', function () {
    $mJabatan = MJabatan::factory()->create();
    $this->deleteJson('/api/m-jabatans/'.$mJabatan->id)->assertStatus(204);
    $this->assertSoftDeleted('m_jabatan', ['id' => $mJabatan->id]);
});
