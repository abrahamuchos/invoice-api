<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => true,
                'message' => 'Email or password are invalid. Try Again.',
                'code' => 10000,
                'details' => null
            ], 401);
        }

        $user = Auth::user();

        if ($user->is_admin) {
            $token = $this->_createAdminToken($user);
        } else {
            $token = $this->_createBasicToken($user);
        }
        $this->_updateLastAccess($user);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token['token'],
            'expiresAt' => $token['expires_at']
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json();
    }

    /**
     * Create token to admin user
     *
     * @param User $user
     *
     * @return array<string>
     */
    private function _createAdminToken(User $user): array
    {
        $token = $user->createToken('admin-token', ['create', 'update', 'delete'], now()->addHours(2));

        return [
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ];
    }

    /**
     * Create a basic user token
     *
     * @param User $user
     *
     * @return array<string>
     */
    private function _createBasicToken(User $user): array
    {
        $token = $user->createToken('basic-token', ['create', 'update'], now()->addHours(12));

        return [
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ];
    }

    /**
     * Update user last access
     *
     * @param User $user
     *
     * @return void
     */
    private function _updateLastAccess(User $user): void
    {
        $user->update([
            'last_access' => now()
        ]);
    }
}
