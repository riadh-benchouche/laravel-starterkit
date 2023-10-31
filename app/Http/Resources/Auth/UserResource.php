<?php

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth\ProfileResource;
use OpenApi\Annotations as OA;

/** @mixin User */

/**
 * @OA\Schema(
 *     title="UserResource",
 *     description="User resource",
 *     @OA\Xml(
 *         name="UserResource"
 *     )
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */

    /**
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="email", type="email"),
     *     @OA\Property(property="has_password", type="string"),
     *     @OA\Property(property="user_status", type="integer"),
     *     @OA\Property(property="notification_status", type="boolean"),
     *     @OA\Property(property="roles", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="permission", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="profile", type="object", ref=ProfileResource::class),
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'has_password' => $this->password,
            'user_status' => $this->user_status,
            'notification_status' => $this->notification_status,
            'roles' => $this->roles->pluck('name'),
            'permission' => $this->roles->first()->permissions->pluck('name'),
            'profile' => new ProfileResource($this->profile),
        ];
    }
}
