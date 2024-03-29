<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super.admin@rw-digital.co.id',
            'password' => 'SuperAdmin#123456',
        ]);

        User::factory()->count(10)->create();
    }
}
