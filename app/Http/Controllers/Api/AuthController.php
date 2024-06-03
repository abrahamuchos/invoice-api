<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\EmailIsInvalidException;
use App\Exceptions\UpdateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
           'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT){
            return response()->json([
                'status' => __($status)
            ]);

        }else{
            $emailException = new EmailIsInvalidException();
            return $emailException->render();
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6|max:50',
        ]);

        $status = Password::reset(
            $request->only('email','password', 'password_confirmation', 'token'),
            function ($user) use ($request){
                $user->forceFill([
                    'password'=> Hash::make($request->input('password'))
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if($status == Password::PASSWORD_RESET){
            return response()->json([
                'message' => 'Your password was updated'
            ]);

        }else{
            $updateException = new UpdateException("Password could not be updated", 10201);
            return $updateException->render();
        }
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
