<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_standardized_response(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Operator SIMRS',
            'email' => 'operator@example.com',
            'password' => 'secret123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('metaData.code', '201')
            ->assertJsonPath('metaData.message', 'Register berhasil')
            ->assertJsonStructure([
                'metaData' => ['code', 'message', 'timestamp', 'request_id'],
                'response' => ['user', 'token'],
            ]);
    }
}
