<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        [$user, $token] = $this->service->registrar($request->safe()->only(['name', 'email', 'password']));

        return response()->json([
            'user' => new UserResource($user),
            ...$this->service->tokenResponse($token),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->service->login($request->validated());

        if (! $token) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        return response()->json($this->service->tokenResponse($token));
    }

    public function me(): UserResource
    {
        return new UserResource($this->service->usuarioAutenticado());
    }

    public function logout(): JsonResponse
    {
        $this->service->logout();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json($this->service->tokenResponse($this->service->refresh()));
    }
}
