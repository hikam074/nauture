<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    public function sendNotification($title, $message, $url = null)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.config('onesignal.rest_api_key'),
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('onesignal.app_id'),
            'included_segments' => ['All'],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'url' => $url,
        ]);

        if ($response->failed()) {
            Log::error('OneSignal Error: ' . $response->body());
            return ['error' => $response->body()];
        }

        return $response->json();
    }
}
