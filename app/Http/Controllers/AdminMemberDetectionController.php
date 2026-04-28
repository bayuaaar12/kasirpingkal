<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class AdminMemberDetectionController extends Controller
{
    public function latest(): JsonResponse
    {
        $event = Cache::get('latest_member_detection');

        return response()->json([
            'success' => true,
            'event' => $event,
        ]);
    }
}
