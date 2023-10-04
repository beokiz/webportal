<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Interfaces\FileGenerators;

/**
 * Archive Generator Service Interface
 *
 * @package \App\Interfaces\FileGenerators
 */
interface ArchiveGeneratorServiceInterface
{
    /**
     * @param string       $fileName
     * @param string|array $files
     * @return string|bool
     */
    public function create(string $fileName, $files);
}
