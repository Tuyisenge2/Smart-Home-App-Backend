<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\AuthException;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\DeviceResource;
use App\Models\Devices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FcmNotificationService;
use Illuminate\Support\Facades\Log; 
use App\Models\User; 

class DevicesController extends BaseController
{
     protected $fcmService;

       public function __construct(FcmNotificationService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    
    public function index()
    {
        $response=DeviceResource::collection(Devices::all());
        return $this->sendResponse($response,'Device fetched successfully');
    }

   
    public function create()
    {
    }

    public function store(Request $request)
     {
        $validator= Validator::make($request->all(),[
            'name'=>'required',
            'room_id'=>'required',
            
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.',$validator->errors());
        }
       
        $device_name = $request->input('name');
        $device_image = $request->input('images');
         $device_room = $request->input('room_id');

        $device = Devices::create([
            'name' => $device_name,
            'room_id' => $device_room,
            'images' => $device_image,
        ]);
       
          $response=new DeviceResource($device);
        return $this->sendResponse($response,'Device created successfully');
    }

    public function show(Devices $device)
    {
         $response= new DeviceResource($device);
        return $this->sendResponse($response,'Device fetched successfully');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request, Devices $device)
    // {
    //     $device_name = $request->input('name');
    //     $device_room = $request->input('room_id');
    //     $is_active=$request->input('is_active');
    //     $validated=$request->validate([
    //         'name'=>'sometimes|string',
    //         'room_id'=>'sometimes|string',
    //     'is_active' => 'sometimes|boolean' 
    //     ]);
    //       if (isset($validated['is_active'])) {
    //        $validated['is_active'] = $validated['is_active'] ? 1 : 0;
    //         }
    //     $device->update($validated);
    //     $response=new DeviceResource($device);
    //     return $this->sendResponse($response,'Device updated successfully');

    // }

    public function update(Request $request, Devices $device)
    {
        $device_name = $request->input('name');
        $device_room = $request->input('room_id');
        $is_active=$request->input('is_active');


        $validated=$request->validate([
            'name'=>'sometimes|string',
            'room_id'=>'sometimes|string',
            'is_active'=>'sometimes|boolean',
            'images'=>'sometimes|string',            
        ]);
    if (isset($validated['is_active'])) {
        $validated['is_active'] = $validated['is_active'] ? 1 : 0;
    }
    $device->update($validated);
            $users = User::whereNotNull('fcm_token')->get();
              $tokens = $users->pluck('fcm_token')
                       ->filter()
                       ->unique()
                       ->values()
                       ->toArray();
 error_log("Found " . count($tokens) . " FCM tokens");
    if (count($tokens) > 0) {
        error_log("First token: " . $tokens[0]);
    }
 if (isset($validated['is_active'])) {
        $status = $validated['is_active'] ? 'turned ON' : 'turned OFF';
        $notificationTitle = "Device Status Changed";
        $notificationBody = "{$device->name} has been {$status}";
        
        $this->fcmService->sendToMultipleDevices(
            $tokens,
            $notificationTitle,
            $notificationBody
                );
    } else {
        $this->fcmService->sendToMultipleDevices(
            $tokens,
            "Device Updated",
            "{$device->name} has been updated"
        );
    }
    return $this->sendResponse( new DeviceResource($device)   , 'Device updated successfully');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devices $device)
    {
        $device->delete();
        return response()->json(null,204);
    }
}
