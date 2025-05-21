<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SceneController extends BaseController
{
 
    public function index()
    {
        $scenes = Scene::with('devices')->get();
        return response()->json(['scenes' => $scenes]);

    //     return Room::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'days_of_week' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'send_notification' => 'boolean',
            'is_active' => 'boolean',
            'devices'=>'required|array',
            'devices.*'=>'exists:devices,id'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $scene = Auth::user()->scenes()->create($request->except('devices'));
        $scene->devices()->attach($request->devices);   
        return response()->json(['scene' => $scene], 201);
    }

    public function show(Scene $scene)
    {
        if ($scene->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
          $scene->load('devices');
        return response()->json(['scene' => $scene]);
    }

    // public function update(Request $request, Scene $scene)
    // {
    //     if ($scene->user_id !== Auth::id()) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'string|max:255',
    //         'days_of_week' => 'array',
    //         'start_time' => 'date_format:H:i',
    //         'end_time' => 'nullable|date_format:H:i|after:start_time',
    //         'send_notification' => 'boolean',
    //         'is_active' => 'boolean',
    //                 'devices'=>'sometimes|array',
    //         'devices.*'=>'exists:devices,id'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $scene->update($request->all());
    //       $scene->load('devices');
    //     return response()->json(['scene' => $scene]);
    // }

    public function update(Request $request, Scene $scene)
{
    // if ($scene->user_id !== Auth::id()) {
    //     return response()->json(['message' => 'Unauthorized'], 403);
    // }

    $validator = Validator::make($request->all(), [
        'name' => 'string|max:255',
        'days_of_week' => 'array',
        'start_time' => 'date_format:H:i',
        'end_time' => 'nullable|date_format:H:i|after:start_time',
        'send_notification' => 'boolean',
        'is_active' => 'boolean',
        'devices' => 'sometimes|array',
        'devices.*' => 'exists:devices,id'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Check if is_active is being updated
    $isActiveChanged = $request->has('is_active');
         //&&  $scene->is_active != $request->is_active;

    $scene->update($request->all());

    // Update related devices if is_active changed
    if ($isActiveChanged) {
        $scene->devices()->update(['is_active' => $scene->is_active]);
    }

    // Update device associations if provided
    // if ($request->has('devices')) {
    //     $scene->devices()->sync($request->devices);
    // }

    $scene->load('devices');
    return response()->json(['scene' => $scene]);
}
    public function destroy(Scene $scene)
    {
        if ($scene->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $scene->delete();
        return response()->json(['message' => 'Scene deleted successfully']);
    }
} 
