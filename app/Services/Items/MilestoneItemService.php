<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Milestone;
use Batch;
use Illuminate\Support\Arr;

/**
 * Milestone Item Service
 *
 * @package \App\Services\Items
 */
class MilestoneItemService extends BaseItemService
{
    /**
     * MilestoneItemService constructor.
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
        $query = Milestone::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'order', $params->sort === 'desc')
            ->with(['subdomain']);

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Milestone::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Milestone|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Milestone
    {
        return $throwExceptionIfFail
            ? Milestone::findOrFail($id)
            : Milestone::find($id);
    }

    /**
     * @param array $attributes
     * @return ?Milestone
     */
    public function create(array $attributes) : ?Milestone
    {
        $this->prepareAttributes($attributes);

        $item = Milestone::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item->loadMissing(['subSubdomains']);
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
            return Batch::update(new Milestone, $attributes['items'], 'id');
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
        if (empty($attributes['subdomain'])) {
            $attributes['subdomain_id'] = $attributes['subdomain'];

            unset($attributes['subdomain']);
        }

        if (empty($attributes['order'])) {
            $attributes['order'] = Milestone::max('order') + 1;
        }
    }

    /**
     * @param Milestone $item
     * @param array     $attributes
     * @return void
     */
    protected function updateRelations(Milestone $item, array $attributes) : void
    {
        //
    }
}
