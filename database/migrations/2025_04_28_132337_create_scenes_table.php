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
        Schema::create('scenes', function (Blueprint $table) {
            $table->id();
            $table->string('name');  
            $table->json('days_of_week'); 
            $table->time('start_time');   
            $table->time('end_time')->nullable(); 
            $table->boolean('send_notification')->default(false); 
            $table->json('device_states'); 
            $table->boolean('is_active')->default(true); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenes');
    }
};
