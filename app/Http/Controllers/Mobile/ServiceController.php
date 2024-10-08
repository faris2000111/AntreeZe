<?php

namespace App\Http\Controllers\Mobile;

use Exception;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class ServiceController extends Controller
{
    //
    public function getServiceList()
    {
        try {
            $token = 'f3v5aw8OTUalJG-u_9JUFp:APA91bHo9_xpKlpRr1M8rc0U3W40U4VLp2-Ny8bfz6Gabilj5JYoyjrgShBmLf7u-pYpUJ5ymDS3axWKojf__QMRkehNlod-J1FIsV3iT_aD9PW8wxzSTgs2l6rCM-BNhJD9JJD9rB6V';
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create('Status Pesanan', 'Pesanan Booking anda '))
                ->withData([
                    'status' => "turu",
                    'alesan' => "tes",
                ]);
            $messaging = app('firebase.messaging');
            $messaging->send($message);


            $services = Service::all();
            return ResponseFormatter::success(['message' => 'The service list was successfully retrieved', 'data' => $services], "Succes get list");
        } catch (Exception $error) {

            return ResponseFormatter::error(['message' => 'The service list failed to retrieve', 'error' => $error], 'Failed', 500);
        }
    }

    public function getservicelistbyid($id)
    {
        try {
            $service = Service::find($id);
            if (!$service) {
                return ResponseFormatter::error(['message' => 'service not found',], 'Failed', 404);
            }

            return ResponseFormatter::success(['message' => 'service found successfully', 'data' => $service], "Succes get service");
        } catch (Exception $error) {

            return ResponseFormatter::error(['message' => 'The service failed to retrieve', 'error' => $error], 'Failed', 500);
        }
    }
}
