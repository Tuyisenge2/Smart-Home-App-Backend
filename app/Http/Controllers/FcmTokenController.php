<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    /**
     * Display a listing of all FCM tokens.
     */
    public function index()
    {
        try {
            $tokens = FcmToken::all();
            return response()->json([
                'success' => true,
                'data' => $tokens,
                'message' => 'FCM tokens retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created FCM token.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255|unique:fcm_tokens,token',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fcmToken = FcmToken::create($request->only('token'));
            return response()->json([
                'success' => true,
                'data' => $fcmToken,
                'message' => 'FCM token stored successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified FCM token.
     */
    public function destroy($token)
    {
        try {
            $fcmToken = FcmToken::where('token', $token)->first();

            if (!$fcmToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found'
                ], 404);
            }

            $fcmToken->delete();
            return response()->json([
                'success' => true,
                'message' => 'FCM token deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}