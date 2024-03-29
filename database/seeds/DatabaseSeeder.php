<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call(BetaListTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PlatformTableSeeder::class);
        $this->call(UserTableSeeder::class);
        
        if (App::Environment() !== 'production') {
            // $this->call(StreamSeeder::class);
            // $this->call(LeaderboardSeeder::class);
            // $this->call(LeaderboardReferralSeeder::class);
        }
    }
}
