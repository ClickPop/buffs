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
      $initialBetaList = [];
      $seanBeta = [
        "email" => "sean.metzgar@gmail.com",
        "current_status" => "approved",
        "make_admin" => false
      ];
      array_push($initialBetaList, $seanBeta);
      $chrisBeta = [
        "email" => "chris.vqz@gmail.com",
        "current_status" => "approved",
        "make_admin" => true
      ];
      array_push($initialBetaList, $chrisBeta);
      $grahamBeta = [
        "email" => "rescus1221@gmail.com",
        "current_status" => "approved",
        "make_admin" => true
      ];
      array_push($initialBetaList, $grahamBeta);

      foreach ($initialBetaList as $betaUser) {
          $tempBetaUser = BetaList::create($betaUser);
      }
    }
}
