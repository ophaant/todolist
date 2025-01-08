<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(LoginRequest $request)
    {
        $credentials = $request->safe()->only('email', 'password');

        try {
            if (!JWTAuth::attempt($credentials)) {
                return $this->error(config('rc.record_not_found'));
            }

            // Get the authenticated user.
            $user = auth()->user();
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            $response = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];

            return $this->success($response,200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error(config('rc.internal_server_error'));
        }
    }

    // register new user
    public function register(RegisterRequest $request)
    {
        $data = $request->safe()->only('name', 'email', 'password');

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return $this->success($user->toArray(), 201,config('rc.user_create_successfully'));
    }
}
