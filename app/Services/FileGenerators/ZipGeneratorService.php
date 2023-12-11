<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App\Exceptions\Custom\FileGeneratorException;
use App\Interfaces\FileGenerators\ArchiveGeneratorServiceInterface;
use ZipArchive;

/**
 * Zip Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class ZipGeneratorService extends BaseFileGeneratorService implements ArchiveGeneratorServiceInterface
{
    /**
     * CsvGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string       $fileName
     * @param string|array $files
     * @return string|bool
     */
    public function create(string $fileName, $files)
    {
        try {
            $ds = DIRECTORY_SEPARATOR;

            $path = config('filesystems.disks.local_tmp.root') . $ds . uniqid($fileName . '_') . '.zip';

            $zip = new ZipArchive();

            $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ((array) $files as $file) {
                if (file_exists($file) && !is_dir($file)) {
                    $zip->addFile($file, basename($file));
                }
            }

            $zip->close();

            return $path;
        } catch (\Exception $exception) {
            if (!App::environment('production')) {
                throw new FileGeneratorException($exception->getMessage(), $exception->getCode(), $exception);
            }

            return false;
        }
    }
}
