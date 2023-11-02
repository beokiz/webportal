<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App;
use App\Exceptions\Custom\FileGeneratorException;
use App\Interfaces\FileGenerators\CsvGeneratorServiceInterface;

/**
 * Csv Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class CsvGeneratorService extends BaseFileGeneratorService implements CsvGeneratorServiceInterface
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
     * @param string $fileName
     * @param mixed  $data
     * @param bool   $returnAsBase64
     * @return string|bool
     */
    public function create(string $fileName, $data, bool $returnAsBase64)
    {
        try {
            $ds = DIRECTORY_SEPARATOR;

            $path = config('filesystems.disks.local_tmp.root') . $ds . uniqid($fileName . '_') . '.csv';

            $file = fopen($path, 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);

            if ($returnAsBase64) {
                return base64_encode_file($path, true);
            }

            return $path;
        } catch (\Exception $exception) {
            if (!App::environment('production')) {
                throw new FileGeneratorException($exception->getMessage(), $exception->getCode(), $exception);
            }

            return false;
        }
    }
}
