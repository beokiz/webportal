<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

use App\Models\Evaluation;
use Illuminate\Validation\Rule;

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
     * @return array
     */
    protected function passwordRules(bool $mustBeConfirmed = true) : array
    {
        // ^ - Start of the string.
        // (?=.*[A-Z]) - Must contain at least one uppercase letter.
        // (?=.*[a-z]) - Must contain at least one lowercase letter.
        // (?=.*\d) - Must contain at least one digit.
        // (?=.*[\p{P}\p{S}]) - Must contain at least one special character.
        // \p{P} and \p{S} match any punctuation or symbol characters, which includes non-English characters.
        // (?=.*[\p{L}]) - Must contain at least one letter (can be non-English letter).
        // .{8,} - Must have a minimum length of 8 characters.
        // $/u - End of the string. The 'u' flag is used to support Unicode characters.

        // This regular expression enforces the following criteria for a password:
        // - At least one uppercase letter.
        // - At least one lowercase letter.
        // - At least one digit.
        // - At least one letter (including non-English letters).
        // - Minimum length of 8 characters.

        $rules = [
            'min:8',
//            'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\p{P}\p{S}])(?=.*[\p{L}]).{8,}$/u',
            'regex:/^(?=.*[A-ZÄÖÜ])(?=.*[a-zäöüß])(?=.*\d).{8,}$/u',
        ];

        // Also can be used
        // $rules[] = Rules\Password::defaults()

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

    /**
     * @return array
     */
    public function yearRules() : array
    {
        return ['integer', 'min:1900', 'max:' . date('Y')];
    }

    /**
     * @return array
     */
    public function ageGroupRules() : array
    {
        return [Rule::in(Evaluation::CHILD_AGE_GROUPS)];
    }

    /**
     * @param array $mimeTypes
     * @param int $maxKbSize
     * @return array
     */
    public function fileRules(array $mimeTypes = [], int $maxKbSize = 3072) : array
    {
        $rules = ['file', "max:{$maxKbSize}"];

        if (!empty($mimeTypes)) {
            $rules[] = 'mimetypes:' . implode(',', $mimeTypes);
        }

        return $rules;
    }

    /**
     * @param int $maxKbSize
     * @return array
     */
    public function imageFileRules(int $maxKbSize = 3072) : array
    {
        return $this->fileRules(['image/jpg', 'image/jpeg', 'image/png'], $maxKbSize);
    }
}
