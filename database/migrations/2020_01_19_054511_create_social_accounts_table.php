<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialAccountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('social_accounts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('user_id')->unsigned();
      $table->bigInteger('platform_id')->unsigned();
      $table->string('platform_user_id');
      $table->string('token')->nullable()->default(null);
      $table->string('tokenSecret')->nullable()->default(null);
      $table->string('refreshToken')->nullable()->default(null);
      $table->timestamp('expires')->nullable()->default(null);
      $table->timestamps();

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
    Schema::table('social_accounts', function (Blueprint $table) {
      $table->dropForeign(['user_id']);
      $table->dropForeign(['platform_id']);
    });
    Schema::dropIfExists('social_accounts');
  }
}
