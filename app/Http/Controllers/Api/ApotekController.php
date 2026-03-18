<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApotekController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'summary' => [
                'active_prescriptions' => 18,
                'ready_to_dispense' => 11,
                'needs_compounding' => 3,
            ],
            'services' => [
                'validasi resep',
                'dispensing obat',
                'monitoring stok kritis',
            ],
        ], 'Ringkasan modul apotek berhasil diambil');
    }
}
