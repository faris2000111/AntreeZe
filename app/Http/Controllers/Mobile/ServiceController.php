<?php

namespace App\Http\Controllers\Mobile;


use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Exception;

class ServiceController extends Controller
{
    //
    public function getServiceList()
    {
        try {
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
