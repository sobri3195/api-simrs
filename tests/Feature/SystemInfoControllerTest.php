<?php

namespace Tests\Feature;

use Tests\TestCase;

class SystemInfoControllerTest extends TestCase
{
    public function test_system_info_endpoint_returns_runtime_metadata(): void
    {
        $this->getJson('/api/v1/system-info')
            ->assertOk()
            ->assertJsonPath('metaData.code', '200')
            ->assertJsonPath('response.application', 'SIMRS API')
            ->assertJsonPath('response.environment', config('app.env'))
            ->assertJsonPath('response.timezone', config('app.timezone'))
            ->assertJsonStructure([
                'metaData' => ['code', 'message'],
                'response' => [
                    'application',
                    'environment',
                    'php_version',
                    'server_time_utc',
                    'timezone',
                ],
            ]);
    }
}
