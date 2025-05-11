<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\AuthException;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\DeviceResource;
use App\Models\Devices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevicesController extends BaseController
{
    
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
        $device_room = $request->input('room_id');
        $device_image = $request->input('images');

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
        $device->update($validated);
        $response=new DeviceResource($device);
        return $this->sendResponse($response,'Device updated successfully');

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
