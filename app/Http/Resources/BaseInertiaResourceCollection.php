<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Base Inertia Resource Collection
 *
 * @package \App\Http\Resources
 */
class BaseInertiaResourceCollection extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        $isPaginated = $this->resource instanceof LengthAwarePaginator;

        $data = [
            'items'     => $this->collection,
            'paginated' => $isPaginated,
        ];

        if ($isPaginated) {
            array_overwrite($data, [
                'currentPage' => (int) $this->resource->currentPage(),
                'lastPage'    => (int) $this->resource->lastPage(),
                'perPage'     => (int) $this->resource->perPage(),
                'total'       => (int) $this->resource->total(),
                'sort'        => $request->input('sort', 'asc'),
                'orderBy'     => $request->input('order_by'),
            ]);
        }

        return $data;
    }
}
