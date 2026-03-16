<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClinicalAiControllerTest extends TestCase
{
    public function test_can_generate_triage_suggestion(): void
    {
        $response = $this->postJson('/api/v1/ai/triage-suggestion', [
            'symptoms' => ['sesak berat', 'nyeri dada'],
            'pain_scale' => 8,
            'spo2' => 88,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('metaData.code', '200')
            ->assertJsonPath('response.urgency', 'tinggi');
    }

    public function test_can_detect_claim_anomaly(): void
    {
        $response = $this->postJson('/api/v1/ai/claim-anomaly-detection', [
            'claims' => [
                [
                    'claim_id' => 'CLM-1001',
                    'amount' => 1700000,
                    'historical_average' => 900000,
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('metaData.code', '200')
            ->assertJsonPath('response.anomaly_count', 1);
    }

    public function test_validation_error_when_triage_payload_is_invalid(): void
    {
        $response = $this->postJson('/api/v1/ai/triage-suggestion', [
            'symptoms' => [],
            'pain_scale' => 99,
            'spo2' => 40,
        ]);

        $response->assertStatus(422);
    }


    public function test_can_access_new_ai_feature_endpoints(): void
    {
        $cases = [
            ['/api/v1/ai/mortality-risk-estimate', ['age' => 70, 'comorbidity_count' => 3, 'spo2' => 89]],
            ['/api/v1/ai/sepsis-early-warning', ['heart_rate' => 120, 'respiratory_rate' => 30, 'temperature' => 39]],
            ['/api/v1/ai/telemedicine-suitability', ['needs_physical_exam' => false, 'stable_condition' => true]],
        ];

        foreach ($cases as [$url, $payload]) {
            $response = $this->postJson($url, $payload);

            $response
                ->assertOk()
                ->assertJsonPath('metaData.code', '200');
        }
    }

}
