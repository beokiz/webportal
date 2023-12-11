<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Helpers;

use Illuminate\Filesystem\Filesystem;

/**
 * Stub Generator Helper
 *
 * @package \App\Helpers
 */
class StubGeneratorHelper
{
    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * StubGeneratorHelper constructor.
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Generate file from stub file
     *
     * @param string $stubPath
     * @param string $namespace
     * @param string $fileName
     * @param array  $stubVars
     * @return string
     */
    public function makeFromStub(string $stubPath, string $namespace, string $fileName, array $stubVars = []) : string
    {
        if (!$this->filesystem->exists($stubPath)) {
            return __('helpers.stub_generator_stub_not_exist_error', ['path' => $stubPath]);
        }

        $filePath = sprintf('%s\\%s', $namespace, $fileName);

        $this->makeDirectory(dirname($filePath));

        if ($this->filesystem->exists($filePath)) {
            return __('helpers.stub_generator_file_already_exists_error', ['filename' => $fileName]);
        }

        $created = $this->filesystem->put(
            $filePath,
            $this->getStubContents($stubPath, $stubVars)
        );

        return $created !== false
            ? __('helpers.stub_generator_success', ['path' => $filePath])
            : __('helpers.stub_generator_error', ['filename' => $fileName]);
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param       $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    protected function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('{{ ' . trim(strtoupper($search)) . ' }}', $replace, $contents);
        }

        return $contents;

    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path) : string
    {
        if (!$this->filesystem->isDirectory($path)) {
            $this->filesystem->makeDirectory($path, 0755, true, true);
        }

        return $path;
    }
}
