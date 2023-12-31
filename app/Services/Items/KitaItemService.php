<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Kita;
use Batch;
use Illuminate\Support\Arr;

/**
 * Kita Item Service
 *
 * @package \App\Services\Items
 */
class KitaItemService extends BaseItemService
{
    /**
     * KitaItemService constructor.
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
        $query = Kita::query()->filter($filters)
            ->customOrderBy($params->order_by ?? 'order', $params->sort === 'desc');

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !Kita::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Kita|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Kita
    {
        return $throwExceptionIfFail
            ? Kita::findOrFail($id)
            : Kita::find($id);
    }

    /**
     * @param array $attributes
     * @return ?Kita
     */
    public function create(array $attributes) : ?Kita
    {
        if (empty($attributes['order'])) {
            $attributes['order'] = Kita::max('order') + 1;
        }

        $this->prepareAttributes($attributes);

        $item = Kita::create($attributes);

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
                $kitaUsers = $item->users->pluck('id');

                foreach ($users as $userId) {
                    $userId = (int) $userId;

                    if (!$kitaUsers->contains($userId)) {
                        $kitaUsers->add($userId);
                    }
                }

                if (!empty($kitaUsers)) {
                    $item->users()->sync($kitaUsers);

                    $item->users()->whereIn('id', $kitaUsers)->get()->map(function ($user) use ($item) {
                        $user->sendConnectedToKitasNotification(
                            $user->kitas()
                                ->pluck('name')
                                ->toArray()
                        );
                    });
                }
            }

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

    /**
     * @param array $attributes
     * @return bool
     */
    public function reorder(array $attributes) : bool
    {
        if (!empty($attributes['items'])) {
            return Batch::update(new Kita, $attributes['items'], 'id');
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
     * @param Kita  $item
     * @param array $attributes
     * @return void
     */
    protected function updateRelations(Kita $item, array $attributes) : void
    {
        //
    }
}
