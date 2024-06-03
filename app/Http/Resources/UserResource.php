<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property bool                            $is_admin
 * @property string|null                     $last_access
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'isAdmin' => $this->is_admin,
            'lastAccess' => $this->last_access,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
