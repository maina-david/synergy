<?php

namespace Database\Seeders;

use App\Enums\Admin\Role as RoleEnum;
use App\Models\Administration\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $organization = Organization::factory()
            ->owner()
            ->has(User::factory()->count(1))
            ->create([
                'name' => 'Default Organization',
            ]);

        $user = $organization->users->first();

        $user->assignRole(RoleEnum::OWNER);
    }
}