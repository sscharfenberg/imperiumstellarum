<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @throws \Illuminate\Validation\ValidationException
     * @return User
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            //'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:120', 'unique:users'],
            'password' => $this->passwordRules(),
            'confirmed' => ['accepted']
        ])->validate();

        return User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'locale' => app()->getLocale()
        ]);
    }
}
