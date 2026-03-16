<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(mixed $data = [], string $message = 'Sukses', int $httpCode = 200)
    {
        return response()->json([
            'metaData' => [
                'code' => (string) $httpCode,
                'message' => $message,
            ],
            'response' => $data,
        ], $httpCode);
    }

    public static function created(mixed $data = [], string $message = 'Data berhasil dibuat')
    {
        return response()->json([
            'metaData' => [
                'code' => '201',
                'message' => $message,
            ],
            'response' => $data,
        ], 201);
    }

    public static function error(string $message = 'Terjadi kesalahan', int $httpCode = 500, mixed $data = null)
    {
        return response()->json([
            'metaData' => [
                'code' => (string) $httpCode,
                'message' => $message,
            ],
            'response' => $data,
        ], $httpCode);
    }

    public static function empty(string $message = 'Sukses')
    {
        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => $message,
            ],
            'response' => (object) [],
        ], 200);
    }
}
