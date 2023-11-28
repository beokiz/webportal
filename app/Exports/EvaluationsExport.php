<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exports;

use App\Models\Evaluation;
use App\Services\Items\DomainItemService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Evaluations Export
 *
 * @package \App\Exports
 */
class EvaluationsExport implements FromCollection, WithColumnFormatting, WithMapping, WithHeadings, WithStyles, WithColumnWidths
{
    use Exportable, RegistersEventListeners;

    /**
     * @var DomainItemService
     */
    protected $domainItemService;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var mixed
     */
    protected $user = null;

    /**
     * @var array
     */
    protected $additionalCols = [];

    /**
     * EvaluationsExport constructor.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->domainItemService = app(DomainItemService::class);

        /*
         * Prepare common data
         */
        $this->data = $data;

        if (!empty($data['user'])) {
            $this->user = $data['user'];
        }

        /*
         * Prepare milestones data
         */
        $isAgeSet = !empty($this->data['age']) && in_array($this->data['age'], ['2.5', '4.5']);

        $this->domainItemService->collection(['only' => $this->data['domains'] ?? []])->each(function ($domain) use ($isAgeSet) {
            // Get milestones data
            $additionalDomainCols = [];
            $domainMilestones     = [];

            $domain->loadMissing('subdomains');

            $domain->subdomains->sortBy('order')->each(function ($subdomain) use ($isAgeSet, &$additionalDomainCols, &$domainMilestones) {
                $subdomain->loadMissing('milestones');

                $milestones = $isAgeSet ? $subdomain->milestones->where('age', $this->data['age']) : $subdomain->milestones;

                $milestones->sortBy('order')->each(function ($milestone) use (&$additionalDomainCols, &$domainMilestones) {
                    $additionalDomainCols[] = $milestone->abbreviation;
                    $domainMilestones[]     = $milestone->abbreviation;
                });
            });

            // Set domains data
            $redThreshold       = null;
            $yellowThreshold    = null;
            $redThresholdDaz    = null;
            $yellowThresholdDaz = null;

            if ($isAgeSet) {
                if ($this->data['age'] === '4.5') {
                    $redThreshold       = $domain->age_4_red_threshold;
                    $yellowThreshold    = $domain->age_4_yellow_threshold;
                    $redThresholdDaz    = $domain->age_4_red_threshold_daz;
                    $yellowThresholdDaz = $domain->age_4_yellow_threshold_daz;
                } else {
                    $redThreshold       = $domain->age_2_red_threshold;
                    $yellowThreshold    = $domain->age_2_yellow_threshold;
                    $redThresholdDaz    = $domain->age_2_red_threshold_daz;
                    $yellowThresholdDaz = $domain->age_2_yellow_threshold_daz;
                }
            }

            $this->data['domain_milestones'][$domain->abbreviation] = $domainMilestones;
            $this->data['domain_threshold'][$domain->abbreviation]  = [
                'red'        => $redThreshold,
                'yellow'     => $yellowThreshold,
                'red_daz'    => $redThresholdDaz,
                'yellow_daz' => $yellowThresholdDaz,
            ];

            $this->additionalCols = array_merge($this->additionalCols, $additionalDomainCols, [
                str_replace(' ', '', __('files.excel.domain_sum_label', ['domain' => $domain->abbreviation])),
                str_replace(' ', '', __('files.excel.domain_red_threshold_label', ['domain' => $domain->abbreviation])),
                str_replace(' ', '', __('files.excel.domain_yellow_threshold_label', ['domain' => $domain->abbreviation])),
                str_replace(' ', '', __('files.excel.domain_red_threshold_daz_label', ['domain' => $domain->abbreviation])),
                str_replace(' ', '', __('files.excel.domain_yellow_threshold_daz_label', ['domain' => $domain->abbreviation])),
            ]);
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Evaluation::whereNotNull('finished_at')
            ->with(['kita'])
            ->orderBy('id');

        if (!empty($this->data['finished_after'])) {
            $query->whereDate('finished_at', '>=', Carbon::make($this->data['finished_after'])->startOfDay());
        }

        if (!empty($this->data['finished_before'])) {
            $query->whereDate('finished_at', '<=', Carbon::make($this->data['finished_after'])->endOfDay());
        }

        if (!empty($this->data['age']) && in_array($this->data['age'], ['2.5', '4.5'])) {
            $query->where('age', $this->data['age']);
        }

        if (!empty($this->data['zip_code'])) {
            $query->whereHas('kita', function ($subQuery) {
                return $subQuery->where('zip_code', $this->data['zip_code']);
            });
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings() : array
    {
        $headings = [
            __('files.excel.id_label'),
            __('files.excel.finished_at_label'),
            __('files.excel.age_label'),
            __('files.excel.is_daz_label'),
//            __('files.excel.uuid_label'),
            __('files.excel.postal_label'),
            __('files.excel.uuid_label'),
        ];

//        if (!empty($this->user) && ($this->user->is_super_admin || $this->user->is_monitor)) {
//            $headings[] = __('files.excel.kita_label');
//        }

        return array_merge($headings, $this->additionalCols);
    }

    /**
     * @param $evaluation
     * @return array
     */
    public function map($evaluation) : array
    {
        if (!empty($evaluation->kita)) {
//            $formattedId = Str::slug($evaluation->kita->name, '_') . "_" . $evaluation->uuid;
            $postalCode = $evaluation->kita->zip_code;
            $kitaId     = $evaluation->kita->id;
            $kitaName   = $evaluation->kita->name;
        } else {
//            $formattedId = $evaluation->uuid;
            $postalCode = null;
            $kitaId     = null;
            $kitaName   = null;
        }

        $rowData = [
            'id'          => $evaluation->id,
            'finished_at' => $evaluation->finished_at,
            'age'         => $evaluation->age,
            'is_daz'      => $evaluation->is_daz ? 'yes' : 'no',
//            'uuid'        => $formattedId,
            'postal'      => $postalCode,
        ];

        if (!empty($this->user) && ($this->user->is_super_admin || $this->user->is_monitor)) {
            $rowData['kita'] = $kitaName;
        } else {
            $rowData['kita'] = $kitaId;
        }

        /*
         * Set domains & milestones data
         */
        $evaluationMilestonesRating = array_column(
            array_merge(...array_column($evaluation->data, 'milestones')),
            'value',
            'abbreviation'
        );

        foreach ($this->data['domain_milestones'] as $abbreviation => $milestones) {
            $sumColVal = 0;

            foreach ($milestones as $milestone) {
                $rowData[$milestone] = $evaluationMilestonesRating[$milestone] ?? null;

                $sumColVal += $evaluationMilestonesRating[$milestone] ?? 0;
            }

            array_overwrite($rowData, [
                str_replace(' ', '', __('files.excel.domain_sum_label', ['domain' => $abbreviation]))                  => $sumColVal > 0 ? $sumColVal : null,
                str_replace(' ', '', __('files.excel.domain_red_threshold_label', ['domain' => $abbreviation]))        => $this->data['domain_threshold'][$abbreviation]['red'] ?? null,
                str_replace(' ', '', __('files.excel.domain_yellow_threshold_label', ['domain' => $abbreviation]))     => $this->data['domain_threshold'][$abbreviation]['yellow'] ?? null,
                str_replace(' ', '', __('files.excel.domain_red_threshold_daz_label', ['domain' => $abbreviation]))    => $this->data['domain_threshold'][$abbreviation]['red_daz'] ?? null,
                str_replace(' ', '', __('files.excel.domain_yellow_threshold_daz_label', ['domain' => $abbreviation])) => $this->data['domain_threshold'][$abbreviation]['yellow_daz'] ?? null,
            ]);
        }

        return $rowData;
    }

    /**
     * @return array
     */
    public function columnFormats() : array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_DATE_XLSX22,
            'C' => NumberFormat::FORMAT_NUMBER_0,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @return int[]
     */
    public function columnWidths() : array
    {
        return [
            'A' => 5,
            'B' => 18,
            'C' => 15,
            'D' => 7,
            'E' => 40,
            'F' => 15,
            'G' => 15,
        ];
    }

    /**
     * @param $sheet
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function styles($sheet)
    {
        $lastColumnIndex = $sheet->getHighestColumn();

        /*
         * First row styling
         */
        $sheet->getStyle("A1:{$lastColumnIndex}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastColumnIndex}1")->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFF0000');

        $sheet->getStyle("A1:{$lastColumnIndex}1")->getFont()->setBold(true)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle("A1:{$lastColumnIndex}1")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:{$lastColumnIndex}1")->getFill()->setFillType('solid')->getStartColor()->setARGB('00537F');
        $sheet->getStyle("A1:{$lastColumnIndex}1")->getBorders()->getAllBorders()->setBorderStyle('thin')->getColor()->setARGB('FFFFFF');
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->freezePane('A2');

        /*
         * Other rows styling
         */
        $evenStyle = [
            'fill' => [
                'fillType'   => 'solid',
                'startColor' => ['argb' => 'B8CCE4'],
            ],
        ];

        $oddStyle = [
            'fill' => [
                'fillType'   => 'solid',
                'startColor' => ['argb' => 'DCE6F1'],
            ],
        ];

        $rows = $sheet->getHighestRow();

        for ($row = 2; $row <= $rows; $row++) { // OLD: $row <= $rows
            $style     = $row % 2 == 0 ? $evenStyle : $oddStyle;
            $cellRange = "A{$row}:{$lastColumnIndex}{$row}";

            $sheet->getStyle('A' . $row . ":{$lastColumnIndex}" . $row)->applyFromArray($style);

            $sheet->getStyle($cellRange)->applyFromArray($style);
            $sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle('thin')->getColor()->setARGB('FFFFFF');
            $sheet->getRowDimension($row)->setRowHeight(15);
        }

        $sheet->getStyle("A1:{$lastColumnIndex}" . $sheet->getHighestRow())->getAlignment()->setVertical('center');

        /*
         * Columns styling
         */
        foreach ($this->headings() as $index => $heading) {
            if (preg_match("/^[A-Za-z]+\.\d+$/", $heading)) {
                $sheet->getColumnDimensionByColumn($index + 1)->setWidth(10);
            } else if (str_contains($heading, 'Sum') || str_contains($heading, 'Summe')) {
                $sheet->getColumnDimensionByColumn($index + 1)->setWidth(15);
            } else if (str_contains($heading, 'Threshold') || str_contains($heading, 'Schwellenwert')) {
                $sheet->getColumnDimensionByColumn($index + 1)->setWidth(30);
            } else {
                //
            }
        }
    }
}
