<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use Illuminate\Support\Str;

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
        $user_sean->name = "Sean Metzgar";
        $user_sean->email = "sean.metzgar@gmail.com";
        $user_sean->username = "gelatinous3";
        $user_sean->password = Str::random(24);
        $user_sean->save();
        $user_sean->roles()->attach($role_admin);
        $user_sean->roles()->attach($role_streamer);

        $user_graham = new User();
        $user_graham->name = "Graham Vasquez";
        $user_graham->email = "rescus1221@gmail.com";
        $user_graham->username = "datboi_fourtwenty";
        $user_graham->password = Str::random(24);
        $user_graham->save();
        $user_graham->roles()->attach($role_admin);
        $user_graham->roles()->attach($role_streamer);

        $user_chris = new User();
        $user_chris->name = "Chris Vasquez";
        $user_chris->email = "chris.vqz@gmail.com";
        $user_chris->username = "shorkattack";
        $user_chris->password = Str::random(24);
        $user_chris->save();
        $user_chris->roles()->attach($role_admin);
        $user_chris->roles()->attach($role_streamer);
    }
}
