<?php

namespace App\Actions\Fortify;
use Laravel\Fortify\Rules\Password;
use App\Rules\PasswordStrength;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules(): array
    {
        //return ['required', 'string', new Password, 'confirmed'];
        return ['required', new PasswordStrength, 'string', 'confirmed'];
    }
}
