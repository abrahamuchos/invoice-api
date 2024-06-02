<?php

namespace App\Exceptions\User;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class DeleteAdminException extends Exception
{
    protected $code = 10100;
    public int $status = 423;

    public function render(): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Error Admin user can not delete",
                "details" => "Error Admin user can not delete",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => "Please you do not delete admin user"
            ],
            "documentation_url" => env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }
}
