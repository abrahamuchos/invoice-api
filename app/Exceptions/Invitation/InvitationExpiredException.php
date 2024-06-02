<?php

namespace App\Exceptions\Invitation;

use Exception;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class InvitationExpiredException extends Exception
{
    protected $code = 11430;
    public int $status = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "statusCode" => 500,
            "error" => [
                "code" => $this->code,
                "message" => "Invitation expired",
                "details" => "Invitation has expired, please request a new one",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Invitation has expired, contact admin user and request a new one"
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
