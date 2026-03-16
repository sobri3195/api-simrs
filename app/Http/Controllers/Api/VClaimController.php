<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VClaimController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'List data VClaim',
            'data' => [],
        ]);
    }
}
