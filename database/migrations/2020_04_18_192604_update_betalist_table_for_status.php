<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBetaListTableForStatus extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('betalist', function (Blueprint $table) {
      $table->enum('current_status', ['pending', 'approved', 'denied'])->after('email')->default('pending');
      $table->bigInteger('updated_by')->unsigned()->nullable()->after('created_by');
      $table->foreign('updated_by')
        ->references('id')
        ->on('users')
        ->onDelete('set null');
    });
    \DB::statement("UPDATE betalist SET current_status = DEFAULT(current_status)");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('betalist', function (Blueprint $table) {
      $table->dropForeign(['updated_by']);
      $table->dropColumn(['current_status', 'updated_by']);
    });
  }
}
