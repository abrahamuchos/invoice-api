<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class EmailIsInvalidException extends Exception
{
    protected $code = 15100;
    public int $status = 422;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Email is invalid",
                "details" => "Email you sent is not registered in our system.",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Email you sent is not registered in our system. Please confirm and try again."
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
