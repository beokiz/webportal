<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\YearlyEvaluations;

/**
 * Update Survey Time Period Request
 *
 * @package \App\Http\Requests\YearlyEvaluations
 */
class UpdateYearlyEvaluationRequest extends CreateYearlyEvaluationRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $children2WithGermanLang  = $this->input('children_2_with_german_lang', 0);
        $children4WithGermanLang  = $this->input('children_2_with_german_lang', 0);
        $children2WithForeignLang = $this->input('children_2_with_foreign_lang', 0);
        $children4WithForeignLang = $this->input('children_2_with_foreign_lang', 0);

        return [
            'year'                                     => array_merge($this->yearRules(), ['sometimes']),
            'kita'                                     => ['sometimes', $this->kitaExistRule()],
            'evaluations_without_daz_2_total_per_year' => array_merge($this->bigIntegerRules(), ['sometimes']),
            'evaluations_without_daz_4_total_per_year' => array_merge($this->bigIntegerRules(), ['sometimes']),
            'evaluations_with_daz_2_total_per_year'    => array_merge($this->bigIntegerRules(), ['sometimes']),
            'evaluations_with_daz_4_total_per_year'    => array_merge($this->bigIntegerRules(), ['sometimes']),
            'children_2_born_per_year'                 => array_merge($this->bigIntegerRules(), ['sometimes', 'in:' . $children2WithGermanLang + $children2WithForeignLang]),
            'children_4_born_per_year'                 => array_merge($this->bigIntegerRules(), ['sometimes', 'in:' . $children4WithGermanLang + $children4WithForeignLang]),
            'children_2_with_german_lang'              => array_merge($this->bigIntegerRules(), ['sometimes']),
            'children_4_with_german_lang'              => array_merge($this->bigIntegerRules(), ['sometimes']),
            'children_2_with_foreign_lang'             => array_merge($this->bigIntegerRules(), ['sometimes']),
            'children_4_with_foreign_lang'             => array_merge($this->bigIntegerRules(), ['sometimes']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return array_merge(parent::attributes(), [
            //
        ]);
    }
}
