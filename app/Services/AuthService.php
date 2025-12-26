<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(
        string $name,
        string $email,
        string $password
    ): array
    {
        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        return [
            'user'  => $user,
            'token' => $user->createToken('api-token')->plainTextToken
        ];
    }


    public function login(
        string $email,
        string $password
    ): array
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        return [
            'user'  => $user,
            'token' => $user->createToken('api-token')->plainTextToken
        ];
    }


    public function logout(User $user): void
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        $token?->delete();
    }
}
