<?php

namespace App\Http\Resources\Auth;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/** @mixin Profile */

/**
 * @OA\Schema(
 *     title="ProfileResource",
 *     description="Profile resource",
 *     @OA\Xml(
 *         name="ProfileResource"
 *     )
 * )
 */
class ProfileResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */

    /**
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="first_name", type="string"),
     *     @OA\Property(property="last_name", type="string"),
     *     @OA\Property(property="full_name", type="string"),
     *     @OA\Property(property="phone_number", type="string"),
     *     @OA\Property(property="address", type="string"),
     *     @OA\Property(property="dob", type="string"),
     *     @OA\Property(property="gender", type="string"),
     *     @OA\Property(property="avatar", type="string")
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'avatar' => $this->avatar
        ];
    }
}
