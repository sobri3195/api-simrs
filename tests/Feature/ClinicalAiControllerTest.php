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
            ->assertJsonPath('response.urgency', 'tinggi')
            ->assertJsonStructure([
                'metaData' => ['code', 'message', 'timestamp', 'request_id'],
                'response',
            ]);
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


    public function test_request_id_header_is_reflected_in_response_metadata(): void
    {
        $requestId = 'simrs-test-request-id';

        $response = $this
            ->withHeaders(['X-Request-Id' => $requestId])
            ->postJson('/api/v1/ai/queue-estimate', [
                'queue_number' => 5,
                'avg_service_minutes' => 10,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('metaData.request_id', $requestId);
    }

    public function test_all_ai_endpoints_accept_valid_payload(): void
    {
        $cases = [
            ['/api/v1/ai/triage-suggestion', ['symptoms' => ['demam'], 'pain_scale' => 3, 'spo2' => 98]],
            ['/api/v1/ai/patient-risk-score', ['age' => 56, 'comorbidities' => ['diabetes'], 'blood_pressure_systolic' => 150, 'blood_pressure_diastolic' => 95]],
            ['/api/v1/ai/readmission-prediction', ['length_of_stay_days' => 5, 'visit_last_90_days' => 2, 'discharge_against_advice' => false]],
            ['/api/v1/ai/bed-demand-forecast', ['occupied_beds' => 80, 'total_beds' => 100, 'avg_daily_admissions' => 20]],
            ['/api/v1/ai/medication-interaction-check', ['medications' => ['warfarin', 'aspirin']]],
            ['/api/v1/ai/duplicate-record-detection', ['records' => [['name' => 'Budi', 'birth_date' => '1990-01-01', 'medical_record_number' => 'RM001'], ['name' => 'Budi', 'birth_date' => '1990-01-01', 'medical_record_number' => 'RM002']]]],
            ['/api/v1/ai/referral-recommendation', ['diagnosis_code' => 'I10']],
            ['/api/v1/ai/queue-estimate', ['queue_number' => 11, 'avg_service_minutes' => 10]],
            ['/api/v1/ai/claim-anomaly-detection', ['claims' => [['claim_id' => 'CLM-2', 'amount' => 2000000, 'historical_average' => 1000000]]]],
            ['/api/v1/ai/clinical-summary', ['subjective' => 'Batuk 3 hari', 'objective' => 'Suhu 38 C', 'assessment' => 'ISPA', 'plan' => 'Antibiotik oral']],
            ['/api/v1/ai/mortality-risk-estimate', ['age' => 70, 'comorbidity_count' => 3, 'spo2' => 89]],
            ['/api/v1/ai/sepsis-early-warning', ['heart_rate' => 120, 'respiratory_rate' => 30, 'temperature' => 39]],
            ['/api/v1/ai/stroke-risk-estimate', ['hypertension' => true, 'smoking' => false, 'atrial_fibrillation' => true]],
            ['/api/v1/ai/nutrition-risk-screening', ['bmi' => 17.5, 'weight_loss_percent' => 6.1]],
            ['/api/v1/ai/fall-risk-assessment', ['age' => 66, 'history_of_fall' => true, 'sedative_use' => false]],
            ['/api/v1/ai/infection-control-risk', ['isolation_required' => true, 'multi_drug_resistant' => false]],
            ['/api/v1/ai/surgery-readiness-check', ['hemoglobin' => 12.3, 'platelet' => 220000, 'consent_signed' => true]],
            ['/api/v1/ai/icu-transfer-recommendation', ['news_score' => 8, 'vasopressor_needed' => false]],
            ['/api/v1/ai/ventilator-need-prediction', ['respiratory_rate' => 32, 'spo2' => 86, 'fio2' => 70]],
            ['/api/v1/ai/discharge-planning-score', ['stable_vital' => true, 'medication_prepared' => true, 'caregiver_available' => true]],
            ['/api/v1/ai/length-of-stay-estimate', ['case_severity' => 'sedang', 'comorbidity_count' => 2]],
            ['/api/v1/ai/emergency-load-prediction', ['current_queue' => 20, 'arrivals_per_hour' => 10]],
            ['/api/v1/ai/lab-critical-value-detection', ['labs' => ['potassium' => 6.5, 'glucose' => 120]]],
            ['/api/v1/ai/antibiotic-suggestion', ['diagnosis' => 'pneumonia']],
            ['/api/v1/ai/dehydration-risk-score', ['age' => 4, 'vomiting' => true, 'diarrhea' => true]],
            ['/api/v1/ai/pressure-ulcer-risk', ['immobile' => true, 'incontinence' => true, 'malnutrition' => false]],
            ['/api/v1/ai/pediatric-dosage-check', ['weight_kg' => 20.5, 'dose_mg_per_kg' => 10]],
            ['/api/v1/ai/dialysis-need-prediction', ['creatinine' => 7.2, 'fluid_overload' => true, 'uremia_symptoms' => true]],
            ['/api/v1/ai/blood-transfusion-need', ['hemoglobin' => 6.9, 'active_bleeding' => false]],
            ['/api/v1/ai/mental-health-screening', ['phq2_score' => 4, 'suicidal_ideation' => false]],
            ['/api/v1/ai/maternal-risk-assessment', ['gestational_weeks' => 32, 'hypertension' => true, 'bleeding' => false]],
            ['/api/v1/ai/neonatal-risk-assessment', ['birth_weight_kg' => 2.3, 'apgar5' => 8]],
            ['/api/v1/ai/outpatient-no-show-prediction', ['previous_no_show' => 2, 'days_to_appointment' => 21]],
            ['/api/v1/ai/vaccine-eligibility-check', ['age' => 30, 'pregnant' => false, 'immunocompromised' => false]],
            ['/api/v1/ai/telemedicine-suitability', ['needs_physical_exam' => false, 'stable_condition' => true]],
        ];

        foreach ($cases as [$url, $payload]) {
            $response = $this->postJson($url, $payload);

            $response
                ->assertOk()
                ->assertJsonPath('metaData.code', '200')
                ->assertJsonStructure([
                    'metaData' => ['code', 'message', 'timestamp', 'request_id'],
                    'response',
                ]);
        }
    }
}
