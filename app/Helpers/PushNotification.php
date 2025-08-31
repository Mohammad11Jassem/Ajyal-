<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PushNotification
{
    public static function sendNotification($message,$fcm_token)
    {
        try {
            $apiUrl = 'https://fcm.googleapis.com/v1/projects/ajyal-45f04/messages:send';

            $access_token = Cache::remember('access_token', now()->addHour(), function () {
                $credentialsFilePath = storage_path('app/fcm.json');
                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                return $token['access_token'];
            });


            // $fcm_token = 'fUq3em0xR0CY08GLllSMNd:APA91bF21K4QKiPwb_9TWLd6SCvy0nW5gmEMdRdmFx5qZ6la08kvxZ3fnZdL8luji4XKqsn_qm_iAVuRHwGk1r89atbKsCgtp__MwFi6-_C4SYkXxqwKlxU';

            $message = [
                "message" => [
                    "token" => $fcm_token,
                    "notification" => [
                        "title" => $message['title'],
                        "body" => $message['body'],
                    ]
                ]
            ];

            $response = Http::withToken($access_token)
                ->post($apiUrl, $message);

            // dd($response); // dump API response so you can see result

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
    }
}
