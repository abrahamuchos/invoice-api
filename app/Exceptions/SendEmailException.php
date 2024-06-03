<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class SendEmailException extends Exception
{
    protected $code = 11500;
    public int $status = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Send mail was not possible",
                "details" => "Send mail was not possible, contact with admin.",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Send mail was not possible check connection and try again"
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
