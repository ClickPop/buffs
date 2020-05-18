<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstReferralsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('first_referrals', function (Blueprint $table) {
      $table->id();
      $table->string('referrer');
      $table->unsignedBigInteger('leaderboard_id')->nullable();
      $table->boolean('acknowledged')->default(false);
      $table->timestamps();

      $table->foreign('leaderboard_id')
        ->references('id')
        ->on('leaderboards')
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
    Schema::dropIfExists('first_referrals');
  }
}
