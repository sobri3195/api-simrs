<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('roles', 'permissions')->latest()->get();

        return ApiResponse::success([
            'total' => $users->count(),
            'items' => $users,
        ], 'Daftar user berhasil diambil');
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);

        $user->assignRole($payload['role']);

        return ApiResponse::created([
            'user' => $user->load('roles', 'permissions'),
        ], 'User berhasil dibuat');
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $payload = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->syncRoles([$payload['role']]);

        return ApiResponse::success([
            'user' => $user->load('roles', 'permissions'),
        ], 'Role user berhasil diubah');
    }

    public function roles(): JsonResponse
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get();

        return ApiResponse::success([
            'total' => $roles->count(),
            'items' => $roles,
        ], 'Daftar role berhasil diambil');
    }
}
