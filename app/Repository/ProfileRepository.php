<?php

namespace App\Repository;

use App\Models\Profile;
use Carbon\Carbon;

class ProfileRepository
{

    private Profile $profile;

    /**
     * ProfileRepository constructor.
     * @param Profile $profile
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @param int $profileId
     * @param array $data
     */
    public function updateProfile(int $profileId, array $data): void
    {
        $profile = $this->profile->findOrFail($profileId);
        $this->profile->newQuery()->whereId($profile->id)->update(
            [
                "first_name" => $data["first_name"] ?? $profile->first_name,
                "last_name" => $data["last_name"] ?? $profile->last_name,
                "dob" => new Carbon($data["dob"]) ?? $profile->dob,
                "phone_number" => $data["phone_number"] ?? $profile->phone_number,
                "address" => $data["address"] ?? $profile->address,
                "gender" => $data["gender"] ?? $profile->gender,
            ]
        );
    }

    /**
     * @param int $profileId
     * @param array $data
     */
    public function updateProfileDescription(int $profileId, array $data): void
    {
        $this->profile->newQuery()->whereId($profileId)->update(
            [
                "description" => $data["description"],
            ]
        );
    }

}
