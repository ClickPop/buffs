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
            $table->bigInteger('leaderboard_id')->unsinged();
            $table->bigInteger('platform_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('referrer');
            $table->string('ip_address');
            $table->string('userAgent');
            $table->timestamps();

            // $table->foreign('leaderboard_id')
            //     ->references('id')
            //     ->on('leaderboards')
            //     ->onDelete('cascade');

            $table->foreign('platform_id')
                ->references('id')
                ->on('platforms')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('leaderboard_referrals');
    }
}
