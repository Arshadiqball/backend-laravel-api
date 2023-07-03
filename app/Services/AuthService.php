<?php

namespace App\Services;

use App\Http\Resources\UserCollection;
use App\Models\SocialAccount;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Mockery\Matcher\Any;

class AuthService
{
    private $userRepo;

    public function __construct(
        UserRepository $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    public function performAuth($request): array
    {
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return [
                'bool' => false,
                'message' => 'Authentication failed. Please check your email or password'
            ];
        }

        return $this->userData();
    }

    function registerUser($request): array
    {

        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user['password'] = Hash::make($request['password']);

        DB::beginTransaction();
        // Creating user in database(s)
        $result = $this->userRepo->create($user);
        if (!$result['bool']) {
            DB::rollBack();
            return self::error($result['message']);
        }

        if (!Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ])) {
            return self::error('Account is created, login mannually');
        }
        DB::commit();
        // Checking if user is from social platforms, if yes then register him at social_accounts table.

        return $this->userData();
    }

    private function userData()
    {
        $user = Auth::user();
        $token = $user->createToken('React App')->accessToken;
        $userArray = collect($user)->all();
        return [
            'bool' => true,
            'message' => 'Authorized: Welcome!',
            'result' => [...$userArray, 'token' => $token]
        ];
    }
}
