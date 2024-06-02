<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int                             $id
 * @property string                          $email
 * @property string                          $token
 * @property bool                            $is_admin
 * @property string|null                     $accept_at
 * @property string|null                     $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
            'isAdmin' => $this->is_admin,
            'expiresAt' => $this->expires_at,
            'createdAt' => $this->created_at
        ];
    }
}
