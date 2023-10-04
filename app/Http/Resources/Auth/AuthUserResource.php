<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\Role\RoleBaseResource;
use App\Http\Resources\User\UserBaseResource;

/**
 * Auth User Resource
 *
 * @package \App\Http\Resources\Auth
 */
class AuthUserResource extends BaseJsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        $resource = [
            $this->mergeWhen(true, UserBaseResource::make($this->resource)),
        ];

        if ($this->relationLoaded('roles')) {
            $resource['role'] = RoleBaseResource::make($this->whenLoaded('roles')->first());
        }

        return $resource;
    }
}
