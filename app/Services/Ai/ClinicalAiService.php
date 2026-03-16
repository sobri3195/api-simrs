<?php

namespace App\Services\Ai;

class ClinicalAiService
{
    public function triageSuggestion(array $symptoms, int $painScale, int $spo2): array
    {
        $urgency = 'rendah';

        if ($painScale >= 8 || $spo2 < 90 || in_array('sesak berat', $symptoms, true)) {
            $urgency = 'tinggi';
        } elseif ($painScale >= 5 || $spo2 < 95) {
            $urgency = 'sedang';
        }

        $suggestion = match ($urgency) {
            'tinggi' => 'Prioritaskan ke IGD dan lakukan observasi ketat.',
            'sedang' => 'Perlu evaluasi dokter dalam waktu dekat.',
            default => 'Masuk antrean reguler dengan monitoring dasar.',
        };

        return compact('urgency', 'suggestion');
    }

    public function patientRiskScore(int $age, array $comorbidities, int $systolic, int $diastolic): array
    {
        $score = 0;
        $score += $age >= 60 ? 30 : ($age >= 45 ? 20 : 10);
        $score += count($comorbidities) * 10;

        if ($systolic >= 160 || $diastolic >= 100) {
            $score += 20;
        } elseif ($systolic >= 140 || $diastolic >= 90) {
            $score += 10;
        }

        $category = $score >= 70 ? 'tinggi' : ($score >= 40 ? 'sedang' : 'rendah');

        return ['score' => min($score, 100), 'category' => $category];
    }

    public function readmissionPrediction(int $lengthOfStayDays, int $visitLast90Days, bool $dischargeAgainstAdvice): array
    {
        $probability = 0.1;
        $probability += min($lengthOfStayDays * 0.02, 0.2);
        $probability += min($visitLast90Days * 0.05, 0.4);
        $probability += $dischargeAgainstAdvice ? 0.2 : 0;

        $probability = round(min($probability, 0.95), 2);
        $label = $probability >= 0.6 ? 'tinggi' : ($probability >= 0.35 ? 'sedang' : 'rendah');

        return [
            'probability' => $probability,
            'label' => $label,
        ];
    }

    public function bedDemandForecast(int $occupiedBeds, int $totalBeds, int $avgDailyAdmissions): array
    {
        $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 2) : 0;
        $nextDayDemand = (int) round($occupiedBeds + ($avgDailyAdmissions * 0.75));
        $remainingCapacity = max($totalBeds - $nextDayDemand, 0);

        return [
            'occupancy_rate' => $occupancyRate,
            'next_day_demand' => $nextDayDemand,
            'remaining_capacity' => $remainingCapacity,
        ];
    }

    public function medicationInteractionCheck(array $medications): array
    {
        $pairs = [
            ['warfarin', 'aspirin', 'Risiko perdarahan meningkat.'],
            ['ibuprofen', 'lisinopril', 'Efek antihipertensi dapat menurun.'],
            ['simvastatin', 'clarithromycin', 'Risiko miopati meningkat.'],
        ];

        $lower = array_map(fn ($item) => strtolower($item), $medications);
        $alerts = [];

        foreach ($pairs as [$a, $b, $warning]) {
            if (in_array($a, $lower, true) && in_array($b, $lower, true)) {
                $alerts[] = [
                    'combination' => [$a, $b],
                    'warning' => $warning,
                ];
            }
        }

        return [
            'alert_count' => count($alerts),
            'alerts' => $alerts,
        ];
    }

    public function duplicateRecordDetection(array $records): array
    {
        $seen = [];
        $duplicates = [];

        foreach ($records as $record) {
            $key = strtolower(($record['name'] ?? '').'|'.($record['birth_date'] ?? ''));
            if (isset($seen[$key])) {
                $duplicates[] = $record;
                continue;
            }

            $seen[$key] = true;
        }

        return [
            'duplicate_count' => count($duplicates),
            'duplicates' => $duplicates,
        ];
    }

    public function referralRecommendation(string $diagnosisCode): array
    {
        $map = [
            'I10' => 'Poli Jantung',
            'E11' => 'Poli Penyakit Dalam',
            'J45' => 'Poli Paru',
            'M54' => 'Poli Saraf',
        ];

        return [
            'diagnosis_code' => strtoupper($diagnosisCode),
            'recommended_clinic' => $map[strtoupper($diagnosisCode)] ?? 'Poli Umum',
        ];
    }

    public function queueEstimate(int $queueNumber, int $avgServiceMinutes): array
    {
        $estimatedMinutes = max($queueNumber - 1, 0) * $avgServiceMinutes;

        return [
            'estimated_wait_minutes' => $estimatedMinutes,
            'service_speed_minutes_per_patient' => $avgServiceMinutes,
        ];
    }

    public function claimAnomalyDetection(array $claims): array
    {
        $anomalies = [];

        foreach ($claims as $claim) {
            $amount = (float) ($claim['amount'] ?? 0);
            $avg = (float) ($claim['historical_average'] ?? 1);

            if ($avg > 0 && $amount >= $avg * 1.7) {
                $anomalies[] = [
                    'claim_id' => $claim['claim_id'] ?? null,
                    'amount' => $amount,
                    'historical_average' => $avg,
                    'reason' => 'Nilai klaim jauh di atas rata-rata historis.',
                ];
            }
        }

        return [
            'anomaly_count' => count($anomalies),
            'anomalies' => $anomalies,
        ];
    }

    public function generateClinicalSummary(string $subjective, string $objective, string $assessment, string $plan): array
    {
        return [
            'summary' => sprintf(
                'S: %s O: %s A: %s P: %s',
                trim($subjective),
                trim($objective),
                trim($assessment),
                trim($plan)
            ),
        ];
    }


    public function mortalityRiskEstimate(int $age, int $comorbidityCount, int $spo2): array
    {
        $score = min(100, (int) round(($age * 0.5) + ($comorbidityCount * 12) + max(0, (95 - $spo2) * 2)));
        $category = $score >= 70 ? 'tinggi' : ($score >= 40 ? 'sedang' : 'rendah');

        return compact('score', 'category');
    }

    public function sepsisEarlyWarning(int $heartRate, int $respiratoryRate, int $temperature): array
    {
        $criteria = 0;
        $criteria += $heartRate > 100 ? 1 : 0;
        $criteria += $respiratoryRate > 22 ? 1 : 0;
        $criteria += ($temperature > 38 || $temperature < 36) ? 1 : 0;

        return [
            'criteria_met' => $criteria,
            'alert' => $criteria >= 2,
            'recommendation' => $criteria >= 2 ? 'Evaluasi sepsis segera dan lakukan kultur/laktat.' : 'Lanjutkan monitoring berkala.',
        ];
    }

    public function strokeRiskEstimate(bool $hypertension, bool $smoking, bool $atrialFibrillation): array
    {
        $score = ($hypertension ? 30 : 0) + ($smoking ? 20 : 0) + ($atrialFibrillation ? 40 : 0);
        return [
            'score' => $score,
            'risk' => $score >= 60 ? 'tinggi' : ($score >= 30 ? 'sedang' : 'rendah'),
        ];
    }

    public function nutritionRiskScreening(float $bmi, float $weightLossPercent): array
    {
        $risk = 0;
        $risk += $bmi < 18.5 ? 2 : 0;
        $risk += $weightLossPercent >= 5 ? 2 : 0;

        return [
            'risk_score' => $risk,
            'action' => $risk >= 3 ? 'Konsultasi gizi klinik direkomendasikan.' : 'Lakukan edukasi nutrisi standar.',
        ];
    }

    public function fallRiskAssessment(int $age, bool $historyOfFall, bool $sedativeUse): array
    {
        $score = ($age >= 65 ? 2 : 0) + ($historyOfFall ? 2 : 0) + ($sedativeUse ? 1 : 0);

        return [
            'fall_risk_score' => $score,
            'level' => $score >= 4 ? 'tinggi' : ($score >= 2 ? 'sedang' : 'rendah'),
        ];
    }

    public function infectionControlRisk(bool $isolationRequired, bool $multiDrugResistant): array
    {
        return [
            'isolation_required' => $isolationRequired,
            'mdr_flag' => $multiDrugResistant,
            'precaution' => ($isolationRequired || $multiDrugResistant) ? 'Gunakan APD lengkap dan ruang isolasi.' : 'Precaution standar.',
        ];
    }

    public function surgeryReadinessCheck(float $hemoglobin, float $platelet, bool $consentSigned): array
    {
        $ready = $hemoglobin >= 10 && $platelet >= 100000 && $consentSigned;

        return [
            'ready' => $ready,
            'notes' => $ready ? 'Pasien siap tindakan operasi.' : 'Optimasi pra-operasi diperlukan.',
        ];
    }

    public function icuTransferRecommendation(int $newsScore, bool $vasopressorNeeded): array
    {
        $recommend = $newsScore >= 7 || $vasopressorNeeded;

        return [
            'recommend_icu' => $recommend,
            'reason' => $recommend ? 'Instabilitas klinis terdeteksi.' : 'Bisa rawat di bangsal dengan observasi.',
        ];
    }

    public function ventilatorNeedPrediction(int $respiratoryRate, int $spo2, int $fio2): array
    {
        $need = ($respiratoryRate > 30 && $spo2 < 90) || $fio2 > 60;

        return [
            'need_ventilator' => $need,
            'severity' => $need ? 'tinggi' : 'rendah',
        ];
    }

    public function dischargePlanningScore(bool $stableVital, bool $medicationPrepared, bool $caregiverAvailable): array
    {
        $score = ($stableVital ? 40 : 0) + ($medicationPrepared ? 30 : 0) + ($caregiverAvailable ? 30 : 0);

        return [
            'score' => $score,
            'ready_for_discharge' => $score >= 80,
        ];
    }

    public function lengthOfStayEstimate(string $caseSeverity, int $comorbidityCount): array
    {
        $base = match (strtolower($caseSeverity)) {
            'ringan' => 2,
            'sedang' => 4,
            'berat' => 7,
            default => 3,
        };

        return [
            'estimated_days' => $base + min($comorbidityCount, 5),
        ];
    }

    public function emergencyLoadPrediction(int $currentQueue, int $arrivalsPerHour): array
    {
        $nextHour = $currentQueue + $arrivalsPerHour - (int) round($currentQueue * 0.3);

        return [
            'predicted_queue_next_hour' => max($nextHour, 0),
            'status' => $nextHour > 40 ? 'padat' : 'normal',
        ];
    }

    public function labCriticalValueDetection(array $labs): array
    {
        $critical = [];
        foreach ($labs as $name => $value) {
            if (($name === 'potassium' && ($value < 2.5 || $value > 6.0)) || ($name === 'glucose' && ($value < 50 || $value > 400))) {
                $critical[] = ['parameter' => $name, 'value' => $value];
            }
        }

        return [
            'critical_count' => count($critical),
            'critical_values' => $critical,
        ];
    }

    public function antibioticSuggestion(string $diagnosis): array
    {
        $map = [
            'pneumonia' => 'Ceftriaxone + Azithromycin',
            'uti' => 'Ciprofloxacin',
            'cellulitis' => 'Cefazolin',
        ];

        $key = strtolower($diagnosis);
        return [
            'diagnosis' => $diagnosis,
            'suggested_regimen' => $map[$key] ?? 'Konsultasi PPRA untuk regimen empiris.',
        ];
    }

    public function dehydrationRiskScore(int $age, bool $vomiting, bool $diarrhea): array
    {
        $score = ($age <= 5 || $age >= 65 ? 2 : 1) + ($vomiting ? 2 : 0) + ($diarrhea ? 2 : 0);

        return ['risk_score' => $score, 'risk' => $score >= 5 ? 'tinggi' : ($score >= 3 ? 'sedang' : 'rendah')];
    }

    public function pressureUlcerRisk(bool $immobile, bool $incontinence, bool $malnutrition): array
    {
        $score = ($immobile ? 3 : 0) + ($incontinence ? 2 : 0) + ($malnutrition ? 2 : 0);

        return ['risk_score' => $score, 'recommendation' => $score >= 4 ? 'Reposisi 2 jam sekali dan skin care ketat.' : 'Pencegahan standar.'];
    }

    public function pediatricDosageCheck(float $weightKg, float $doseMgPerKg): array
    {
        return [
            'recommended_dose_mg' => round($weightKg * $doseMgPerKg, 2),
            'max_single_dose_warning' => ($weightKg * $doseMgPerKg) > 1000,
        ];
    }

    public function dialysisNeedPrediction(float $creatinine, bool $fluidOverload, bool $uremiaSymptoms): array
    {
        $need = $creatinine >= 6.0 && ($fluidOverload || $uremiaSymptoms);

        return ['need_dialysis_eval' => $need];
    }

    public function bloodTransfusionNeed(float $hemoglobin, bool $activeBleeding): array
    {
        $need = $hemoglobin < 7 || ($activeBleeding && $hemoglobin < 9);
        return ['need_transfusion' => $need, 'trigger_hb' => $hemoglobin];
    }

    public function mentalHealthScreening(int $phq2Score, bool $suicidalIdeation): array
    {
        $risk = $suicidalIdeation ? 'krisis' : ($phq2Score >= 3 ? 'sedang-tinggi' : 'rendah');

        return [
            'risk_level' => $risk,
            'action' => $suicidalIdeation ? 'Rujuk emergensi ke psikiater.' : 'Lanjutkan skrining PHQ-9 bila diperlukan.',
        ];
    }

    public function maternalRiskAssessment(int $gestationalWeeks, bool $hypertension, bool $bleeding): array
    {
        $highRisk = $hypertension || $bleeding || $gestationalWeeks < 34;
        return ['high_risk_pregnancy' => $highRisk];
    }

    public function neonatalRiskAssessment(float $birthWeightKg, int $apgar5): array
    {
        $risk = ($birthWeightKg < 2.5 || $apgar5 < 7) ? 'tinggi' : 'normal';
        return ['risk' => $risk];
    }

    public function outpatientNoShowPrediction(int $previousNoShow, int $daysToAppointment): array
    {
        $probability = min(0.95, round(($previousNoShow * 0.2) + ($daysToAppointment > 14 ? 0.2 : 0.05), 2));
        return ['probability' => $probability];
    }

    public function vaccineEligibilityCheck(int $age, bool $pregnant, bool $immunocompromised): array
    {
        $eligible = !$immunocompromised && $age >= 1;

        return [
            'eligible' => $eligible,
            'notes' => $pregnant ? 'Pertimbangkan vaksin yang aman untuk kehamilan.' : 'Ikuti jadwal imunisasi nasional.',
        ];
    }

    public function telemedicineSuitability(bool $needsPhysicalExam, bool $stableCondition): array
    {
        $suitable = !$needsPhysicalExam && $stableCondition;
        return [
            'suitable' => $suitable,
            'recommendation' => $suitable ? 'Bisa follow-up via telemedicine.' : 'Anjurkan kunjungan langsung.',
        ];
    }

}
