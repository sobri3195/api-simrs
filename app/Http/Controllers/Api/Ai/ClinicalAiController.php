<?php

namespace App\Http\Controllers\Api\Ai;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Ai\ClinicalAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicalAiController extends Controller
{
    public function __construct(private readonly ClinicalAiService $service)
    {
    }

    public function triageSuggestion(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'symptoms' => ['required', 'array', 'min:1'],
            'symptoms.*' => ['string'],
            'pain_scale' => ['required', 'integer', 'min:0', 'max:10'],
            'spo2' => ['required', 'integer', 'min:50', 'max:100'],
        ]);

        return ApiResponse::success($this->service->triageSuggestion(
            $payload['symptoms'],
            $payload['pain_scale'],
            $payload['spo2'],
        ));
    }

    public function patientRiskScore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'comorbidities' => ['required', 'array'],
            'comorbidities.*' => ['string'],
            'blood_pressure_systolic' => ['required', 'integer', 'min:60', 'max:260'],
            'blood_pressure_diastolic' => ['required', 'integer', 'min:40', 'max:160'],
        ]);

        return ApiResponse::success($this->service->patientRiskScore(
            $payload['age'],
            $payload['comorbidities'],
            $payload['blood_pressure_systolic'],
            $payload['blood_pressure_diastolic'],
        ));
    }

    public function readmissionPrediction(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'length_of_stay_days' => ['required', 'integer', 'min:0', 'max:90'],
            'visit_last_90_days' => ['required', 'integer', 'min:0', 'max:40'],
            'discharge_against_advice' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->readmissionPrediction(
            $payload['length_of_stay_days'],
            $payload['visit_last_90_days'],
            $payload['discharge_against_advice'],
        ));
    }

    public function bedDemandForecast(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'occupied_beds' => ['required', 'integer', 'min:0'],
            'total_beds' => ['required', 'integer', 'min:1'],
            'avg_daily_admissions' => ['required', 'integer', 'min:0'],
        ]);

        return ApiResponse::success($this->service->bedDemandForecast(
            $payload['occupied_beds'],
            $payload['total_beds'],
            $payload['avg_daily_admissions'],
        ));
    }

    public function medicationInteractionCheck(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'medications' => ['required', 'array', 'min:1'],
            'medications.*' => ['string'],
        ]);

        return ApiResponse::success($this->service->medicationInteractionCheck($payload['medications']));
    }

    public function duplicateRecordDetection(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'records' => ['required', 'array', 'min:1'],
            'records.*.name' => ['required', 'string'],
            'records.*.birth_date' => ['required', 'date'],
            'records.*.medical_record_number' => ['nullable', 'string'],
        ]);

        return ApiResponse::success($this->service->duplicateRecordDetection($payload['records']));
    }

    public function referralRecommendation(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'diagnosis_code' => ['required', 'string', 'max:10'],
        ]);

        return ApiResponse::success($this->service->referralRecommendation($payload['diagnosis_code']));
    }

    public function queueEstimate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'queue_number' => ['required', 'integer', 'min:1', 'max:500'],
            'avg_service_minutes' => ['required', 'integer', 'min:1', 'max:120'],
        ]);

        return ApiResponse::success($this->service->queueEstimate(
            $payload['queue_number'],
            $payload['avg_service_minutes'],
        ));
    }

    public function claimAnomalyDetection(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'claims' => ['required', 'array', 'min:1'],
            'claims.*.claim_id' => ['required', 'string'],
            'claims.*.amount' => ['required', 'numeric', 'min:0'],
            'claims.*.historical_average' => ['required', 'numeric', 'min:0.0001'],
        ]);

        return ApiResponse::success($this->service->claimAnomalyDetection($payload['claims']));
    }

    public function generateClinicalSummary(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'subjective' => ['required', 'string'],
            'objective' => ['required', 'string'],
            'assessment' => ['required', 'string'],
            'plan' => ['required', 'string'],
        ]);

        return ApiResponse::success($this->service->generateClinicalSummary(
            $payload['subjective'],
            $payload['objective'],
            $payload['assessment'],
            $payload['plan'],
        ));
    }
}
