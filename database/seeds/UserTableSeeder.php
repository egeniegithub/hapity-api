<?php

use Illuminate\Database\Seeder;
use App\User;

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

        $user->name =  'Administrator';
        $user->email =  'administrator@example.com';
        $user->password =  bcrypt('admin125');
        $user->save();

        $user->roles()->attach('1');
    }
}
