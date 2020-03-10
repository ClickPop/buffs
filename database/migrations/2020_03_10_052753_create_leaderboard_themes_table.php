<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreateLeaderboardThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaderboard_themes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('class')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->timestamps();
        });

        Schema::table('leaderboards', function (Blueprint $table) {
            $table->unsignedSmallInteger('theme_id')->nullable()->after('stream_id');

            $table->foreign('theme_id')
                ->references('id')
                ->on('leaderboard_themes')
                ->onDelete('set null');
        });

        Artisan::call( 'db:seed', [
            '--class' => 'LeaderboardThemeSeeder',
            '--force' => true ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaderboards', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropColumn(['theme_id']);
        });

        Schema::dropIfExists('leaderboard_themes');
    }
}
