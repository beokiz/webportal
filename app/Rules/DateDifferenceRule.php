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

        $firstDate  = Carbon::parse($this->value);
        $secondDate = Carbon::parse($value);

        // Ensure the difference is not negative
        if ($secondDate->lt($firstDate)) {
            return false;
        }

        $difference = $secondDate->diffInDays($firstDate);

        if ($this->comparisonType === 'greater_than') {
            return $difference >= $this->days;
        } elseif ($this->comparisonType === 'less_than') {
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
}
