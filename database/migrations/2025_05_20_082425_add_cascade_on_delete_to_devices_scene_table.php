<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('devices_scene', function (Blueprint $table) {
            // Drop existing foreign keys first (required before modifying)
            // $table->dropForeign(['Devices_id']);
            // $table->dropForeign(['scene_id']);
      $table->dropColumn('Devices_id');
            $table->dropColumn('scene_id');
            // Recreate with cascade on delete
          
        });
    }

    public function down()
    {
        Schema::table('devices_scene', function (Blueprint $table) {
           
                $table->json('Devices_id')->nullable();
                    $table->json('scene_id')->nullable();
           
            // Reverse the changes if needed
           // $table->dropForeign(['Devices_id']);
            // $table->dropForeign(['scene_id']);

            // // Recreate original foreign keys without cascade
            // $table->foreign('Devices_id')
            //       ->references('id')
            //       ->on('devices');

            // $table->foreign('scene_id')
            //       ->references('id')
            //       ->on('scenes');
        });
    }
};