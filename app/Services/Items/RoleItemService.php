<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Role;

/**
 * Role Item Service
 *
 * @package \App\Services\Items
 */
class RoleItemService extends BaseItemService
{
    /**
     * RoleItemService constructor.
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
        $params = $this->prepareCollectionParams($args);

        /*
         * Filter & order query
         */
        $query = Role::query()
            ->filter($args)
            ->customOrderBy($params->order_by ?? 'id', $params->sort === 'desc');

        return $params->paginated
            ? $query->paginateFilter($params->per_page)
            : $query->get();
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return Role|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Role
    {
        return $throwExceptionIfFail
            ? Role::findOrFail($id)
            : Role::find($id);
    }

    /**
     * @param string $name
     * @param bool   $throwExceptionIfFail
     * @return mixed
     */
    public function findByName(string $name, bool $throwExceptionIfFail = false) : mixed
    {
        $query = Role::where('name', $name);

        return $throwExceptionIfFail
            ? $query->firstOrFail()
            : $query->first();
    }
}
