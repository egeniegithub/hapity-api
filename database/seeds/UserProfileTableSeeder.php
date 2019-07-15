<?php

use Illuminate\Database\Seeder;
use App\UserProfile;

class UserProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userprofile = new UserProfile();

        $userprofile->user_id = '1';
        $userprofile->first_name = 'Super';
        $userprofile->last_name = 'Admin';
        $userprofile->email = 'administrator@example.com';
        $userprofile->gender = 'Male';
        $userprofile->date_of_birth = '1991-12-21';
        $userprofile->age = '27';
        $userprofile->full_name = 'Super Admin';
        $userprofile->save();
    }
}
