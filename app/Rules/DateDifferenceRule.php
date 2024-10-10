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
     * Create a new rule instance.
     *
     * @param string      $attribute
     * @param string|null $value
     * @param int         $days
     * @return void
     */
    public function __construct(string $attribute, ?string $value, int $days = 1)
    {
        $this->attribute = $attribute;
        $this->value     = $value;
        $this->days      = $days;
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

        dd();
        return $secondDate->diffInDays($firstDate) >= $this->days;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        $otherField = __("validation.attributes.{$this->attribute}");

        return __('validation.date_difference', [
            'other_field' => $otherField,
            'days'        => $this->days,
        ]);
    }
}
