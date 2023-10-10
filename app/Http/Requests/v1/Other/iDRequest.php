<?php

namespace App\Http\Requests\v1\Other;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class iDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->position() == 'manager'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric'
        ];
    }
}
