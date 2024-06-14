<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\DownloadableFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * DownloadableFiles Item Service
 *
 * @package \App\Services\Items
 */
class DownloadableFilesItemService extends BaseItemService
{
    /**
     * SettingItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function collection(array $args = [])
    {
        /*
         * Define params
         */
        $params  = $this->prepareCollectionParams($args);
        $filters = Arr::except($args, array_keys((array) $params));

        /*
         * Filter & order query
         */
        $query = DownloadableFile::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !DownloadableFile::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return DownloadableFile|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?DownloadableFile
    {
        return $throwExceptionIfFail
            ? DownloadableFile::findOrFail($id)
            : DownloadableFile::find($id);
    }

    /**
     * @param string $name
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    protected function findByName(string $name, bool $throwExceptionIfFail = false) : mixed
    {
        $query = DownloadableFile::where('name', $name);

        return $throwExceptionIfFail
            ? $query->firstOrFail()
            : $query->first();
    }

    /**
     * @param array $attributes
     * @return ?DownloadableFile
     */
    public function create(array $attributes) : ?DownloadableFile
    {
        $this->prepareAttributes($attributes);

        $item = DownloadableFile::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item;
        } else {
            return null;
        }
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes) : bool
    {
        $item = $this->find($id, true);

        $this->prepareAttributes($attributes);

        return $item->update($attributes);
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function bulkUpdate(array $attributes) : bool
    {
        if (!empty($attributes['settings']) && is_array($attributes['settings'])) {
            $settings = [];

            foreach ($attributes['settings'] as $name => $value) {
                $settings[] = [
                    'name'  => $name,
                    'value' => $value,
                ];
            }

            if (!empty($settings)) {
                DownloadableFile::upsert($settings, 'name');

                return true;
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id) : ?bool
    {
        return $this->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param array $attributes
     * @return void
     */
    protected function prepareAttributes(array &$attributes) : void
    {
        $storage = Storage::disk('public_files');

        // Prepare 'file' attribute
        $hasFile = !empty($attributes['file']);

        if ($hasFile && $attributes['file'] instanceof UploadedFile) {
            $attributes['filename'] = $storage->putFileAs('/', $attributes['file'], clean_file_name($attributes['file']->getClientOriginalName()));

            unset($attributes['file']);
        }
    }

    /**
     * @param DownloadableFile $item
     * @param array            $attributes
     * @return void
     */
    protected function updateRelations(DownloadableFile $item, array $attributes) : void
    {
        //
    }
}
