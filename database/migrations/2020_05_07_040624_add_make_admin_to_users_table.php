<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMakeAdminToUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->enum('login_action',  ['promote-streamer', 'promote-admin', 'demote-streamer', 'demote-admin'])->nullable()->default(null);
    });
    \DB::statement("UPDATE users SET login_action = DEFAULT(login_action)");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['login_action']);
    });
  }
}
