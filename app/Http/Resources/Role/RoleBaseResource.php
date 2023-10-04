<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources\Role;

use App\Http\Resources\BaseJsonResource;

/**
 * Role Base Resource
 *
 * @package \App\Http\Resources\Role
 */
class RoleBaseResource extends BaseJsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
