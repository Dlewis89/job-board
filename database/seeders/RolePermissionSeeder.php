<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'admin',
            'employer',
            'candidate'
        ];

        $role = Role::firstOrCreate(['name' => 'super-admin']);

        foreach($permissions as $permission) {
            $role = Permission::firstOrCreate(['name' => $permission]);
        }

        if($role) {
            $role->syncPermissions($permissions);
        }
    }
}
