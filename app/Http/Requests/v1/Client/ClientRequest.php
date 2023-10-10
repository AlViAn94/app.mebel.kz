<?php

namespace App\Http\Requests\v1\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'iin' => 'required|numeric|digits:12',
            'name' => 'required|string',
            'surname' => 'string',
            'lastname' => 'string',
            'phone' => 'required|integer',
            'email' => 'required|email',
        ];
    }
}
