<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if( $rootRole = Role::where('name', 'root')->first() ) {
            return;
        }

        if ( !$user = User::where('email', 'root@root.com')->first() ) {
            $user = User::create([
                'name'           => 'root',
                'email'          => 'root@root.com',
                'password'       => bcrypt('password'),
                'remember_token' => Str::random(60),
            ]);
        }

        $user->assignRole('root');
    }
}
