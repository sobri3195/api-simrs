<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApotekController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'List data Apotek',
            'data' => [],
        ]);
    }
}
