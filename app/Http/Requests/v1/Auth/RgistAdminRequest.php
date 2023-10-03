<?php

namespace App\Http\Requests\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RgistAdminRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required',
            'position' => 'required|string'
        ];
    }
}
