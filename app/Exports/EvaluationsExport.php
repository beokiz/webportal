<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exports;

use App\Models\Evaluation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Evaluations Export
 *
 * @package \App\Exports
 */
class EvaluationsExport implements FromCollection, WithColumnFormatting, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Evaluation::whereNotNull('finished_at')
            ->with(['kita'])
            ->get();
    }

    /**
     * @param $evaluation
     * @return array
     */
    public function map($evaluation) : array
    {
        if (!empty($evaluation->kita)) {
            $formattedId = Str::slug($evaluation->kita->name, '_') . "_" . $evaluation->uuid;
            $postalCode  = $evaluation->kita->zip_code;
            $kitaName  = $evaluation->kita->name;
        } else {
            $formattedId = $evaluation->uuid;
            $postalCode  = null;
            $kitaName  = null;
        }

        return [
            $formattedId,
            $evaluation->finished_at,
            $evaluation->age,
            $evaluation->is_daz ? 'yes' : 'no',
            $postalCode,
            $kitaName,
            !empty($evaluation->data) ? array_sum(array_column($evaluation->data, 'rating')) : 0,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats() : array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_DATE_XLSX22,
            'C' => NumberFormat::FORMAT_NUMBER_0,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
