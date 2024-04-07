<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $owner = User::create([
                'name'     => 'Owner',
                'email'    => 'owner@gmail.com',
                'password' => 'owner1234'
            ]);

            $role1 = Role::where('role_name', 'owner')->first();
            $role2 = Role::where('role_name', 'admin')->first();
            $role3 = Role::where('role_name', 'customer')->first();
            $roles = [$role1->id, $role2->id, $role3->id];

            DB::commit();
            $owner->roles()->attach($roles);
            $this->command->info('User seeded successfully.');
        } catch (\Throwable $th) {
            Log::debug($th);
            DB::rollBack();
            $this->command->info('User dide=n\'t seeded.');
        }
    }
}
