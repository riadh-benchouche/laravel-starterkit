<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Status;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class AuthController extends ApiController
{
    /**
     * @var UserRepository
     */
    private UserRepository $authRepository;

    public function __construct(UserRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @throws Exception
     */

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Login user",
     *      description="Logs in a user with email and password",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref=UserResource::class),
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="The provided credentials are incorrect.")
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function login(LoginRequest $request): JsonResponse|array
    {
        $user = User::whereUserStatus(Status::ENABLED)->where('email', $request->email)->first();

        // Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {

            activity()->performedOn(User::getModel())
                ->useLog('Authentication failure')
                ->causedBy($user)
                ->event('login')
                ->withProperties(['message' => 'Authentication failure with: ' . $request->email, 'ip_address' => $request->getClientIp(), 'user_agent' => $request->userAgent()])
                ->log('Authentication failure');

            return $this->errorResponse(
                __("The provided credentials are incorrect."),
                401
            );
        }

        return [
            "data" => new UserResource($user),
            "token" => $this->authRepository->createToken($user)
        ];
    }

    /**
     * @param RegisterRequest $request
     * @return array|JsonResponse
     * @throws Exception
     */

    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Register user",
     *      description="Registers a new user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref=UserResource::class),
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function register(RegisterRequest $request): JsonResponse|array
    {
        $user = $this->authRepository->createAccountProfile($request->all());

        return [
            "data" => new UserResource($user),
            "token" => $this->authRepository->createToken($user),
        ];
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout user",
     *      description="Logs out the currently authenticated user",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="logout success")
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function logout(Request $request)
    {
        $this->authRepository->revokeTokenById($request->user(), $request->user()->currentAccessToken()->id);
        return $this->showMessage(__('logout success'));
    }
}
