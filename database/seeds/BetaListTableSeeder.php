<?php

use Illuminate\Database\Seeder;
use App\BetaList;

class BetaListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $initialBetaList = array(
            // "sean.metzgar@gmail.com",
            "rescus1221@gmail.com",
            "chris.vqz@gmail.com"
        );

        foreach ($initialBetaList as $betaUser) {
            $tempBetaUser = BetaList::create(['email' => $betaUser]);
        }
    }
}
