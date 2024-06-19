<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

use Illuminate\Validation\Rule;

/**
 * Trait for returning prepared "exist" validation rules
 *
 * @package \App\Http\Traits
 */
trait ExistValidationRules
{
    /**
     * @param string $table
     * @param string $column
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function existByColumnRule(string $table, string $column = 'id') : \Illuminate\Validation\Rules\Exists
    {
        return Rule::exists($table, $column);
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function userExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('users');
    }

    /**
     * @param array|string|null $names
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function roleExistRule($names) : \Illuminate\Validation\Rules\Exists
    {
        $rule = $this->existByColumnRule('roles');

        if ($names) {
            $rule->whereIn('name', (array) $names);
        }

        return $rule;
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function domainExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('domains');
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function subdomainExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('subdomains');
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function milestoneExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('milestones');
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function kitaExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('kitas');
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function settingExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('settings', 'name');
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    protected function operatorExistRule() : \Illuminate\Validation\Rules\Exists
    {
        return $this->existByColumnRule('operators');
    }
}
