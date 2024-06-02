<?php

namespace App\Exceptions;

use Exception;

class SendEmailException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => 'Error send email',
            'code' => 14100,
            'details' => $this->getMessage()
        ]);
    }
}
