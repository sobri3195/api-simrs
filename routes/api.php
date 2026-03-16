<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Antrol\AntreanRsController;
use App\Http\Controllers\Api\Bpjs\BpjsVClaimController;
use App\Http\Controllers\Api\Bpjs\BpjsReferensiController;
use App\Http\Controllers\Api\Bpjs\BpjsSukonController;
use App\Http\Controllers\Api\SatuSehat\EncounterController;
use App\Http\Controllers\Api\SatuSehat\TokenController;
use App\Http\Controllers\Api\Ai\ClinicalAiController;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'metaData' => [
                'code' => '200',
                'message' => 'Sukses',
            ],
            'response' => (object) [
                'app' => 'SIMRS API',
                'version' => 'v1',
                'status' => 'active',
            ],
        ]);
    });

    Route::prefix('bpjs')->group(function () {
        Route::get('/peserta', [BpjsVClaimController::class, 'peserta']);
        Route::get('/sep', [BpjsVClaimController::class, 'cariSep']);
        Route::get('/sep-riwayat', [BpjsVClaimController::class, 'historyPelayananPeserta']);
        Route::post('/sep', [BpjsVClaimController::class, 'insertSep']);
        Route::put('/sep', [BpjsVClaimController::class, 'updateSep']);
        Route::delete('/sep', [BpjsVClaimController::class, 'hapusSep']);


        Route::get('/monitoring-kunjungan', [BpjsVClaimController::class, 'monitoringKunjungan']);
        Route::get('/monitoring-klaim', [BpjsVClaimController::class, 'monitoringKlaim']);

        Route::get('/referensi/poli', [BpjsReferensiController::class, 'poli']);
        Route::get('/referensi/diagnosa', [BpjsReferensiController::class, 'diagnosa']);
        Route::get('/referensi/faskes', [BpjsReferensiController::class, 'faskes']);
        Route::get('/referensi/dokter-dpjp', [BpjsReferensiController::class, 'dokterDpjp']);
        Route::get('/referensi/provinsi', [BpjsReferensiController::class, 'provinsi']);
        Route::get('/referensi/kabupaten', [BpjsReferensiController::class, 'kabupaten']);
        Route::get('/referensi/kecamatan', [BpjsReferensiController::class, 'kecamatan']);
        Route::get('/referensi/prosedur', [BpjsReferensiController::class, 'prosedur']);


        //Sukon
        Route::post('/surat-kontrol/insert', [BpjsSukonController::class, 'insertSuratKontrol']);
        Route::post('/surat-kontrol/update', [BpjsSukonController::class, 'updateSuratKontrol']);

        // SPRI
        Route::post('/spri/insert', [BpjsSukonController::class, 'insertSpri']);
        Route::post('/spri/update', [BpjsSukonController::class, 'updateSpri']);
    });


    Route::prefix('ai')->group(function () {
        Route::post('/triage-suggestion', [ClinicalAiController::class, 'triageSuggestion']);
        Route::post('/patient-risk-score', [ClinicalAiController::class, 'patientRiskScore']);
        Route::post('/readmission-prediction', [ClinicalAiController::class, 'readmissionPrediction']);
        Route::post('/bed-demand-forecast', [ClinicalAiController::class, 'bedDemandForecast']);
        Route::post('/medication-interaction-check', [ClinicalAiController::class, 'medicationInteractionCheck']);
        Route::post('/duplicate-record-detection', [ClinicalAiController::class, 'duplicateRecordDetection']);
        Route::post('/referral-recommendation', [ClinicalAiController::class, 'referralRecommendation']);
        Route::post('/queue-estimate', [ClinicalAiController::class, 'queueEstimate']);
        Route::post('/claim-anomaly-detection', [ClinicalAiController::class, 'claimAnomalyDetection']);
        Route::post('/clinical-summary', [ClinicalAiController::class, 'generateClinicalSummary']);

        Route::post('/mortality-risk-estimate', [ClinicalAiController::class, 'mortalityRiskEstimate']);
        Route::post('/sepsis-early-warning', [ClinicalAiController::class, 'sepsisEarlyWarning']);
        Route::post('/stroke-risk-estimate', [ClinicalAiController::class, 'strokeRiskEstimate']);
        Route::post('/nutrition-risk-screening', [ClinicalAiController::class, 'nutritionRiskScreening']);
        Route::post('/fall-risk-assessment', [ClinicalAiController::class, 'fallRiskAssessment']);
        Route::post('/infection-control-risk', [ClinicalAiController::class, 'infectionControlRisk']);
        Route::post('/surgery-readiness-check', [ClinicalAiController::class, 'surgeryReadinessCheck']);
        Route::post('/icu-transfer-recommendation', [ClinicalAiController::class, 'icuTransferRecommendation']);
        Route::post('/ventilator-need-prediction', [ClinicalAiController::class, 'ventilatorNeedPrediction']);
        Route::post('/discharge-planning-score', [ClinicalAiController::class, 'dischargePlanningScore']);
        Route::post('/length-of-stay-estimate', [ClinicalAiController::class, 'lengthOfStayEstimate']);
        Route::post('/emergency-load-prediction', [ClinicalAiController::class, 'emergencyLoadPrediction']);
        Route::post('/lab-critical-value-detection', [ClinicalAiController::class, 'labCriticalValueDetection']);
        Route::post('/antibiotic-suggestion', [ClinicalAiController::class, 'antibioticSuggestion']);
        Route::post('/dehydration-risk-score', [ClinicalAiController::class, 'dehydrationRiskScore']);
        Route::post('/pressure-ulcer-risk', [ClinicalAiController::class, 'pressureUlcerRisk']);
        Route::post('/pediatric-dosage-check', [ClinicalAiController::class, 'pediatricDosageCheck']);
        Route::post('/dialysis-need-prediction', [ClinicalAiController::class, 'dialysisNeedPrediction']);
        Route::post('/blood-transfusion-need', [ClinicalAiController::class, 'bloodTransfusionNeed']);
        Route::post('/mental-health-screening', [ClinicalAiController::class, 'mentalHealthScreening']);
        Route::post('/maternal-risk-assessment', [ClinicalAiController::class, 'maternalRiskAssessment']);
        Route::post('/neonatal-risk-assessment', [ClinicalAiController::class, 'neonatalRiskAssessment']);
        Route::post('/outpatient-no-show-prediction', [ClinicalAiController::class, 'outpatientNoShowPrediction']);
        Route::post('/vaccine-eligibility-check', [ClinicalAiController::class, 'vaccineEligibilityCheck']);
        Route::post('/telemedicine-suitability', [ClinicalAiController::class, 'telemedicineSuitability']);
    });

    Route::prefix('antrol')->group(function () {
        Route::get('/antrean', [AntreanRsController::class, 'index']);
    });



    Route::prefix('satu-sehat')->group(function () {
        Route::get('/token', [TokenController::class, 'index']);
        Route::post('/encounter/send', [EncounterController::class, 'send']);
    });

    Route::fallback(function () {
        return response()->json([
            'metaData' => [
                'code' => '404',
                'message' => 'Endpoint tidak ditemukan',
            ],
            'response' => (object) [],
        ], 404);
    });
});
