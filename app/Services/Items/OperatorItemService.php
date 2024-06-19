<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Operator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Operator Item Service
 *
 * @package \App\Services\Items
 */
class OperatorItemService extends BaseItemService
{
    /**
     * OperatorItemService constructor.
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
        $query = Operator::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Operator::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Operator|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Operator
    {
        return $throwExceptionIfFail
            ? Operator::findOrFail($id)
            : Operator::find($id);
    }

    /**
     * @param string $name
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    protected function findByName(string $name, bool $throwExceptionIfFail = false) : mixed
    {
        $query = Operator::where('name', $name);

        return $throwExceptionIfFail
            ? $query->firstOrFail()
            : $query->first();
    }

    /**
     * @param array $attributes
     * @return ?Operator
     */
    public function create(array $attributes) : ?Operator
    {
        $this->prepareAttributes($attributes);

        $item = Operator::create($attributes);

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
     * @param int   $id
     * @param array $users
     * @param bool  $removeUsers
     * @return bool|null
     */
    public function updateAttachedUsers(int $id, array $users, bool $removeUsers = false) : ?bool
    {
        $item = $this->find($id);

        if (!empty($users)) {
            if ($removeUsers) {
                $item->users()->detach($users);
            } else {
                $operatorUsers = $item->users->pluck('id');

                foreach ($users as $userId) {
                    $userId = (int) $userId;

                    if (!$operatorUsers->contains($userId)) {
                        $operatorUsers->add($userId);
                    }
                }

                if (!empty($operatorUsers)) {
                    $item->users()->sync($operatorUsers);
                }
            }

            return true;
        }

        return false;
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
                Operator::upsert($settings, 'name');

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
        //
    }

    /**
     * @param Operator $item
     * @param array    $attributes
     * @return void
     */
    protected function updateRelations(Operator $item, array $attributes) : void
    {
        //
    }
}
