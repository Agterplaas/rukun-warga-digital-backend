<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Http\Controllers\WargaController;
use App\Http\Requests\StoreWargaRequest;
use Database\Factories\WargaFactory;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $controller = new WargaController();

        for ($i = 0; $i < 200; $i++) {
            $requestData = WargaFactory::new()->make()->toArray();
            $request = new StoreWargaRequest($requestData);
            $controller->store($request);
        }
    }
}
