<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['role_name' => 'owner']);
        Role::create(['role_name' => 'admin']);
        Role::create(['role_name' => 'customer']);

        $this->command->info('Roles seeded successfully.');
    }
}
