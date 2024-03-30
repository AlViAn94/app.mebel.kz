<?php

namespace App\Http\Requests\v1\Sklad;

use App\Validators\RequestValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SkladRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'position' => 'nullable',
            'count'    => 'nullable|integer|min:0',
            'unit'     => 'nullable',
            'price'    => 'nullable|integer|min:0',
        ];
    }

    protected function failedValidation($validator): JsonResponse
    {
        RequestValidator::handleValidationErrors($validator);
    }
}
