<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        if ($user) {
            return response()->json($user, Response::HTTP_CREATED);
        } else {
            return response()->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::whereEmail($data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($data['device_name'], ['*'], now()->addDay())->plainTextToken;

        return response()->json(
            [
                'message' => 'Welcome! ' . $user->name,
                'token' => $token
            ], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Goodbye! ' . $user->name,
        ], Response::HTTP_OK);
    }
}
