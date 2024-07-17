<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\DownloadableFiles;

/**
 * Update Downloadable File Request
 *
 * @package \App\Http\Requests\DownloadableFiles
 */
class UpdateDownloadableFileRequest extends CreateDownloadableFileRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => array_merge($this->textRules(), ['sometimes']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return array_merge(parent::attributes(), [
            //
        ]);
    }
}
