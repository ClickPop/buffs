<?php

use Illuminate\Database\Seeder;
use App\LeaderboardTheme;

class LeaderboardThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_theme = LeaderboardTheme::where('class', 'default')->first();
        if (!$default_theme) {
            $default_theme = LeaderboardTheme::create([
                'class' => 'default',
                'name' => 'Default',
                'description' => 'Default Leaderboard Theme'
            ]);
        }

        DB::table('leaderboards')
            ->where('theme_id', null)
            ->update(['theme_id' => $default_theme->id]);
    }
}
