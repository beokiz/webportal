<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\YearlyEvaluations;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * Create Survey Time Period Request
 *
 * @package \App\Http\Requests\YearlyEvaluations
 */
class CreateYearlyEvaluationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $children2WithGermanLang  = $this->input('children_2_with_german_lang', 0);
        $children4WithGermanLang  = $this->input('children_4_with_german_lang', 0);
        $children2WithForeignLang = $this->input('children_2_with_foreign_lang', 0);
        $children4WithForeignLang = $this->input('children_4_with_foreign_lang', 0);

        return [
            'year'                                     => array_merge($this->yearRules(), ['required', Rule::unique('yearly_evaluations')->where(function ($query) {
                return $query->where('kita_id', $this->input('kita_id'));
            })]),
            'kita_id'                                  => ['required', $this->kitaExistRule()],
            'evaluations_with_daz_2_total_per_year'    => array_merge($this->bigIntegerRules(true), ['required']),
            'evaluations_with_daz_4_total_per_year'    => array_merge($this->bigIntegerRules(true), ['required']),
            'evaluations_without_daz_2_total_per_year' => array_merge($this->bigIntegerRules(true), ['required']),
            'evaluations_without_daz_4_total_per_year' => array_merge($this->bigIntegerRules(true), ['required']),
            'children_2_born_per_year'                 => array_merge($this->bigIntegerRules(true), ['required', 'in:' . $children2WithGermanLang + $children2WithForeignLang]),
            'children_4_born_per_year'                 => array_merge($this->bigIntegerRules(true), ['required', 'in:' . $children4WithGermanLang + $children4WithForeignLang]),
            'children_2_with_german_lang'              => array_merge($this->bigIntegerRules(true), ['required']),
            'children_4_with_german_lang'              => array_merge($this->bigIntegerRules(true), ['required']),
            'children_2_with_foreign_lang'             => array_merge($this->bigIntegerRules(true), ['required']),
            'children_4_with_foreign_lang'             => array_merge($this->bigIntegerRules(true), ['required']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            'year' => __('validation.attributes.year_of_evaluations'),
        ];
    }
}
