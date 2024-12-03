<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationHelper
{
    /**
     * Send Firebase Cloud Message
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
    public static function sendNotification(string $token, string $title, string $body, array $data = [])
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $messaging = app('firebase.messaging');
            $messaging->send($message);
        } catch (\Exception $e) {

            // Log the error or handle as needed

            Log::error('Failed to send FCM message: ' . $e->getMessage());
        }
    }
}
