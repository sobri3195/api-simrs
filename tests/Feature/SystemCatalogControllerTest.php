<?php

namespace Tests\Feature;

use Tests\TestCase;

class SystemCatalogControllerTest extends TestCase
{
    public function test_dashboard_endpoint_returns_module_summary(): void
    {
        $this->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('metaData.code', '200')
            ->assertJsonPath('response.application', 'SIMRS API')
            ->assertJsonCount(5, 'response.modules');
    }

    public function test_antrol_reference_endpoints_are_available(): void
    {
        $this->getJson('/api/v1/antrol/poli')
            ->assertOk()
            ->assertJsonPath('metaData.code', '200')
            ->assertJsonPath('response.0.kode', 'INT');

        $this->getJson('/api/v1/antrol/dokter')
            ->assertOk()
            ->assertJsonPath('response.1.kode', 'D002');

        $this->getJson('/api/v1/antrol/jadwal-dokter')
            ->assertOk()
            ->assertJsonPath('response.2.hari', 'Rabu');
    }

    public function test_catalog_endpoints_for_apotek_and_vclaim_are_available(): void
    {
        $this->getJson('/api/v1/apotek')
            ->assertOk()
            ->assertJsonPath('response.summary.ready_to_dispense', 11);

        $this->getJson('/api/v1/vclaim')
            ->assertOk()
            ->assertJsonPath('response.available_endpoints.0', 'GET /api/v1/bpjs/peserta');
    }
}
