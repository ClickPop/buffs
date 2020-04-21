<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaderboardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('leaderboards', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('stream_id')->unsigned()->nullable();
      $table->string('name')->default('Referral Leaderboard');
      $table->string('theme')->default('light');
      $table->integer('length')->default(10);
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('stream_id')
        ->references('id')
        ->on('streams')
        ->onDelete('cascade');
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
      $table->dropForeign(['stream_id']);
    });
    Schema::dropIfExists('leaderboards');
  }
}
