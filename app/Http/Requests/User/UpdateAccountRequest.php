<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        return [
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'old_password' => [
                Rule::requiredIf($request->user()->hasPassword())
                , function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail(__('Errors credentials'));
                    }
                },
            ],
            'password' => 'required|confirmed',
        ];
    }
}
