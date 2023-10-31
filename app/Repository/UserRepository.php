<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createAccountProfile(array $data): mixed
    {
        return DB::transaction(static function () use ($data) {
            return User::withoutEvents(static function () use ($data) {
                $user = User::create([
                    'email' => $data['email'] ?? null,
                    'password' => $data['password'],
                ]);
                $user->profile()->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'] ?? null,
                ]);
                $user->assignRole($data['role']);
                return $user;
            });

        });

    }

    /**
     * @param User $user
     * @return string
     */
    public function createToken(User $user): string
    {
        return $user->createToken($user->email)->plainTextToken;
    }


    public function revokeTokenById(User $user, int $tokenId): void
    {
        $user->tokens()->where('id', $tokenId)->delete();
    }
}
