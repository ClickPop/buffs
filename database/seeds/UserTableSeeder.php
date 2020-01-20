<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
Use Illuminate\Support\Str;

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
        $user_sean->username = env('USERS_SEAN_EMAIL');
        $user_sean->password = Str::random(24);
        $user_sean->save();
        $user_sean->roles()->attach($role_admin);
        $user_sean->roles()->attach($role_streamer);

        $user_graham = new User();
        $user_graham->name = env('USERS_GRAHAM_NAME');
        $user_graham->email = env('USERS_GRAHAM_EMAIL');
        $user_graham->username = env('USERS_GRAHAM_USERNAME');
        $user_graham->password = Str::random(24);
        $user_graham->save();
        $user_graham->roles()->attach($role_admin);
        $user_graham->roles()->attach($role_streamer);

        $user_chris = new User();
        $user_chris->name = env('USERS_CHRIS_NAME');
        $user_chris->email = env('USERS_CHRIS_EMAIL');
        $user_chris->username = env('USERS_CHRIS_USERNAME');
        $user_chris->password = Str::random(24);
        $user_chris->save();
        $user_chris->roles()->attach($role_admin);
        $user_chris->roles()->attach($role_streamer);
    }
}
