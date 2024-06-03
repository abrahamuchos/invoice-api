<?php

namespace App\Http\Controllers;

use App\Exceptions\HasInvitationException;
use App\Exceptions\Invitation\InvitationExpiredException;
use App\Exceptions\SendEmailException;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Http\Resources\InvitationCollection;
use App\Http\Resources\InvitationResource;
use App\Mail\InvitationEmail;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): InvitationCollection
    {
        $perPage = $request->query('perPage') ?? 10;
        $invitations = Invitation::paginate($perPage);

        return new InvitationCollection($invitations);
    }

    /**
     * Store a newly created invitation in storage.
     *
     * @param StoreInvitationRequest $request
     *
     * @return InvitationResource|JsonResponse
     */
    public function store(StoreInvitationRequest $request): InvitationResource|JsonResponse
    {

        try {
            $this->_validateEmail($request->email);
            $token = Invitation::createToken();
            $invitation = Invitation::create([
                'email' => $request->email,
                'is_admin' => $request->isAdmin,
                'token' => $token,
                'expires_at' => now()->addHours(12)
            ]);
            $this->_sendMail($invitation->email, $token, $invitation->expires_at);

        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error store an invitation',
                'code' => 11300,
                'details' => $e->getMessage()
            ], 500);

        } catch (HasInvitationException $e) {
            return $e->render();

        } catch (SendEmailException $e) {
            $invitation->delete();
            return $e->render();
        }


        return new InvitationResource($invitation);
    }

    /**
     * Display the specified invitation.
     *
     * @param Invitation $invitation
     *
     * @return InvitationResource
     */
    public function show(Invitation $invitation): InvitationResource
    {
        return new InvitationResource($invitation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }


    /**
     * Check if email has an active invitation
     *
     * @param string $email
     *
     * @return void
     * @throws HasInvitationException
     */
    private function _validateEmail(string $email): void
    {
        $hasInvitation = Invitation::where([
            ['email', '=', $email],
            ['expires_at', '>=', now()]
        ])->count();

        if ($hasInvitation) {
            throw new HasInvitationException();
        }

    }

    /**
     * Send mail to invitation
     *
     * @param string $email
     * @param string $token
     * @param string $expiresAt
     *
     * @return void
     * @throws SendEmailException
     */
    private function _sendMail(string $email, string $token, string $expiresAt): void
    {
        try {
            Mail::to($email)->send(new InvitationEmail($token, $expiresAt));
        } catch (\Exception $e) {
            throw new SendEmailException($e->getMessage());
        }

    }

    /**
     * Validate if invitation is valid, check email and token
     * @param string $email
     * @param string $token
     *
     * @return Invitation
     * @throws InvitationExpiredException
     */
    static function isValid(string $email, string $token): Invitation
    {
        $invitation = Invitation::where([
            ['email', $email],
            ['token', $token]
        ])->first();

        if ($invitation->expires_at <= now()) {
            throw new InvitationExpiredException();
        }

        return $invitation;

    }
}
