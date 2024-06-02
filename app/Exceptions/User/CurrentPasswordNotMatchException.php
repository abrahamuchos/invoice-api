<?php

namespace App\Exceptions\User;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class CurrentPasswordNotMatchException extends Exception
{
    protected $code = 10100;
    public int $status = 401;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Password not match",
                "details" => "Your password does not match",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Please try again, check your password, it should match the one we have saved."
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
