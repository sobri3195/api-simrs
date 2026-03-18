<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class VClaimController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'available_endpoints' => [
                'GET /api/v1/bpjs/peserta',
                'GET /api/v1/bpjs/sep',
                'GET /api/v1/bpjs/sep-riwayat',
                'POST /api/v1/bpjs/sep',
                'PUT /api/v1/bpjs/sep',
                'DELETE /api/v1/bpjs/sep',
                'GET /api/v1/bpjs/monitoring-kunjungan',
                'GET /api/v1/bpjs/monitoring-klaim',
            ],
            'referensi' => [
                'poli',
                'diagnosa',
                'faskes',
                'dokter-dpjp',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'prosedur',
            ],
        ], 'Katalog fitur VClaim berhasil diambil');
    }
}
