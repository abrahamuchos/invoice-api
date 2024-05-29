<?php

namespace App\Http\Controllers;

use App\Filters\UserFilter;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): UserCollection
    {
        $perPage = $request->query('perPage') ?? 10;
        $filters = new UserFilter();
        $queryItems = $filters->transform($request);
        $users = User::where($queryItems);

        return new UserCollection($users->paginate($perPage)->appends($request->query()));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return UserResource|JsonResponse
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'is_admin' => $request->input('isAdmin'),
                'last_access' => now(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error store a user',
                'code' => 11100,
                'details' => $e->getMessage()
            ]);
        }

        return new UserResource($user);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     *
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            if ($request->input('password')) {
                $this->_checkPassword($request->input('currentPassword'));
            }
            $wasUpdated = $user->update($request->all());

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error current password not match',
                'code' => 11300,
                'details' => $e->getMessage()
            ]);
        }

        if ($wasUpdated) {
            return response()->json();
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error update a customer',
                'code' => 11200,
                'details' => null
            ]);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if ($user->id === 1) {
            return response()->json([
                'error' => true,
                'message' => 'Error Admin user can not delete',
                'code' => 10201,
                'details' => null
            ], 423);
        }

        $wasDeleted = $user->delete();

        if ($wasDeleted) {
            return response()->json();
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error deleted a user',
                'code' => 10200,
                'details' => null
            ]);
        }
    }

    /**
     * Validates that send password is the same a stored password
     *
     * @param string $password
     *
     * @return void
     * @throws \Exception
     */
    private function _checkPassword(string $password): void
    {
        $user = Auth::user();

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('Password not match');
        }

    }

}
