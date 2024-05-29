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

        if($user->is_admin){
            $token = $this->_createAdminToken($user);
        }else{
            $token = $this->_createBasicToken($user);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
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
     * @param User $user
     *
     * @return string
     */
    private function _createAdminToken(User $user): string
    {
        return $user->createToken('admin-token', ['create', 'update', 'delete'])->plainTextToken;
    }

    /**
     * Create a basic user token
     * @param User $user
     *
     * @return string
     */
    private function _createBasicToken(User $user): string
    {
        return $user->createToken('basic-token', ['create', 'update'])->plainTextToken;
    }
}
