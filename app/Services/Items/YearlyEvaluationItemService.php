<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\YearlyEvaluation;
use Illuminate\Support\Arr;

/**
 * Yearly Evaluation Item Service
 *
 * @package \App\Services\Items
 */
class YearlyEvaluationItemService extends BaseItemService
{
    /**
     * YearlyEvaluationItemService constructor.
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
        $query = YearlyEvaluation::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc')
            ->with(['kita']);

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !YearlyEvaluation::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return YearlyEvaluation|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?YearlyEvaluation
    {
        return $throwExceptionIfFail
            ? YearlyEvaluation::findOrFail($id)
            : YearlyEvaluation::find($id);
    }

    /**
     * @param array $attributes
     * @return ?YearlyEvaluation
     */
    public function create(array $attributes) : ?YearlyEvaluation
    {
        $this->prepareAttributes($attributes);

        $item = YearlyEvaluation::create($attributes);

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
     * @param YearlyEvaluation $item
     * @param array            $attributes
     * @return void
     */
    protected function updateRelations(YearlyEvaluation $item, array $attributes) : void
    {
        //
    }
}
