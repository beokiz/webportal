<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;

/**
 * User Base Resource
 *
 * @package \App\Http\Resources\User
 */
class UserBaseResource extends BaseJsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'          => $this->id,
            'api_user_id' => $this->api_user_id,
            'name'        => $this->name,
            'username'    => $this->username,
            'website'     => $this->website,
            'email'       => $this->email,
            'status'      => $this->status,
        ];
    }
}
