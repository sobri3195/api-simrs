<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create($payload);
        $user->assignRole('superadmin');

        return ApiResponse::created([
            'user' => $user->load('roles'),
            'token' => $user->createToken('api-token')->plainTextToken,
        ], 'Register berhasil');
    }

    public function login(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $payload['email'])->first();

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        return ApiResponse::success([
            'user' => $user->load('roles', 'permissions'),
            'token' => $user->createToken('api-token')->plainTextToken,
        ], 'Login berhasil');
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(
            $request->user()->load('roles', 'permissions'),
            'Profil pengguna berhasil diambil',
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return ApiResponse::success((object) [], 'Logout berhasil');
    }
}
