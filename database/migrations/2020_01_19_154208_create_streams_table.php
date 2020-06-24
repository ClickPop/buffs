<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('streams', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('user_id')->unsigned()->nullable();
      $table->bigInteger('platform_id')->unsigned()->nullable();
      $table->string('channel_name');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

      $table->foreign('platform_id')
        ->references('id')
        ->on('platforms')
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
    Schema::table('streams', function (Blueprint $table) {
      $table->dropForeign(['user_id']);
      $table->dropForeign(['platform_id']);
    });
    Schema::dropIfExists('streams');
  }
}
