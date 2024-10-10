<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Resources\BaseInertiaResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

/**
 * Base Controller
 *
 * @package \App\Http\Controllers
 */
abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param LengthAwarePaginator|Collection $collection
     * @param array                           $additionalData
     * @return array
     */
    protected function prepareItemsCollection($collection, array $additionalData = [])
    {
        return array_merge(
            BaseInertiaResourceCollection::make($collection)->resolve(),
            $additionalData
        );
    }
}
