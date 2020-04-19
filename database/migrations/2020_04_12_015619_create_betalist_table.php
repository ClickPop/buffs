<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetaListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betalist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->bigInteger('created_by')->unsigned()->nullable()->default(null);
            $table->bigInteger('user_id')->unsigned()->nullable()->default(null);
            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::table('betalist', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('betalist');

    }
}
