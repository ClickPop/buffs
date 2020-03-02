<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaderboardReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaderboard_referrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('leaderboard_id')->unsigned();
            $table->string('referrer');
            $table->bigInteger('referrer_id')->unsigned()->nullable(); // Use to record associated User/Stream if they are an active user
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('leaderboard_id')
                ->references('id')
                ->on('leaderboards')
                ->onDelete('cascade');

            $table->foreign('referrer_id')
                ->references('id')
                ->on('streams')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaderboard_referrals');
    }
}
