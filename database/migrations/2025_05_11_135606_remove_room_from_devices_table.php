<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('room');        
        });
    }
    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // If you want to make the migration reversible,
            // add back the column in the down() method
            $table->string('room')->nullable();
        });
    }
};
