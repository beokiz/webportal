<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

/**
 * Date Difference Rule
 *
 * @package \App\Rules
 */
class DateDifferenceRule implements Rule
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var string
     */
    private $comparisonType;

    /**
     * Create a new rule instance.
     *
     * @param string      $attribute
     * @param string|null $value
     * @param int         $days
     * @param string      $comparisonType ('greater_than' or 'less_than')
     * @return void
     */
    public function __construct(string $attribute, ?string $value, int $days = 1, string $comparisonType = 'less_than')
    {
        $this->attribute      = $attribute;
        $this->value          = $value;
        $this->days           = $days;
        $this->comparisonType = $comparisonType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        if (empty($this->value)) {
            return false;
        }

        // Possible date formats
        $formats = [
            'd/m/Y, H:i:s',   // Example: 18/01/2025, 01:00:00
            'm/d/Y H:i:s',    // Example: 01/18/2025 01:00:00
            'd.m.Y, H:i:s',   // Example: 18.01.2025 01:00:00
            'd.m.Y H:i:s',    // Example: 18.01.2025 01:00:00
            'Y-m-d H:i:s',    // Example: 2025-01-18 01:00:00
            'Y-m-d',          // Example: 2025-01-18
            'd/m/Y',          // Example: 18/01/2025
        ];

        $firstDate  = $this->tryParseDate($this->value, $formats);
        $secondDate = $this->tryParseDate($value, $formats);

        // If one of the dates wasn't successfully parsed, return false
        if (!$firstDate || !$secondDate) {
            return false;
        }

        // Ensure the difference is not negative
        if ($secondDate->lt($firstDate)) {
            return false;
        }

        $difference = $secondDate->diffInDays($firstDate);

        if ($this->comparisonType === 'greater_than') {
            return $difference >= $this->days;
        } else if ($this->comparisonType === 'less_than') {
            return $difference <= $this->days;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        $otherField = __("validation.attributes.{$this->attribute}");

        $messageKey = $this->comparisonType === 'greater_than'
            ? 'validation.date_difference_greater'
            : 'validation.date_difference_less';

        return __($messageKey, [
            'other_field' => $otherField,
            'days'        => $this->days,
        ]);
    }

    /**
     * Attempts to parse a date using multiple formats.
     *
     * @param string $date
     * @param array  $formats
     * @return \Carbon\Carbon|false
     */
    private function tryParseDate($date, array $formats) : \Carbon\Carbon|false
    {
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (\Exception $e) {
                // Catch the exception and continue checking other formats
                continue;
            }
        }

        // If no format matches, return false
        return false;
    }
}
