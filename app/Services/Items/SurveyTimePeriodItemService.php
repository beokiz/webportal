<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\SurveyTimePeriod;
use Illuminate\Support\Arr;

/**
 * Survey Time Period Item Service
 *
 * @package \App\Services\Items
 */
class SurveyTimePeriodItemService extends BaseItemService
{
    /**
     * SurveyTimePeriodItemService constructor.
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
        $query = SurveyTimePeriod::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !SurveyTimePeriod::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return SurveyTimePeriod|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?SurveyTimePeriod
    {
        return $throwExceptionIfFail
            ? SurveyTimePeriod::findOrFail($id)
            : SurveyTimePeriod::find($id);
    }

    /**
     * @param array $attributes
     * @return ?SurveyTimePeriod
     */
    public function create(array $attributes) : ?SurveyTimePeriod
    {
        $this->prepareAttributes($attributes);

        $item = SurveyTimePeriod::create($attributes);

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
     * @param SurveyTimePeriod $item
     * @param array            $attributes
     * @return void
     */
    protected function updateRelations(SurveyTimePeriod $item, array $attributes) : void
    {
        //
    }
}
