<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'token' => ['required', 'numeric'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
