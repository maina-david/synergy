<?php

namespace Database\Seeders;

use App\Enums\Admin\Role as RoleEnum;
use App\Models\Administration\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $organization = Organization::factory()
            ->owner()
            ->corporate()
            ->has(
                User::factory()
                    ->count(1)
                    ->state([
                        'email' => 'admin@example.com'
                    ])
            )
            ->create([
                'name' => 'Default Organization',
            ]);

        $user = $organization->users->first();

        $ownerRole = Role::create(['name' => RoleEnum::OWNER]);

        $OrgAdminRole = Role::create(['name' => RoleEnum::ORG_ADMIN]);

        $user->assignRole($ownerRole);
    }
}