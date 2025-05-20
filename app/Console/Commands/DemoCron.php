<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scene;
use Carbon\Carbon;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   
   public function handle()
{
    // Set timezone first
    date_default_timezone_set('Africa/Kigali');
    
    $currentHour = now()->hour; // Gets hour in local time (0-23)
    $today = strtolower(now()->englishDayOfWeek);
    
    info("Scene activation check running at ". now()->format('Y-m-d H:i:s'));
    info("Checking scenes for hour {$currentHour} on {$today}");

    // Find scenes to activate
    $scenesToActivate = Scene::all();
    // whereRaw('HOUR(start_time) = ?', [$currentHour - 2]) // UTC is 2 hours behind Kigali
    //     ->whereJsonContains('days_of_week', $today)
    //     ->where('is_active', false)
    //     ->get();

    info("Found ".$scenesToActivate." scenes to activate");

    foreach ($scenesToActivate as $scene) {
        // Activate the scene
        $scene->update(['is_active' => true]);
        
        // Activate all related devices
        $scene->devices()->update(['is_active' => true]);
        
        $this->info("Activated Scene: {$scene->name} (ID: {$scene->id})");
        info("Scene {$scene->id} activated at ".now()->format('H:i'));
    }
    
    // Deactivation logic (using same time adjustment)
    $scenesToDeactivate = Scene::whereRaw('HOUR(end_time) = ?', [$currentHour - 2])
        ->where('is_active', true)
        ->get();

    foreach ($scenesToDeactivate as $scene) {
        $scene->update(['is_active' => false]);
        $scene->devices()->update(['is_active' => false]);
        $this->info("Deactivated Scene: {$scene->name}");
    }
}
   


     
   
   
   
     // public function handle()
    // {
    //     //
    //      info("Cron Job running at ". now());
    //    // 1. Log that the command is running (for verification)
    //     info("Cron Job running at ". now());
        
    //     // 2. Get the first scene from the database
    //     $scene = Scene::first();
        
    //     if ($scene) {
    //         // 3. Generate a new name with timestamp
    //         $newName = "Updated Scene @ " . now()->format('H:i:s');
            
    //         // 4. Update the scene name
    //         $scene->update(['name' => $newName]);
            
    //         // 5. Log what we changed
    //         $this->info("Changed scene ID {$scene->id} name to: {$newName}");
    //         info("Scene {$scene->id} updated to: {$newName}");
    //     } else {
    //         $this->error('No scenes found in database!');
    //         info('Cron Job: No scenes found to update');
    //     }

    // }
}
