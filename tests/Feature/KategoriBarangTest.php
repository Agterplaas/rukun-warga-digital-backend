<?php

use App\Models\Master\KategoriBarang;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

it('can get a list of KategoriBarang', function () {
    $response = $this->get('/api/kategori-barangs');

    $response->assertStatus(200);
});

it('can create a KategoriBarang', function () {
    $data = [
			'nama' => $this->faker->text(255),
			'created_by' => $this->faker->numberBetween(1, 10),
			'updated_by' => $this->faker->numberBetween(1, 10),
		];
    $this->postJson('/api/kategori-barangs', $data)->assertStatus(201);
    $this->assertDatabaseHas('m_kategori_barang', $data);
});

it('can fetch a KategoriBarang', function () {
    $kategoriBarang = KategoriBarang::factory()->create();
    
    $this->getJson('/api/kategori-barangs/' . $kategoriBarang->id)->assertStatus(200);
});

it('can update a KategoriBarang', function () {
    $kategoriBarang = KategoriBarang::factory()->create();
    
    $data = [
			'nama' => $this->faker->text(255),
			'created_by' => $this->faker->numberBetween(1, 10),
			'updated_by' => $this->faker->numberBetween(1, 10),
		];
    
    $this->putJson('/api/kategori-barangs/' . $kategoriBarang->id, $data)->assertStatus(200);
    
    $this->assertDatabaseHas('m_kategori_barang', $data);
});

it('can delete a KategoriBarang', function () {
    $kategoriBarang = KategoriBarang::factory()->create();
    $this->deleteJson('/api/kategori-barangs/' . $kategoriBarang->id)->assertStatus(204);
    $this->assertSoftDeleted('m_kategori_barang', ['id' => $kategoriBarang->id]);
});
