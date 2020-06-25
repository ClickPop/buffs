<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('platforms', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('socialite_driver')->unique();
      $table->string('name')->unique();
      $table->string('description');
      $table->string('channel_url_structure');
      $table->string('url');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('platforms');
  }
}
