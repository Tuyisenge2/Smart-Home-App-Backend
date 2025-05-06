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
        $scenes = Auth::user()->scenes;
        return response()->json(['scenes' => $scenes]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'days_of_week' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'send_notification' => 'boolean',
            'device_states' => 'required|array',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $scene = Auth::user()->scenes()->create($request->all());
        return response()->json(['scene' => $scene], 201);
    }

    public function show(Scene $scene)
    {
        if ($scene->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json(['scene' => $scene]);
    }

    public function update(Request $request, Scene $scene)
    {
        if ($scene->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'days_of_week' => 'array',
            'start_time' => 'date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'send_notification' => 'boolean',
            'device_states' => 'array',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $scene->update($request->all());
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
