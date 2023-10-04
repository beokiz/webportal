<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Exceptions\Custom\SuperAdminDeletingException;
use App\Exceptions\Custom\SuperAdminUpdatingException;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

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
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc')
            ->with(['roles']);

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
     * @param int   $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes) : bool
    {
        $item = $this->find($id, true);

        // TODO: Refactor this code for better performance.
//        if ($item->hasRole(config('permission.project_roles.super_admin')) && (optional(auth('sanctum')->user())->id !== $item->id)) {
//            throw new SuperAdminUpdatingException();
//        }

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
        $user = $this->find($id, true);

        // TODO: Refactor this code for better performance.
//        if ($user->hasRole(config('permission.project_roles.super_admin'))) {
//            throw new SuperAdminDeletingException();
//        }

        return $user->delete();
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
    protected function prepareAttributes(array &$attributes)
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
    protected function updateRelations(User $item, array $attributes)
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
    }
}
