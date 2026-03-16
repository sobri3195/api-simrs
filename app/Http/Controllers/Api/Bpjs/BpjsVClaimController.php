<?php

namespace App\Http\Controllers\Api\Bpjs;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\BridgingV3;
use Illuminate\Http\Request;

class BpjsVClaimController extends Controller
{
    public function peserta(Request $request, BridgingV3 $bpjs)
    {
        // 1. Nomor Kartu
        // 2. Nomor KTP
        $request->validate([
            'nomor' => ['required'],
            'tipe' => ['nullable', 'in:1,2'],
        ]);

        $ok = $bpjs->queryGetPeserta(
            $request->nomor,
            (int) ($request->tipe ?? 1)
        )->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function cariSep(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'no_sep' => ['required'],
        ]);

        $ok = $bpjs->querySearchSEP($request->no_sep)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function riwayatSep(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'no_kartu' => ['required'],
        ]);

        $ok = $bpjs->queryGetRiwayatSEP($request->no_kartu)->exec();


        dd($ok);
        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function insertSep(Request $request, BridgingV3 $bpjs)
    {
        $data = $request->all();

        $ok = $bpjs->queryInsertSEP($data)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'SEP berhasil dibuat',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function updateSep(Request $request, BridgingV3 $bpjs)
    {
        $data = $request->all();

        $ok = $bpjs->queryUpdateSEP($data)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'SEP berhasil diupdate',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function hapusSep(Request $request, BridgingV3 $bpjs)
    {
        $data = $request->all();

        $ok = $bpjs->queryHapusSEP($data)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'SEP berhasil dihapus',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function monitoringKlaim(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required'],
            'status' => ['required'],

        ]);

        // dd($request);

        $ok = $bpjs->monitoringKlaim($request->tanggal, $request->tipe, $request->status)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function monitoringKunjungan(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required'],
        ]);

        $ok = $bpjs->monitoringKunjungan($request->tanggal, $request->tipe)->exec();

        if (!$ok) {
            return response()->json([
                'metaData' => [
                    'code' => (string) ($bpjs->getError()['code'] ?? 400),
                    'message' => $bpjs->getError()['message'] ?? 'Gagal mengambil data peserta',
                ],
                'response' => (object) [],
            ], (int) ($bpjs->getError()['code'] ?? 400));
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }

    public function historyPelayananPeserta(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'no_kartu' => ['required'],
        ]);

        $ok = $bpjs->queryHistoryPelayananPeserta($request->no_kartu)->exec();

        if (!$ok) {
            return ApiResponse::error($bpjs->getError()['message'], (int) $bpjs->getError()['code']);
        }

        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => $bpjs->getResponse(),
        ], 200);
    }
}
