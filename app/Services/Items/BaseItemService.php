<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

/**
 * Base Item Service
 *
 * @package \App\Services\Items
 */
abstract class BaseItemService
{
    /**
     * BaseItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param array $args
     * @return object
     */
    protected function prepareCollectionParams(array $args) : object
    {
        return (object) [
            'paginated' => isset($args['paginated']) ? filter_var($args['paginated'], FILTER_VALIDATE_BOOLEAN) : false,
            'page'      => (isset($args['page']) && (int) $args['page'] > 0) ? (int) $args['page'] : 1,
            'per_page'  => (isset($args['per_page']) && (int) $args['per_page'] > 0) ? (int) $args['per_page'] : 25,
            'sort'      => (isset($args['sort']) && in_array($args['sort'], ['asc', 'desc'], true)) ? $args['sort'] : 'asc',
            'order_by'  => (isset($args['order_by']) && !empty($args['order_by'])) ? $args['order_by'] : null,
        ];
    }
}
