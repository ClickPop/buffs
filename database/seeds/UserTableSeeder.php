<?php

use Illuminate\Database\Seeder;
use App\Role;
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
        $role_admin = Role::where('name', 'admin')->first();
        $role_streamer  = Role::where('name', 'streamer')->first();

        $user_sean = new User();
        $user_sean->name = env('USERS_SEAN_NAME');
        $user_sean->email = env('USERS_SEAN_EMAIL');
        $user_sean->password = Hash::make(env('USERS_SEAN_PASSWORD'));
        $user_sean->save();
        $user_sean->roles()->attach($role_admin);
        $user_sean->roles()->attach($role_streamer);

//        $user_graham = new User();
//        $user_graham->name = env('USERS_GRAHAM_NAME');
//        $user_graham->email = env('USERS_GRAHAM_EMAIL');
//        $user_graham->password = Hash::make(env('USERS_GRAHAM_PASSWORD'));
//        $user_graham->save();
//        $user_graham->roles()->attach($role_admin);
//        $user_graham->roles()->attach($role_streamer);
    }
}
