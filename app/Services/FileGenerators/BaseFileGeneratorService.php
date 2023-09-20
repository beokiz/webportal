<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

/**
 * Base File Generator Service
 *
 * @package \App\Services\FileGenerators
 */
abstract class BaseFileGeneratorService
{
    /**
     * BaseFileGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param string $name
     * @param mixed  $data
     * @return string
     */
    protected function getHtmlFromBlade(string $name, $data) : string
    {
        return view($name, compact('data'))
            ->render();
    }
}
