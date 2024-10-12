<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Exception;

class ProfileController extends Controller
{

    public function getProfile()
    {
        try {
            $profile = Profile::find(1);

            if (!$profile) {
                return ResponseFormatter::error(['message' => 'profile not found'], 'Failed', 404);
            }

            return ResponseFormatter::success(['message' => 'profile fount', 'data' => $profile], 'succes get data');
        } catch (Exception $error) {

            return ResponseFormatter::error(['message' => 'The Profile failed to retrieve', 'error' => $error], 'Failed', 500);
        }
    }

    public function getListBanners()
    {
        try {
            $profile = Profile::find(1);
            if (!$profile) {
                return ResponseFormatter::error(['message' => 'profile not found'], 'Failed', 404);
            }

            $banners = [$profile->banner1, $profile->banner2, $profile->banner3];

            // Filter out null values from the banners list
            $listBanner = array_filter($banners, function ($banner) {
                return !is_null($banner);
            });

            return ResponseFormatter::success(['message' => 'profile found', 'data' => array_values($listBanner)], 'success get data');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'ada sesuatu yang error', 'error' => $error], 'Failed', 500);
        }
    }
}
