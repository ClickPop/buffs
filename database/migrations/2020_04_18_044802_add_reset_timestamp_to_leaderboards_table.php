<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResetTimestampToLeaderboardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('leaderboards', function (Blueprint $table) {
      $table->timestamp('reset_timestamp')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('leaderboards', function (Blueprint $table) {
      $table->dropColumn(['reset_timestamp']);
    });
  }
}
