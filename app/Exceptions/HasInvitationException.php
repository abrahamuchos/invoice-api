<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class HasInvitationException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'error' => true,
            'message' => 'Error email has invitation active',
            'code' => 11400,
            'details' => 'You have an active invitation, you must complete it or it expires.'
        ]);
    }
}
