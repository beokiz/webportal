<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

/**
 * Validator Service Provider
 *
 * @package \App\Providers
 */
class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->customizeBaseValidationRules();
        $this->additionalValidationRules();
    }

    /**
     * @return void
     */
    protected function customizeBaseValidationRules()
    {
        /**
         * Customize "gt" rule message (add child field name replacer)
         */
        Validator::replacer('gt', function ($message, $attribute, $rule, $parameters, $validator) {
            $parentFieldKey = $parameters[0];

            if (isset($parameters[1]) && filter_var($parameters[1], FILTER_VALIDATE_BOOLEAN)) {
                $fieldsNamesArr = __("validation.attributes");

                if (isset($fieldsNamesArr[$parentFieldKey])) {
                    $parentFieldName = $fieldsNamesArr[$parentFieldKey] ?? $parentFieldKey;

                    return str_replace(
                        [':attribute', ':parent_attribute'],
                        [$fieldsNamesArr[$attribute] ?? $attribute, $parentFieldName ?? $parentFieldKey],
                        __('validation.gt.numeric_custom')
                    );
                }
            }

            // Default message
            $data = Arr::dot($validator->getData());

            return !empty($data[$parentFieldKey])
                ? str_replace(':value', $data[$parentFieldKey], $message)
                : str_replace(':attribute', $attribute, __('validation.custom.invalid'));
        });

        /**
         * Customize "lt" rule message (add child field name replacer)
         */
        Validator::replacer('lt', function ($message, $attribute, $rule, $parameters, $validator) {
            $parentFieldKey = $parameters[0];

            if (isset($parameters[1]) && filter_var($parameters[1], FILTER_VALIDATE_BOOLEAN)) {
                $fieldsNamesArr = __("validation.attributes");

                if (isset($fieldsNamesArr[$parentFieldKey])) {
                    $parentFieldName = $fieldsNamesArr[$parentFieldKey] ?? $parentFieldKey;

                    return str_replace(
                        [':attribute', ':parent_attribute'],
                        [$fieldsNamesArr[$attribute] ?? $attribute, $parentFieldName ?? $parentFieldKey],
                        __('validation.lt.numeric_custom')
                    );
                }
            }

            // Default message
            $data = Arr::dot($validator->getData());

            return !empty($data[$parentFieldKey])
                ? str_replace(':value', $data[$parentFieldKey], $message)
                : str_replace(':attribute', $attribute, __('validation.custom.invalid'));
        });
    }

    /**
     * @return void
     */
    protected function additionalValidationRules()
    {
        /**
         * "Integer or Float" Validation
         *
         * @return void
         */
        Validator::extend('integer_or_float', function ($attribute, $value, array $parameters, $validator) {
            $afterDecimalMax  = (!empty($parameters[0]) && is_numeric($parameters[0])) ? $parameters[0] : 2;
            $beforeDecimalMax = (!empty($parameters[1]) && is_numeric($parameters[1])) ? $parameters[1] : 6;

            $validator->addReplacer('integer_or_float', function ($message, $attribute, $rule, $parameters) use ($beforeDecimalMax, $afterDecimalMax) {
                return str_replace([':before', ':after'], [$beforeDecimalMax, $afterDecimalMax], $message);
            });

            $pattern = "/^[-]?[0-9]{0,{$beforeDecimalMax}}(.[0-9]{1,{$afterDecimalMax}})?$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "Max decimal digits" Validation
         *
         * @return void
         */
        Validator::extend('max_decimal_digits', function ($attribute, $value, array $parameters, $validator) {
            $maxDecimalNumber = (!empty($parameters[0]) && is_numeric($parameters[0])) ? $parameters[0] : 10;

            $validator->addReplacer('max_decimal_digits', function ($message, $attribute, $rule, $parameters) use ($maxDecimalNumber) {
                return str_replace([':max'], $maxDecimalNumber, $message);
            });

            $pattern = "/^\d+(\.\d{1,{$maxDecimalNumber}})?$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "Timestamp" Validation
         *
         * @return void
         */
        Validator::extend('timestamp', function ($attribute, $value, array $parameters, $validator) {
            return is_timestamp($value);
        });

        /**
         * "Phone number" Validation
         *
         * @return void
         */
        Validator::extend('phone', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "UNIX Path" Validation
         *
         * @return void
         */
        Validator::extend('unix_path', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/^\/([a-zA-Z0-9_\/\-]+)$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "Windows Disk" Validation
         *
         * @return void
         */
        Validator::extend('windows_disk', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/^[A-Za-z]:\\\\$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "UNIX Path or Windows Disk" Validation
         *
         * @return void
         */
        Validator::extend('unix_path_or_windows_disk', function ($attribute, $value, array $parameters, $validator) {
            return $validator->validateUnixPath($attribute, $value, $parameters, $validator) ||
                $validator->validateWindowsDisk($attribute, $value, $parameters, $validator);
        });

        /**
         * "URL or IPv4" Validation
         *
         * @return void
         */
        Validator::extend('url_or_ipv4', function ($attribute, $value, array $parameters, $validator) {
            return $validator->validateUrl($attribute, $value) ||
                $validator->validateIpv4($attribute, $value);
        });

        /**
         * "FQDN" Validation
         *
         * @return void
         */
        Validator::extend('fqdn', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/(?=^.{4,253}$)(^((?!-)[a-zA-Z0-9-]{1,63}(?<!-)\.)+[a-zA-Z]{2,63}$)/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "FQDN or URL" Validation
         *
         * @return void
         */
        Validator::extend('fqdn_or_url', function ($attribute, $value, array $parameters, $validator) {
            return $validator->validateFqdn($attribute, $value, $parameters, $validator) ||
                $validator->validateUrl($attribute, $value);
        });

        /**
         * "FQDN or IPv4" Validation
         *
         * @return void
         */
        Validator::extend('fqdn_or_ipv4', function ($attribute, $value, array $parameters, $validator) {
            return $validator->validateFqdn($attribute, $value, $parameters, $validator) ||
                $validator->validateIpv4($attribute, $value);
        });

        /**
         * "URL, FQDN or IPv4" Validation
         *
         * @return void
         */
        Validator::extend('url_or_fqdn_or_ipv4', function ($attribute, $value, array $parameters, $validator) {
            return $validator->validateFqdn($attribute, $value, $parameters, $validator) ||
                $validator->validateUrl($attribute, $value) ||
                $validator->validateIpv4($attribute, $value);
        });

        /**
         * "Port Number" Validation
         *
         * @return void
         */
        Validator::extend('port_number', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/^([0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "URL Path" Validation
         *
         * @return void
         */
        Validator::extend('url_path', function ($attribute, $value, array $parameters, $validator) {
            $pattern = "/^\/([a-zA-Z0-9\-\.\_\~\:\/\?\%\#\[\]\@\!\$\&\'\(\)\*\+\,\;\=]{0,8192})$/";

            return preg_match($pattern, $value) > 0;
        });

        /**
         * "CIDR" Validation
         *
         * @return void
         */
        Validator::extend('cidr', function ($attribute, $value, array $parameters, $validator) {
            return is_valid_cidr($value);
        });

        /**
         * "Not Present" Validation
         *
         * @return void
         */
        Validator::extend('not_present', function ($attribute, $value, array $parameters, $validator) {
            return !array_key_exists($attribute, Arr::dot($validator->getData()));
        });

        /**
         * "Empty with" Validation
         *
         * @return void
         */
        Validator::extend('not_present_with', function ($attribute, $value, array $parameters, $validator) {
            if (empty($parameters)) {
                throw new InvalidArgumentException('Validation rule "not_present_with" requires 1 parameter.');
            }

            $fieldKey = str_replace(' ', '', array_shift($parameters));

            $validator->addReplacer('not_present_with', function ($message, $attribute, $rule, $parameters) use ($fieldKey) {
                return str_replace([':value'], [$fieldKey], $message);
            });

            return !array_key_exists($fieldKey, Arr::dot($validator->getData()));
        });

        /**
         * "File name" Validation
         *
         * @return void
         */
        Validator::extend('file_name', function ($attribute, $value, array $parameters, $validator) {
            if (empty($parameters)) {
                throw new InvalidArgumentException('Validation rule "file_name" requires 1 parameter.');
            }

            $types   = implode('|', $parameters);
            $pattern = "/^.*\.({$types})$/i";

            $validator->addReplacer('file_name', function ($message, $attribute, $rule, $parameters) {
                return str_replace([':values'], implode(', ', $parameters), $message);
            });

            return preg_match($pattern, $value) > 0;
        });
    }
}
