<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RequestValidator
{
    public static function handleValidationErrors(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
