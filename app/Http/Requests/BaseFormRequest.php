<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests;

use App\Http\Traits\BaseValidationRules;
use App\Http\Traits\ExistValidationRules;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Base Form Request
 *
 * @package \App\Http\Requests
 */
abstract class BaseFormRequest extends FormRequest
{
    use BaseValidationRules, ExistValidationRules;

    /**
     * @param array $fields
     * @return void
     */
    protected function prepareBooleanFieldsForValidation(array $fields) : void
    {
        $preparedFields = [];

        /*
         * Prepare each boolean field
         */
        foreach ($fields as $field) {
            if ($this->has($field)) {
                $preparedFields[$field] = $this->boolean($field);
            }
        }

        /*
         * Add prepared fields to current request
         */
        $this->merge(Arr::undot(
            array_merge(Arr::dot($this->all()), $preparedFields)
        ));
    }

    /**
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * @param string  $throttleKey
     * @param integer $maxAttempts
     * @param string  $msgKey
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(string $throttleKey, int $maxAttempts = 5, string $msgKey = '') : void
    {
        if (!RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($throttleKey);

        $msgKey = !empty($msgKey) ? $msgKey : 'validation.custom.throttle';

        throw ValidationException::withMessages([
            'throttle' => trans($msgKey, [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * @param mixed $attributeKeys
     * @return array
     */
    public function attributeWithIndex(mixed ...$attributeKeys) : array
    {
        $preparedAttributes = [];

        foreach ($attributeKeys as $key) {
            // TODO: Переделать под Validator::replacer()
            $requestData     = $this->input($key);
            $attributesRules = filter_array_by_prefix($this->rules(), "{$key}.");

            $validationAttributesLines = __('validation.attributes');

            for ($counter = 1; $counter <= count($requestData); $counter++) {
                foreach (array_keys($attributesRules) as $rule) {
                    $parts = explode('.', $rule);
                    $index = $parts[1];
                    $field = $parts[2];

                    $key = str_replace('*', $counter - 1, $rule);

                    $preparedAttributes[$key] = array_key_exists($rule, $validationAttributesLines)
                        ? str_replace(':counter', $counter, $validationAttributesLines[$rule])
                        : sprintf('%1$s #%2$d', ucfirst(str_replace(['-', '_'], ' ', $field)), $counter);

                }
            }
        }

        return $preparedAttributes;
    }
}
