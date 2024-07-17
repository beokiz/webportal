<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\DownloadableFiles;

use App\Http\Requests\BaseFormRequest;

/**
 * Create Downloadable File Request
 *
 * @package \App\Http\Requests\DownloadableFiles
 */
class CreateDownloadableFileRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => array_merge($this->textRules(), ['required']),
            'file' => array_merge($this->fileRules([], 61440), ['required']),
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            //
        ];
    }
}
