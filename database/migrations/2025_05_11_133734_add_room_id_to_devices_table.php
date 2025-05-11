<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('devices', function (Blueprint $table) {
           
       $table->foreign('room_id')->references('id')->on('rooms')

        ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('devices', function (Blueprint $table) {
        $table->dropColumn('room_id');
    });
}
};
