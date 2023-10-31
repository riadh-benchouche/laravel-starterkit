<?php

namespace App\Http\Resources;

use App\Http\Resources\Auth\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserProfileResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->user_status,
            'roles' => $this->roles->pluck('name'),
            'profile' => new ProfileResource($this->profile),
        ];
    }
}
