<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\DeviceResource;
use App\Models\Devices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevicesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response=DeviceResource::collection(Devices::all());
        return $this->sendResponse($response,'Device fetched successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $device_name = $request->input('name');
        $device_room = $request->input('room');
        $device = Devices::create([
            'name' => $device_name,
            'room' => $device_room,
        ]);
        // return response()->json([
        //     'data' => new DeviceResource($product)
        // ], 201);

          $response=new DeviceResource($device);
        return $this->sendResponse($response,'Device created successfully');



    }

    /**
     * Display the specified resource.
     */
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
        $device_room = $request->input('room');

        $validated=$request->validate([
            'name'=>'sometimes|string',
            'room'=>'sometimes|string'
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
