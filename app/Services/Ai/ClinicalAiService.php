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
}
