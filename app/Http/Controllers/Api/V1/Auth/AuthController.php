<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\v1\Auth\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse|UserResource
    {
        $user =  User::create($request->all());
        if($user) {
            Auth::attempt(['email' => $request->email, 'password' => $request->password], true);
            $user->tokens()->delete();
            return new UserResource($user);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }
    public function login(LoginRequest $request): JsonResponse|UserResource
    {
        if(!Auth::attempt($request->only(['email', 'password'], $request->has('remember_token')))) {
            return response()->json([
                'message' => 'Логін або пароль невірний'
            ], 401);
        }
        $user = Auth::user();
        $user->tokens()->delete();
        return new UserResource($user);
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback(): UserResource
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Ищем пользователя по google_id или email
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

        // Если пользователь не найден — создаем нового
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(8)),
            ]);
        } else {
            // Если пользователь найден по email, но не по google_id — обновляем google_id
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                ]);
            }
        }

        auth()->login($user, true);
        $user->tokens()->delete();

        return new UserResource($user);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
