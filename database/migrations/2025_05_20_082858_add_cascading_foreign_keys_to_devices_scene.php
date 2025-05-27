<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('devices_scene', function (Blueprint $table) {
            // Add new columns with proper foreign keys
            $table->foreignId('Devices_id')
                  ->constrained('devices')  // Explicit table name
                  ->cascadeOnDelete();
                  
            $table->foreignId('scene_id')
                  ->constrained('scenes')  // Explicit table name
                  ->cascadeOnDelete();
                  
        });
    }

    public function down()
    {
        Schema::table('devices_scene', function (Blueprint $table) {
            // Remove the foreign keys first
            $table->dropForeign(['Devices_id']);
            $table->dropForeign(['scene_id']);
            
            // Then drop the columns
            $table->dropColumn('Devices_id');
            $table->dropColumn('scene_id');
        });
    }
};