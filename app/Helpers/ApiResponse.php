<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ApiResponse
{
    public static function success(mixed $data = [], string $message = 'Sukses', int $httpCode = 200): JsonResponse
    {
        return self::buildResponse($httpCode, $message, $data);
    }

    public static function created(mixed $data = [], string $message = 'Data berhasil dibuat'): JsonResponse
    {
        return self::buildResponse(201, $message, $data);
    }

    public static function error(string $message = 'Terjadi kesalahan', int $httpCode = 500, mixed $data = null): JsonResponse
    {
        return self::buildResponse($httpCode, $message, $data);
    }

    public static function empty(string $message = 'Sukses'): JsonResponse
    {
        return self::buildResponse(200, $message, (object) []);
    }

    private static function buildResponse(int $httpCode, string $message, mixed $data): JsonResponse
    {
        return response()->json([
            'metaData' => [
                'code' => (string) $httpCode,
                'message' => $message,
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-Id', (string) Str::uuid()),
            ],
            'response' => $data,
        ], $httpCode);
    }
}
