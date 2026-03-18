<?php

namespace App\Http\Controllers\Api\Antrol;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AntreanRsController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success([
            'summary' => [
                'total_queue' => 42,
                'served' => 28,
                'remaining' => 14,
                'average_wait_minutes' => 26,
            ],
        ], 'Ringkasan antrean RS berhasil diambil');
    }

    public function poli(): JsonResponse
    {
        return ApiResponse::success([
            ['kode' => 'INT', 'nama' => 'Poli Penyakit Dalam'],
            ['kode' => 'ANK', 'nama' => 'Poli Anak'],
            ['kode' => 'JTG', 'nama' => 'Poli Jantung'],
        ], 'Daftar poli berhasil diambil');
    }

    public function dokter(): JsonResponse
    {
        return ApiResponse::success([
            ['kode' => 'D001', 'nama' => 'dr. Andi Saputra, Sp.PD', 'poli' => 'INT'],
            ['kode' => 'D002', 'nama' => 'dr. Siti Rahma, Sp.A', 'poli' => 'ANK'],
            ['kode' => 'D003', 'nama' => 'dr. Budi Pranata, Sp.JP', 'poli' => 'JTG'],
        ], 'Daftar dokter berhasil diambil');
    }

    public function jadwalDokter(): JsonResponse
    {
        return ApiResponse::success([
            [
                'kode_dokter' => 'D001',
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
            ],
            [
                'kode_dokter' => 'D002',
                'hari' => 'Selasa',
                'jam_mulai' => '09:00',
                'jam_selesai' => '13:00',
            ],
            [
                'kode_dokter' => 'D003',
                'hari' => 'Rabu',
                'jam_mulai' => '10:00',
                'jam_selesai' => '14:00',
            ],
        ], 'Jadwal dokter berhasil diambil');
    }
}
