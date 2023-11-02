<?php

namespace App\Http\Requests\User;

use App\Enums\UserGenders;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use function __;

/** @mixin Profile */
class ProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => "required|string|min:3",
            'last_name' => "required|string|min:3",
            'phone_number' => ['nullable', "unique:profiles,phone_number,{$this->user->profile->id}"],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', new Enum(UserGenders::class)],
            'dob' => ['nullable', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'gender.in' => __('The value must be 0 for Man and 1 for Woman'),
        ];
    }

    public function authorize()
    {
        return true;
    }
}
