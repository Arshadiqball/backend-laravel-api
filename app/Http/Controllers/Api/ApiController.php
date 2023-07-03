<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $authSrvc,
        $user = [];

    public function __construct(AuthService $authService)
    {
        $this->authSrvc = $authService;
        $this->middleware("throttle:10,1")->only("login");
    }
    /**
     *--------------------------------------------------------------------------
     * For login in SPA
     *--------------------------------------------------------------------------
     *
     * @param AuthRequest
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $result = $this->authSrvc->performAuth($request);
        return $result["bool"]
            ? self::success($result["result"], "Authorized: Welcome!")
            : self::error($result["message"]);
    }

    /**
     *--------------------------------------------------------------------------
     * User register
     *--------------------------------------------------------------------------
     *
     * @param AuthRequest
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $result = $this->authSrvc->registerUser($request);
        return $result["bool"]
            ? self::success($result["result"], $result["message"])
            : self::error($result["message"]);
    }

    /**
     *--------------------------------------------------------------------------
     * Check User is Logged In or Not
     *--------------------------------------------------------------------------
     *
     * Returns current logged in user details
     * @return JsonResponse
     */
    public function authUser(): JsonResponse
    {
        return auth()
            ->guard("api")
            ->user()
            ? self::success(
                auth()
                    ->guard("api")
                    ->user()
            )
            : self::error("No User login");
    }

    /**
     *--------------------------------------------------------------------------
     * User Logout and Delete Token
     *--------------------------------------------------------------------------
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = auth()
            ->guard("api")
            ->user();

        if ($user) {
            $user->tokens()->delete();
            return self::success("Logout successful");
        }

        return self::error("No User login");
    }

    /**
     *--------------------------------------------------------------------------
     * User Settings and Preferences update
     *--------------------------------------------------------------------------
     *
     * @return JsonResponse
     */
    public function settings(Request $request): JsonResponse
    {
        $user = auth()
            ->guard("api")
            ->user();
        $fillableFields = [
            "theme",
            "language",
            "sources",
            "categories",
            "authors",
        ];

        foreach ($fillableFields as $field) {
            $user->$field = !empty($request[$field]) ? ($field === 'sources' || $field === 'categories' || $field === 'authors' ? implode(',', array_column($request[$field], 'value')) : $request[$field]) : null;
        }
        
        $user->save();

        return self::success($user, "Preferences updated successfully");
    }

    /**
     *--------------------------------------------------------------------------
     * Get User Settings and Preferences
     *--------------------------------------------------------------------------
     *
     * @return JsonResponse
     */
    public function getSettings(): JsonResponse
    {
        $user = auth()
            ->guard("api")
            ->user();
        $data = [
            "theme" => $user->theme ?? null,
            "language" => $user->language ?? null,
            "sources" => $user->sources ?? null,
            "categories" => $user->categories ?? null,
            "authors" => $user->authors ?? null,
        ];
        return self::success($data);
    }
}
