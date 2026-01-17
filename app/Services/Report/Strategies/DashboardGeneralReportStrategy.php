<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\DashboardGeneralReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;

class DashboardGeneralReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly DashboardGeneralReportRepository $repo
    ) {}

    public function name(): string
    {
        return 'Dashboard General IATF';
    }

    public function validate(array $filters): ?array
    {
        // Aquí puedes poner reglas extra si deseas (ej: group_by válido ya se valida en Request)
        return null;
    }

    public function fetch(array $filters)
    {
        // traer todo lo necesario en una sola estructura
        return [
            'hato' => $this->repo->countHatoObjetivo($filters),

            'presync' => $this->repo->countStagePresync($filters),

            'ultrasound' => $this->repo->countStageUltrasound($filters),
            'ultrasound_status' => $this->repo->ultrasoundStatusBreakdown($filters),

            'implant_retrieval' => $this->repo->countStageImplantRetrieval($filters),
            'implant_status' => $this->repo->implantRetrievalStatusBreakdown($filters),

            'insemination' => $this->repo->countStageInsemination($filters),
            'heat_quality' => $this->repo->inseminationHeatQualityBreakdown($filters),

            'confirmatory' => $this->repo->countStageConfirmatory($filters),
            'confirmatory_status' => $this->repo->confirmatoryStatusBreakdown($filters),

            'general_diag' => $this->repo->countStageGeneralDiagnosis($filters),
            'general_status' => $this->repo->generalDiagnosisStatusBreakdown($filters),

            'births' => $this->repo->countStageBirths($filters),
            'birth_types' => $this->repo->birthTypeBreakdown($filters),
            'calf_sex' => $this->repo->calfSexBreakdown($filters),

            'series' => [
                'inseminations' => $this->repo->seriesInseminations($filters),
                'pregnant_general' => $this->repo->seriesPregnanciesGeneral($filters),
            ],
        ];
    }

    public function build(array $filters, $fetched): array
    {
        $hato = (int)$fetched['hato'];

        $presync = (int)$fetched['presync'];
        $ultra   = (int)$fetched['ultrasound'];
        $retr    = (int)$fetched['implant_retrieval'];
        $inse    = (int)$fetched['insemination'];
        $conf    = (int)$fetched['confirmatory'];
        $diag    = (int)$fetched['general_diag'];
        $births  = (int)$fetched['births'];

        // KPI principales (embudo y conversiones)
        $kpis = [
            'hato_objetivo' => $hato,

            'presynchronization' => [
                'count' => $presync,
                'coverage_pct' => $this->pct($presync, $hato),
                'missing' => max($hato - $presync, 0),
                'missing_pct' => $this->pct(max($hato - $presync, 0), $hato),
            ],

            'ultrasound' => [
                'count' => $ultra,
                'coverage_pct' => $this->pct($ultra, $hato),
            ],

            'implant_retrieval' => [
                'count' => $retr,
                'coverage_pct' => $this->pct($retr, $hato),
            ],

            'insemination' => [
                'count' => $inse,
                'coverage_pct' => $this->pct($inse, $hato),
            ],

            'confirmatory_ultrasound' => [
                'count' => $conf,
                'coverage_pct' => $this->pct($conf, $hato),
            ],

            'general_pregnancy_diagnosis' => [
                'count' => $diag,
                'coverage_pct' => $this->pct($diag, $hato),
            ],

            'births' => [
                'count' => $births,
                'coverage_pct' => $this->pct($births, $hato),
            ],
        ];

        // Embudo para gráfico funnel (cards + funnel)
        $funnel = [
            ['stage' => 'Hato objetivo', 'count' => $hato],
            ['stage' => 'Presincronización', 'count' => $presync],
            ['stage' => 'Ecografía', 'count' => $ultra],
            ['stage' => 'Retiro de implante', 'count' => $retr],
            ['stage' => 'Inseminación', 'count' => $inse],
            ['stage' => 'Eco confirmación', 'count' => $conf],
            ['stage' => 'Diagnóstico general', 'count' => $diag],
            ['stage' => 'Parto', 'count' => $births],
        ];

        // Breakdown (para pasteles/barras)
        $breakdowns = [
            'ultrasound_status' => $this->withPct($fetched['ultrasound_status'], $ultra),
            'implant_retrieval_status' => $this->withPct($fetched['implant_status'], $retr),
            'confirmatory_status' => $this->withPct($fetched['confirmatory_status'], $conf),
            'general_diagnosis_status' => $this->withPct($fetched['general_status'], $diag),
            'heat_quality' => $this->withPctHeat($fetched['heat_quality'], $inse),
            'birth_types' => $this->withPct($fetched['birth_types'], $births),
            'calf_sex' => $this->withPctSex($fetched['calf_sex']),
        ];

        return [
            'filters_applied' => $filters,
            'group_by' => $filters['group_by'] ?? 'week',

            'kpis' => $kpis,
            'funnel' => $funnel,
            'breakdowns' => $breakdowns,

            'series' => $fetched['series'], // line charts
        ];
    }

    private function withPct(array $rows, int $total): array
    {
        return array_map(function ($r) use ($total) {
            $count = (int)($r['count'] ?? 0);
            return $r + ['pct' => $this->pct($count, $total)];
        }, $rows);
    }

    private function withPctHeat(array $rows, int $total): array
    {
        return array_map(function ($r) use ($total) {
            $count = (int)($r['count'] ?? 0);
            return [
                'heat_quality' => $r['heat_quality'] ?? null, // well/regular/bad
                'count' => $count,
                'pct' => $this->pct($count, $total),
            ];
        }, $rows);
    }

    private function withPctSex(array $rows): array
    {
        $total = array_sum(array_map(fn($r) => (int)$r['count'], $rows));
        return array_map(function ($r) use ($total) {
            $count = (int)$r['count'];
            return $r + ['pct' => $this->pct($count, $total)];
        }, $rows);
    }
}
