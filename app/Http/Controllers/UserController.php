<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\Invitation\InvitationExpiredException;
use App\Exceptions\StoreException;
use App\Exceptions\UpdateException;
use App\Exceptions\User\CurrentPasswordNotMatchException;
use App\Exceptions\User\DeleteAdminException;
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
     * @return UserResource
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        try {
            $invitation = InvitationController::isValid($request->email, $request->token);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $invitation->email,
                'password' => Hash::make($request->input('password')),
                'is_admin' => $invitation->is_admin,
                'last_access' => now(),
            ]);

        } catch (InvitationExpiredException $e) {
            return $e->render();

        } catch (\Exception $e) {
            $storeException = new StoreException();
            return $storeException->render();

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
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            if ($request->input('password')) {
                $this->_checkPassword($request->input('currentPassword'));
            }
            $wasUpdated = $user->update($request->all());

        } catch (CurrentPasswordNotMatchException $e) {
            return $e->render();
        }


        if ($wasUpdated) {
            return response()->json([], 201);

        } else {
            $updateException = new UpdateException();
            return $updateException->render();

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
            $deleteAdminException = new DeleteAdminException();
            return $deleteAdminException->render();
        }

        $wasDeleted = $user->delete();

        if ($wasDeleted) {
            return response()->json();
        } else {
            $deleteException = new DeleteException();
            return $deleteException->render();
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
            throw new CurrentPasswordNotMatchException();
        }

    }

}
