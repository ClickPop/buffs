<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $role_admin = Role::create([
            'name' => 'admin',
            'description' => 'Administrator'
        ]);
        $role_streamer = Role::create([
            'name' => 'streamer',
            'description' => 'Streamer'
        ]);
    }
}
