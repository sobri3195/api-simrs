<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SystemInfoController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'application' => 'SIMRS API',
            'environment' => config('app.env'),
            'php_version' => PHP_VERSION,
            'server_time_utc' => now('UTC')->toIso8601String(),
            'timezone' => config('app.timezone'),
        ], 'Informasi sistem API berhasil diambil');
    }
}
