<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Super Admin';
        $user->email = 'administrator@example.com';
        $user->username = 'superadmin';
        $user->password = bcrypt('admin125');
        $user->save();
        $user->roles()->attach(SUPER_ADMIN_ROLE_ID);

        $user = new User();
        $user->name = 'Hapity User';
        $user->email = 'user@example.com';
        $user->username = 'Hapity-User';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(HAPITY_USER_ROLE_ID);
    }
}
