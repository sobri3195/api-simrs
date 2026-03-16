<?php

namespace App\Http\Controllers\Api\Bpjs;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\BridgingV3;
use Illuminate\Http\Request;

class BpjsReferensiController extends Controller
{
    public function poli(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'keyword' => ['required'],
        ]);

        $ok = $bpjs->queryGetListPoli($request->keyword)->exec();

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

    public function diagnosa(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'keyword' => ['required'],
        ]);

        $ok = $bpjs->queryGetListDiagnosa($request->keyword)->exec();

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

    public function faskes(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'keyword' => ['required'],
            'tipe' => ['nullable', 'in:1,2'],
        ]);

        $ok = $bpjs->queryGetListFaskes(
            $request->keyword,
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

    public function dokterDpjp(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'kode' => ['required'],
            'jenis' => ['required'],
            'tgl' => ['required', 'date'],
        ]);

        $ok = $bpjs->queryGetDokterDpjp(
            $request->kode,
            $request->jenis,
            $request->tgl
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

    public function provinsi(BridgingV3 $bpjs)
    {
        $ok = $bpjs->queryGetProvinsi()->exec();

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

    public function kabupaten(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'prov' => ['required'],
        ]);

        $ok = $bpjs->queryGetKabupaten($request->prov)->exec();

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

    public function kecamatan(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'kab' => ['required'],
        ]);

        $ok = $bpjs->queryGetKecamatan($request->kab)->exec();

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

    public function prosedur(Request $request, BridgingV3 $bpjs)
    {
        $request->validate([
            'keyword' => ['required'],
        ]);

        $ok = $bpjs->getProsedur($request->keyword)->exec();

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
