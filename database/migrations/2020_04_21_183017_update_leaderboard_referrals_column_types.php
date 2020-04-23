<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeaderboardReferralsColumnTypes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('leaderboard_referrals', function (Blueprint $table) {
      $table->text('user_agent')->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('leaderboard_referrals', function (Blueprint $table) {
      $table->string('user_agent')->change();
    });
  }
}
