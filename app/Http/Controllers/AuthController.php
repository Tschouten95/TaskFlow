<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    )
    {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $response = $this->authService->register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        return response()->json([
            'user' => new UserResource($response['user']),
            'token' => $response['token'],
            'type' => 'Bearer',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $response = $this->authService->login(
            $data['email'],
            $data['password']
        );

        return response()->json([
            'user' => new UserResource($response['user']),
            'token' => $response['token'],
            'type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(
            new UserResource($request->user())
        );
    }
}
