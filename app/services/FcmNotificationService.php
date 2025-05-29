<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    public function sendToMultipleDevices(array $tokens, string $title, string $body)
    {
        $projectId = 'smarthome-77eb6';
        $credentialsFilePath = storage_path('app/json/file.json');

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];
        
        $responses = [];
        $errors = [];

        foreach ($tokens as $fcm) {
            $data = [
                "message" => [
                    "token" => $fcm,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                ]
            ];
            $payload = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);
            Log::error($response);

            if ($err) {
                $errors[] = [
                    'token' => $fcm,
                    'error' => $err
                ];
            } else {
                $responses[] = [
                    'token' => $fcm,
                    'response' => json_decode($response, true)
                ];
            }
        }

        return [
            'successful' => $responses,
            'errors' => $errors
        ];
    }
}