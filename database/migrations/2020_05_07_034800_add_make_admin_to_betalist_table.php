<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMakeAdminToBetaListTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('betalist', function (Blueprint $table) {
      $table->boolean('make_admin')->default(false);
    });
    \DB::statement("UPDATE betalist SET make_admin = DEFAULT(make_admin)");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('betalist', function (Blueprint $table) {
      $table->dropColumn(['make_admin']);
    });
  }
}
