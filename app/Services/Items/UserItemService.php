<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * User Item Service
 *
 * @package \App\Services\Items
 */
class UserItemService extends BaseItemService
{
    /**
     * UserItemService constructor.
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
        $query = User::query()->filter($filters)
            ->with(['roles']);

        // Check if we need to sort by role name
        if (isset($params->order_by) && $params->order_by === 'primary_role_name') {
            $query->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('users.*') // Ensure we are selecting all user fields
                ->orderBy('roles.name', $params->sort === 'desc' ? 'desc' : 'asc');
        } else {
            $query->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');
        }

        /*
         * Return results
         */
        if ($params->paginated) {
            $result = $query->paginateFilter($params->per_page);

            // Check if table is totally empty && add additional params
            $result->additionalMeta = [];

            if ($result->isEmpty()) {
                $result->additionalMeta['is_totally_empty'] = !User::query()->exists();
            }

            return $result;
        } else {
            return $query->get();
        }
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return User|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?User
    {
        return $throwExceptionIfFail
            ? User::findOrFail($id)
            : User::find($id);
    }

    /**
     * @param string $email
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    public function findByEmail(string $email, bool $throwExceptionIfFail = false) : mixed
    {
        $query = User::where('email', $email);

        return $throwExceptionIfFail
            ? $query->firstOrFail()
            : $query->first();
    }

    /**
     * @param array $attributes
     * @return ?User
     */
    public function create(array $attributes) : ?User
    {
        $this->prepareAttributes($attributes);

        $item = User::create($attributes);

        if ($item->exists) {
            $this->updateRelations($item, $attributes);

            return $item->loadMissing(['roles']);
        } else {
            return null;
        }
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function createFromKitaOrOperator(array $attributes)
    {
        if (!empty($attributes['email'])) {
            $user = User::where('email', $attributes['email'])->first();

            if ($user) {
                return $this->update($user->id, $attributes);
            } else {
                return $this->create(array_merge($attributes, [
                    'email_verified_at' => Carbon::now(),
                    'password'          => Str::random(20),
                ]));
            }
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
            $this->updateRelations($item, $attributes);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int   $id
     * @param array $kitas
     * @param bool  $removeKitas
     * @param bool  $sendNotification
     * @return bool|null
     */
    public function updateAttachedKitas(int $id, array $kitas, bool $removeKitas = false, bool $sendNotification = true) : ?bool
    {
        $item = $this->find($id);

        if (!empty($kitas)) {
            if ($removeKitas) {
                $item->kitas()->detach($kitas);
            } else {
                $userKitas = $item->kitas->pluck('id');

                foreach ($kitas as $kitaId) {
                    $kitaId = (int) $kitaId;

                    if (!$userKitas->contains($kitaId)) {
                        $userKitas->add($kitaId);
                    }
                }

                if (!empty($userKitas)) {
                    $item->kitas()->sync($userKitas);

                    if (!empty($sendNotification)) {
                        $item->sendConnectedToKitasNotification(
                            $item->kitas()
                                ->pluck('name')
                                ->toArray()
                        );
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param int   $id
     * @param array $operators
     * @param bool  $removeOperators
     * @return bool|null
     */
    public function updateAttachedOperators(int $id, array $operators, bool $removeOperators = false) : ?bool
    {
        $item = $this->find($id);

        if (!empty($operators)) {
            if ($removeOperators) {
                $item->operators()->detach($operators);
            } else {
                $userOperators = $item->operators->pluck('id');

                foreach ($operators as $operatorId) {
                    $operatorId = (int) $operatorId;

                    if (!$userOperators->contains($operatorId)) {
                        $userOperators->add($operatorId);
                    }
                }

                if (!empty($userOperators)) {
                    $item->operators()->sync($userOperators);
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
        // Prepare 'password' field
        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }
    }

    /**
     * @param User  $item
     * @param array $attributes
     * @return void
     */
    protected function updateRelations(User $item, array $attributes) : void
    {
        /*
         * Update 'roles' relation
         */
        if (!empty($attributes['role'])) {
            $roleIds = (array) $attributes['role'];

//            // Remove 'super-admin' Role ID from selected roles
//            $superAdminRole = Role::whereIn('name', [config('permission.project_roles.super_admin')])->first();
//
//            if ($superAdminRole && ($key = array_search($superAdminRole->id, $roleIds)) !== false) {
//                unset($roleIds[$key]);
//            }

            if (!empty($roleIds)) {
                $item->syncRoles($roleIds);
            }
        }

        /*
         * Update 'kitas' relation
         */
        if (!empty($attributes['kitas'])) {
            $userKitas = $item->kitas->pluck('id');

            foreach ($attributes['kitas'] as $kitaId) {
                $kitaId = (int) $kitaId;

                if (!$userKitas->contains($kitaId)) {
                    $userKitas->add($kitaId);
                }
            }

            if (!empty($userKitas)) {
                $item->kitas()->sync($userKitas);

                $item->sendConnectedToKitasNotification(
                    $item->kitas()
                        ->whereIn('id', $userKitas)
                        ->pluck('name')
                        ->toArray()
                );
            }
        }

        /*
         * Update 'operators' relation
         */
        if (!empty($attributes['operators'])) {
            $userOperators = $item->operators->pluck('id');

            foreach ($attributes['operators'] as $operatorId) {
                $operatorId = (int) $operatorId;

                if (!$userOperators->contains($operatorId)) {
                    $userOperators->add($operatorId);
                }
            }

            if (!empty($userOperators)) {
                $item->operators()->sync($userOperators);
            }
        }
    }
}
