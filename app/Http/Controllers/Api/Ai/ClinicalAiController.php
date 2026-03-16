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


    public function mortalityRiskEstimate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'comorbidity_count' => ['required', 'integer', 'min:0', 'max:20'],
            'spo2' => ['required', 'integer', 'min:50', 'max:100'],
        ]);

        return ApiResponse::success($this->service->mortalityRiskEstimate(
            $payload['age'],
            $payload['comorbidity_count'],
            $payload['spo2'],
        ));
    }

    public function sepsisEarlyWarning(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'heart_rate' => ['required', 'integer', 'min:20', 'max:250'],
            'respiratory_rate' => ['required', 'integer', 'min:5', 'max:80'],
            'temperature' => ['required', 'integer', 'min:30', 'max:45'],
        ]);

        return ApiResponse::success($this->service->sepsisEarlyWarning(
            $payload['heart_rate'],
            $payload['respiratory_rate'],
            $payload['temperature'],
        ));
    }

    public function strokeRiskEstimate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'hypertension' => ['required', 'boolean'],
            'smoking' => ['required', 'boolean'],
            'atrial_fibrillation' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->strokeRiskEstimate(
            $payload['hypertension'],
            $payload['smoking'],
            $payload['atrial_fibrillation'],
        ));
    }

    public function nutritionRiskScreening(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'bmi' => ['required', 'numeric', 'min:10', 'max:60'],
            'weight_loss_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        return ApiResponse::success($this->service->nutritionRiskScreening(
            (float) $payload['bmi'],
            (float) $payload['weight_loss_percent'],
        ));
    }

    public function fallRiskAssessment(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'history_of_fall' => ['required', 'boolean'],
            'sedative_use' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->fallRiskAssessment(
            $payload['age'],
            $payload['history_of_fall'],
            $payload['sedative_use'],
        ));
    }

    public function infectionControlRisk(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'isolation_required' => ['required', 'boolean'],
            'multi_drug_resistant' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->infectionControlRisk(
            $payload['isolation_required'],
            $payload['multi_drug_resistant'],
        ));
    }

    public function surgeryReadinessCheck(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'hemoglobin' => ['required', 'numeric', 'min:1', 'max:25'],
            'platelet' => ['required', 'numeric', 'min:1000', 'max:1000000'],
            'consent_signed' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->surgeryReadinessCheck(
            (float) $payload['hemoglobin'],
            (float) $payload['platelet'],
            $payload['consent_signed'],
        ));
    }

    public function icuTransferRecommendation(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'news_score' => ['required', 'integer', 'min:0', 'max:20'],
            'vasopressor_needed' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->icuTransferRecommendation(
            $payload['news_score'],
            $payload['vasopressor_needed'],
        ));
    }

    public function ventilatorNeedPrediction(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'respiratory_rate' => ['required', 'integer', 'min:5', 'max:80'],
            'spo2' => ['required', 'integer', 'min:50', 'max:100'],
            'fio2' => ['required', 'integer', 'min:21', 'max:100'],
        ]);

        return ApiResponse::success($this->service->ventilatorNeedPrediction(
            $payload['respiratory_rate'],
            $payload['spo2'],
            $payload['fio2'],
        ));
    }

    public function dischargePlanningScore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'stable_vital' => ['required', 'boolean'],
            'medication_prepared' => ['required', 'boolean'],
            'caregiver_available' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->dischargePlanningScore(
            $payload['stable_vital'],
            $payload['medication_prepared'],
            $payload['caregiver_available'],
        ));
    }

    public function lengthOfStayEstimate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'case_severity' => ['required', 'string'],
            'comorbidity_count' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        return ApiResponse::success($this->service->lengthOfStayEstimate(
            $payload['case_severity'],
            $payload['comorbidity_count'],
        ));
    }

    public function emergencyLoadPrediction(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'current_queue' => ['required', 'integer', 'min:0', 'max:500'],
            'arrivals_per_hour' => ['required', 'integer', 'min:0', 'max:300'],
        ]);

        return ApiResponse::success($this->service->emergencyLoadPrediction(
            $payload['current_queue'],
            $payload['arrivals_per_hour'],
        ));
    }

    public function labCriticalValueDetection(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'labs' => ['required', 'array', 'min:1'],
            'labs.*' => ['numeric'],
        ]);

        return ApiResponse::success($this->service->labCriticalValueDetection($payload['labs']));
    }

    public function antibioticSuggestion(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'diagnosis' => ['required', 'string'],
        ]);

        return ApiResponse::success($this->service->antibioticSuggestion($payload['diagnosis']));
    }

    public function dehydrationRiskScore(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'vomiting' => ['required', 'boolean'],
            'diarrhea' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->dehydrationRiskScore(
            $payload['age'],
            $payload['vomiting'],
            $payload['diarrhea'],
        ));
    }

    public function pressureUlcerRisk(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'immobile' => ['required', 'boolean'],
            'incontinence' => ['required', 'boolean'],
            'malnutrition' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->pressureUlcerRisk(
            $payload['immobile'],
            $payload['incontinence'],
            $payload['malnutrition'],
        ));
    }

    public function pediatricDosageCheck(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'weight_kg' => ['required', 'numeric', 'min:0.5', 'max:200'],
            'dose_mg_per_kg' => ['required', 'numeric', 'min:0.1', 'max:100'],
        ]);

        return ApiResponse::success($this->service->pediatricDosageCheck(
            (float) $payload['weight_kg'],
            (float) $payload['dose_mg_per_kg'],
        ));
    }

    public function dialysisNeedPrediction(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'creatinine' => ['required', 'numeric', 'min:0', 'max:30'],
            'fluid_overload' => ['required', 'boolean'],
            'uremia_symptoms' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->dialysisNeedPrediction(
            (float) $payload['creatinine'],
            $payload['fluid_overload'],
            $payload['uremia_symptoms'],
        ));
    }

    public function bloodTransfusionNeed(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'hemoglobin' => ['required', 'numeric', 'min:1', 'max:25'],
            'active_bleeding' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->bloodTransfusionNeed(
            (float) $payload['hemoglobin'],
            $payload['active_bleeding'],
        ));
    }

    public function mentalHealthScreening(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'phq2_score' => ['required', 'integer', 'min:0', 'max:6'],
            'suicidal_ideation' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->mentalHealthScreening(
            $payload['phq2_score'],
            $payload['suicidal_ideation'],
        ));
    }

    public function maternalRiskAssessment(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'gestational_weeks' => ['required', 'integer', 'min:1', 'max:45'],
            'hypertension' => ['required', 'boolean'],
            'bleeding' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->maternalRiskAssessment(
            $payload['gestational_weeks'],
            $payload['hypertension'],
            $payload['bleeding'],
        ));
    }

    public function neonatalRiskAssessment(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'birth_weight_kg' => ['required', 'numeric', 'min:0.3', 'max:7'],
            'apgar5' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        return ApiResponse::success($this->service->neonatalRiskAssessment(
            (float) $payload['birth_weight_kg'],
            $payload['apgar5'],
        ));
    }

    public function outpatientNoShowPrediction(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'previous_no_show' => ['required', 'integer', 'min:0', 'max:20'],
            'days_to_appointment' => ['required', 'integer', 'min:0', 'max:365'],
        ]);

        return ApiResponse::success($this->service->outpatientNoShowPrediction(
            $payload['previous_no_show'],
            $payload['days_to_appointment'],
        ));
    }

    public function vaccineEligibilityCheck(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'pregnant' => ['required', 'boolean'],
            'immunocompromised' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->vaccineEligibilityCheck(
            $payload['age'],
            $payload['pregnant'],
            $payload['immunocompromised'],
        ));
    }

    public function telemedicineSuitability(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'needs_physical_exam' => ['required', 'boolean'],
            'stable_condition' => ['required', 'boolean'],
        ]);

        return ApiResponse::success($this->service->telemedicineSuitability(
            $payload['needs_physical_exam'],
            $payload['stable_condition'],
        ));
    }

}
