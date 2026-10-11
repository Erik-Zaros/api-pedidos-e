<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function registrar(array $data): array
    {
        $user = $this->users->create($data);

        return [$user, $this->guard()->login($user)];
    }

    public function login(array $credentials): ?string
    {
        return $this->guard()->attempt($credentials) ?: null;
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }

    public function refresh(): string
    {
        return $this->guard()->refresh();
    }

    public function usuarioAutenticado(): ?User
    {
        return $this->guard()->user();
    }

    public function tokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ];
    }

    private function guard()
    {
        return auth('api');
    }
}
