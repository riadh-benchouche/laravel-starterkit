<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\UpdateAccountRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\Profile;
use App\Models\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends ApiController
{
    private UserRepository $userRepository;
    private ProfileRepository $profileRepository;

    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }
    public function index(Request $request): AnonymousResourceCollection
    {
        return UserProfileResource::collection(User::paginate($request->get('limit', 10)));
    }
    public function store(UserRequest $request): UserProfileResource
    {
        $user = $this->userRepository->createAccountProfile($request->validated());
        return new UserProfileResource($user);
    }

    public function show(User $user): UserProfileResource
    {
        return new UserProfileResource($user);
    }

    public function updateProfile(ProfileRequest $request, User $user): UserProfileResource
    {
        $this->profileRepository->updateProfile($user->profile->id, $request->validated());
        return new UserProfileResource($user);
    }

    public function updateAccount(UpdateAccountRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());
        return $this->showMessage(__("Your password has been update successful"));
    }

    public function createAvatar(ImageRequest $request, User $user): JsonResponse|UserProfileResource
    {
        if ($user->profile === null) {
            return $this->errorResponse(__("The profile is not created yet !, please created first"), 404);
        }
        $user->profile->addMediaFromBase64($request->image)->toMediaCollection('avatar');

        return new UserProfileResource($user->refresh());
    }

    public function deleteAvatar(Request $request, User $user): JsonResponse|UserProfileResource
    {
        if ($user->profile === null) {
            return $this->errorResponse(__("The profile is not created yet !, please created first"), 404);
        }
        $profile = Profile::whereId($user->profile->id)->first();

        $media = $profile->getFirstMedia("avatar");
        $media?->forceDelete();
        return new UserProfileResource($user->profile);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return $this->showMessage(__("User has been deleted successful"));
    }
}
