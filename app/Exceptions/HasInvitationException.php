<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class HasInvitationException extends Exception
{
    protected $code = 13100;
    public int $status = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Email has an active invitation.",
                "details" => "Email has an active invitation.",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "User already has an active invitation, check your email or wait for the invitation to expire to request a new one."
            ],
            "documentation_url" => env('APP_FRONTEND_URL') . '/docs/errors'
        ], $this->status);
    }
}
