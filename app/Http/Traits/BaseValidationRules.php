<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

/**
 * Trait for returning base validation rules
 *
 * @package \App\Http\Traits
 */
trait BaseValidationRules
{
    /**
     * @param int|string|null $max
     * @param int|string|null $min
     * @return array
     */
    protected function textRules($max = 255, $min = 1) : array
    {
        $rules = ['string'];

        if (is_numeric($min)) {
            $rules[] = "min:{$min}";
        }

        if (is_numeric($max)) {
            $rules[] = "max:{$max}";
        }

        return $rules;
    }

    /**
     * @return array
     */
    protected function mediumTextRules() : array
    {
        return $this->textRules(4096);
    }

    /**
     * @return array
     */
    protected function bigTextRules() : array
    {
        return $this->textRules(8192);
    }

    /**
     * @param int|string|null $max
     * @param int|string|null $min
     * @return array
     */
    protected function baseIntegerRules($max, $min = 1) : array
    {
        $rules = ['integer'];

        if (is_numeric($min)) {
            $rules[] = "min:{$min}";
        }

        if (is_numeric($max)) {
            $rules[] = "max:{$max}";
        }

        return $rules;
    }

    /**
     * @param bool $unsigned
     * @param bool $allowNull
     * @return array
     */
    protected function tinyIntegerRules(bool $unsigned = true, bool $allowNull = false) : array
    {
        return $this->baseIntegerRules(
            $unsigned ? 255 : 127,
            $allowNull ? 0 : 1
        );
    }

    /**
     * @param bool $unsigned
     * @param bool $allowNull
     * @return array
     */
    protected function smallIntegerRules(bool $unsigned = true, bool $allowNull = false) : array
    {
        return $this->baseIntegerRules(
            $unsigned ? 65535 : 32767,
            $allowNull ? 0 : 1
        );
    }

    /**
     * @param bool $unsigned
     * @param bool $allowNull
     * @return array
     */
    protected function mediumIntegerRules(bool $unsigned = true, bool $allowNull = false) : array
    {
        return $this->baseIntegerRules(
            $unsigned ? 16777215 : 8388607,
            $allowNull ? 0 : 1
        );
    }

    /**
     * @param bool $unsigned
     * @param bool $allowNull
     * @return array
     */
    protected function integerRules(bool $unsigned = true, bool $allowNull = false) : array
    {
        return $this->baseIntegerRules(
            $unsigned ? 4294967295 : 2147483647,
            $allowNull ? 0 : 1
        );
    }

    /**
     * @param bool $allowNull
     * @return array
     */
    protected function bigIntegerRules(bool $allowNull = false) : array
    {
        return $this->baseIntegerRules(
            PHP_INT_MAX,
            $allowNull ? 0 : 1
        );
    }

    /**
     * @param int $afterDecimalMax
     * @param int $beforeDecimalMax
     * @return array
     */
    protected function floatRules(int $afterDecimalMax = 2, int $beforeDecimalMax = 6) : array
    {
        return ['numeric', "integer_or_float:{$afterDecimalMax},{$beforeDecimalMax}"];
    }

    /**
     * @param int $to
     * @param int $from
     * @return array
     */
    protected function intervalRules(int $to = 14400, int $from = 1) : array
    {
        return ['integer', "between:{$from},{$to}"];
    }

    /**
     * @param int|null $maxDecimalNumber
     * @param bool     $allowNull
     * @return array
     */
    protected function percentageRules(?int $maxDecimalNumber = null, bool $allowNull = true) : array
    {
        $rules = ['numeric', 'between:0,100'];

        if (!$allowNull) {
            $rules[] = 'not_in:0';
        }

        if ($maxDecimalNumber && is_numeric($maxDecimalNumber)) {
            $rules[] = "max_decimal_digits:{$maxDecimalNumber}";
        }

        return $rules;
    }

    /**
     * @param boolean $mustBeConfirmed
     * @param boolean $checkOldPassword
     * @return array
     */
    protected function passwordRules(bool $mustBeConfirmed = true, bool $checkOldPassword = true) : array
    {
        $rules = [
            'min:6',
            // OLD (1 latin letter & 1 number): /^(?=.*[a-zA-Z])(?=.*\d).+$/
            // CURRENT: 1 letter (in any language) & 1 number
            'regex:/^(?=.*[{\p{L}}])(?=.*\d).+$/',
        ];

        if ($mustBeConfirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

    /**
     * @param array $fields
     * @return array
     */
    public function requiredWithoutAllRules(array $fields = []) : array
    {
        $rules  = [];
        $fields = array_filter($fields);

        if (!empty($fields)) {
            $rules[] = 'required_without_all:' . implode(',', $fields);
        }

        return $rules;
    }
}
