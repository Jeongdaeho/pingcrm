<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create default Roles
        $root = Role::findOrCreate('root');
        $admin = Role::findOrCreate('admin');
        $user = Role::findOrCreate('user');

        $permissions[] = Permission::findOrCreate('edit users');

        collect($permissions)->map(function ($permission) use ($admin){
            $admin->givePermissionTo($permission);
        });

        $admin->syncPermissions($permissions);
    }
}
