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
            // Check if column exists before trying to drop it
            if (Schema::hasColumn('devices', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            // Recreate the column when rolling back
            $table->boolean('is_active')->default(false);
        });
    }
};