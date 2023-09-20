<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App\Interfaces\FileGenerators\ImageGeneratorServiceInterface;
use Spatie\Browsershot\Browsershot;

/**
 * Spatie Image Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class SpatieImageGeneratorService extends BaseFileGeneratorService implements ImageGeneratorServiceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * SpatieImageGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->options = config('browsershot.image');
    }

    /**
     * @param string $fileName
     * @param mixed  $html
     * @param bool   $returnAsBase64
     * @param array  $options
     * @return string|bool
     */
    public function createFromHtml(string $fileName, $html, array $options = [], bool $returnAsBase64 = false)
    {
        try {
            $ds       = DIRECTORY_SEPARATOR;
            $basePath = !empty($options['base_path']) ? $options['base_path'] : config('filesystems.disks.local_tmp.root');

            $image = Browsershot::html($html)
                ->fullPage();

            foreach (array_merge($this->options, $options) as $option => $value) {
                if ($option === 'timeout') {
                    $image->timeout($value);
                } else if ($option === 'type') {
                    $image->setScreenshotType($value);
                } else {
                    $image->setOption($option, $value);
                }
            }

            if ($returnAsBase64) {
                return $image->base64Screenshot();
            }

            $path = $basePath . $ds . uniqid($fileName . '_') . '.png';

            $image->save($path);

            return $path;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $templateName
     * @param mixed  $data
     * @param bool   $returnAsBase64
     * @param array  $options
     * @return mixed
     */
    public function createFromBlade(string $templateName, $data, array $options = [], bool $returnAsBase64 = false)
    {
        $html = $this->getHtmlFromBlade($templateName, $data);

        return $this->createFromHtml($templateName, $html, $options, $returnAsBase64);
    }
}
