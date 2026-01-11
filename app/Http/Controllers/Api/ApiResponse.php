<?php

namespace App\Http\Controllers\Api;

trait ApiResponse
{
    protected function success($message = 'OK', $data = null, $code = 200)
    {
        $payload = ['success' => true, 'message' => $message];
        if (!is_null($data)) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $code);
    }

    protected function error($message = 'Error', $errors = null, $code = 500)
    {
        $payload = ['success' => false, 'message' => $message];
        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    protected function validationError($errors, $message = 'Validation failed')
    {
        return $this->error($message, $errors, 422);
    }
}
