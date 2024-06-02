<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class DeleteException extends Exception
{
    protected $code = 10100;
    public int $status = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Store was not possible",
                "details" => "Store was not possible, contact with admin.",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Please contact with admin or developer"
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
