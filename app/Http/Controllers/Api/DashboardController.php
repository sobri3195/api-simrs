<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'application' => 'SIMRS API',
            'version' => 'v1',
            'modules' => [
                [
                    'name' => 'BPJS VClaim',
                    'prefix' => '/api/v1/bpjs',
                    'status' => 'active',
                    'feature_count' => 20,
                ],
                [
                    'name' => 'AI Clinical Assistant',
                    'prefix' => '/api/v1/ai',
                    'status' => 'active',
                    'feature_count' => 35,
                ],
                [
                    'name' => 'Antrol RS',
                    'prefix' => '/api/v1/antrol',
                    'status' => 'active',
                    'feature_count' => 4,
                ],
                [
                    'name' => 'SatuSehat',
                    'prefix' => '/api/v1/satu-sehat',
                    'status' => 'active',
                    'feature_count' => 2,
                ],
                [
                    'name' => 'Authentication & User Management',
                    'prefix' => '/api/v1/auth',
                    'status' => 'active',
                    'feature_count' => 7,
                ],
            ],
        ], 'Ringkasan dashboard API berhasil diambil');
    }
}
