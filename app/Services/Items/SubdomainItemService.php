<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Subdomain;
use Batch;
use Illuminate\Support\Arr;

/**
 * Subdomain Item Service
 *
 * @package \App\Services\Items
 */
class SubdomainItemService extends BaseItemService
{
    /**
     * SubdomainItemService constructor.
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
        $query = Subdomain::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'order', $params->sort === 'desc')
            ->with([
                'domain',
                'milestones' => function ($query) {
                    $query->orderBy('order');
                },
            ]);

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Subdomain::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Subdomain|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Subdomain
    {
        return $throwExceptionIfFail
            ? Subdomain::findOrFail($id)
            : Subdomain::find($id);
    }

    /**
     * @param array $attributes
     * @return ?Subdomain
     */
    public function create(array $attributes) : ?Subdomain
    {
        if (empty($attributes['order'])) {
            $attributes['order'] = Subdomain::max('order') + 1;
        }

        $this->prepareAttributes($attributes);

        $item = Subdomain::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item->loadMissing(['milestones' => function ($query) {
                $query->orderBy('order');
            }]);
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
            return Batch::update(new Subdomain, $attributes['items'], 'id');
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
        if (!empty($attributes['domain'])) {
            $attributes['domain_id'] = $attributes['domain'];

            unset($attributes['domain']);
        }
    }

    /**
     * @param Subdomain $item
     * @param array     $attributes
     * @return void
     */
    protected function updateRelations(Subdomain $item, array $attributes) : void
    {
        //
    }
}
