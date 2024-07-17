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
     * @param array $with
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
        $query = Kita::query()->filter($filters);

        if (!empty($params->order_by) && $params->order_by === 'has_yearly_evaluations') {
            $query->select('*')
                ->selectSub(function ($query) {
                    $query->from('yearly_evaluations')
                        ->selectRaw('count(*)')
                        ->whereColumn('yearly_evaluations.kita_id', 'kitas.id');
                }, 'yearly_evaluations_count')
                ->orderBy('yearly_evaluations_count', $params->sort === 'desc' ? 'desc' : 'asc');
        } else {
            $query->customOrderBy($params->order_by ?? 'order', $params->sort === 'desc');
        }

        if (!empty($args['with'])) {
            $query->with($args['with']);
        }

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
                $kitaUsers    = $item->users->pluck('id');
                $kitaUsersNtf = collect([]);

                foreach ($users as $userId) {
                    $userId = (int) $userId;

                    if (!$kitaUsers->contains($userId)) {
                        $kitaUsers->add($userId);
                        $kitaUsersNtf->add($userId);
                    }
                }

                if (!empty($kitaUsers)) {
                    $item->users()->sync($kitaUsers);

                    $item->users()->whereIn('id', $kitaUsersNtf)->get()->map(function ($user) use ($item) {
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
     * @param array $ids
     * @param bool $withoutYearlyEvaluations
     * @return array
     */
    public function getUsersEmails(array $ids = [], bool $withoutYearlyEvaluations = false) : array
    {
        $emails = [];

        Kita::where('approved', true)
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })
            ->when(!empty($withoutYearlyEvaluations), function ($query) use ($ids) {
                $query->whereDoesntHave('currentYearlyEvaluations');
            })
            ->with(['users.roles'])
            ->get()
            ->each(function ($kita) use (&$emails) {
                $kita->users
                    ->filter(function ($user) {
                        return $user->hasRole(config('permission.project_roles.manager'));
                    })
                    ->each(function ($user) use (&$emails) {
                        $emails[$user->email] = [
                            'title' => "{$user->full_name} <{$user->email}>",
                            'value' => $user->email,
                        ];
                    });
            });

        return array_values($emails);
    }

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
