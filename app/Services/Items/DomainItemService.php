<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Domain;
use Batch;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Domain Item Service
 *
 * @package \App\Services\Items
 */
class DomainItemService extends BaseItemService
{
    /**
     * DomainItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $args
     * @param array $with
     * @return mixed
     */
    public function collection(array $args = [], array $with = [])
    {
        /*
         * Define params
         */
        $params  = $this->prepareCollectionParams($args);
        $filters = Arr::except($args, array_keys((array) $params));

        /*
         * Filter & order query
         */
        $query = Domain::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'order', $params->sort === 'desc');

        if (!empty($with)) {
            $query->with($with);
        } else {
            $query->with([
                'subdomains' => function ($query) {
                    $query->orderBy('order');
                }
            ]);
        }

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Domain::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Domain|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Domain
    {
        return $throwExceptionIfFail
            ? Domain::findOrFail($id)
            : Domain::find($id);
    }

    /**
     * @param array $attributes
     * @return ?Domain
     */
    public function create(array $attributes) : ?Domain
    {
        if (empty($attributes['order'])) {
            $attributes['order'] = Domain::max('order') + 1;
        }

        $this->prepareAttributes($attributes);

        $item = Domain::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item->loadMissing(['subdomains']);
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

        if ($item->update($attributes)) {
            $this->updateRelations($item, $attributes);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id) : ?bool
    {
        return $this->find($id)->delete();
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function reorder(array $attributes) : bool
    {
        if (!empty($attributes['items'])) {
            return Batch::update(new Domain, $attributes['items'], 'id');
        } else {
            return false;
        }
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
     * @param Domain $item
     * @param array  $attributes
     * @return void
     */
    protected function updateRelations(Domain $item, array $attributes) : void
    {
        //
    }
}
