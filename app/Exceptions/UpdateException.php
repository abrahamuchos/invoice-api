<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class UpdateException extends Exception
{
    protected $code = 10200;
    public int $status = 500;

    /**
     * @param string|null $details
     * @param string|null $suggestion
     * @param string|null $documentationUrl
     *
     * @return JsonResponse
     */
    public function render(string $details = null, string $suggestion = null, string $documentationUrl = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "statusCode" => $this->status,
            "error" => [
                "code" => $this->code,
                "message" => "Update was not possible",
                "details" => $details ?? "Update was not possible, contact with admin.",
                "timestamp" => now(),
                "path" => Route::current()->uri,
                "suggestion" => $suggestion ?? "Please contact with admin or developer"
            ],
            "documentation_url" => $documentationUrl ?? env('APP_FRONTEND_URL').'/docs/errors'
        ], $this->status);
    }

}
