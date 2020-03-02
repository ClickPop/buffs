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

        $user_sean = User::create([
            'name' => "Sean Metzgar",
            'username' => "Gelatinous3",
            'email' => "sean.metzgar@gmail.com",
            'password' => Str::random(24),
        ]);
        $user_sean->roles()->attach($role_admin);
        $user_sean->roles()->attach($role_streamer);

        $user_graham = User::create([
            'name' => "Graham Vasquez",
            'username' => "datboi_fourtwenty",
            'email' => "rescus1221@gmail.com",
            'password' => Str::random(24),
        ]);
        $user_graham->roles()->attach($role_admin);
        $user_graham->roles()->attach($role_streamer);

        $user_chris = User::create([
            'name' => "Chris Vasquez",
            'username' => "shorkattack",
            'email' => "chris.vqz@gmail.com",
            'password' => Str::random(24),
        ]);
        $user_chris->roles()->attach($role_admin);
        $user_chris->roles()->attach($role_streamer);
    }
}
