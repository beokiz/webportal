<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Training;
use Illuminate\Support\Arr;

/**
 * Training Item Service
 *
 * @package \App\Services\Items
 */
class TrainingItemService extends BaseItemService
{
    /**
     * TrainingItemService constructor.
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
        $query = Training::query()->filter($filters)
            ->with(array_merge($with, ['multiplier']))
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Training::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Training|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Training
    {
        return $throwExceptionIfFail
            ? Training::findOrFail($id)
            : Training::find($id);
    }

    /**
     * @param array $attributes
     * @return ?Training
     */
    public function create(array $attributes) : ?Training
    {
        $this->prepareAttributes($attributes);

        $item = Training::create($attributes);

        if ($item->exists) {
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
        $item = $this->find($id);

        $this->prepareAttributes($attributes);

        if ($item->update($attributes)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int   $id
     * @param array $kitas
     * @param bool  $removeKitas
     * @return bool|null
     */
    public function updateAttachedKitas(int $id, array $kitas, bool $removeKitas = false) : ?bool
    {
        $item = $this->find($id);

        if (!empty($kitas)) {
            if ($removeKitas) {
                $item->kitas()->detach($kitas);
            } else {
                $trainingKitas    = $item->kitas->pluck('id');

                foreach ($kitas as $userId) {
                    $userId = (int) $userId;

                    if (!$trainingKitas->contains($userId)) {
                        $trainingKitas->add($userId);
                    }
                }

                if (!empty($trainingKitas)) {
                    $item->kitas()->sync($trainingKitas);
                }
            }

            // Reload the item with its relationships
            $item->load('kitas');

            // Get the sum of num_pedagogical_staff for all kitas & update participant_count in Training item
            $item->update(['participant_count' => $item->kitas->sum('num_pedagogical_staff')]);

            return true;
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
}
