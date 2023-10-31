<?php

namespace App\Http\Requests\User;

use App\Enums\UserGenders;
use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'phone_number' => ['nullable', "unique:profiles,phone_number,{$this->user()->profile->id}"],
            'gender' => ['nullable', new Enum(UserGenders::class)],
            'dob' => ['nullable', 'date'],
            'status' => ['nullable', 'string'],
            'role' => ['required', new Enum(UserRoles::class)],
        ];
    }
}
