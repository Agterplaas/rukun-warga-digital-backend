<?php

use App\Models\Master\JenisBarang;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of JenisBarang', function () {
    $response = $this->get('/api/jenis-barangs');

    $response->assertStatus(200);
});

it('can create a JenisBarang', function () {
    $data = [
			'm_kategori_barang_id' => $this->faker->numberBetween(1, 10),
			'nama' => $this->faker->text(255),
			'created_by' => $this->faker->numberBetween(1, 10),
			'updated_by' => $this->faker->numberBetween(1, 10),
		];
    $this->postJson('/api/jenis-barangs', $data)->assertStatus(201);
    $this->assertDatabaseHas('m_jenis_barang', $data);
});

it('can fetch a JenisBarang', function () {
    $jenisBarang = JenisBarang::factory()->create();
    
    $this->getJson('/api/jenis-barangs/' . $jenisBarang->id)->assertStatus(200);
});

it('can update a JenisBarang', function () {
    $jenisBarang = JenisBarang::factory()->create();
    
    $data = [
			'm_kategori_barang_id' => $this->faker->numberBetween(1, 10),
			'nama' => $this->faker->text(255),
			'created_by' => $this->faker->numberBetween(1, 10),
			'updated_by' => $this->faker->numberBetween(1, 10),
		];
    
    $this->putJson('/api/jenis-barangs/' . $jenisBarang->id, $data)->assertStatus(200);
    
    $this->assertDatabaseHas('m_jenis_barang', $data);
});

it('can delete a JenisBarang', function () {
    $jenisBarang = JenisBarang::factory()->create();
    $this->deleteJson('/api/jenis-barangs/' . $jenisBarang->id)->assertStatus(204);
    $this->assertSoftDeleted('m_jenis_barang', ['id' => $jenisBarang->id]);
});
