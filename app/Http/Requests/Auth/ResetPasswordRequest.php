<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;


class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
