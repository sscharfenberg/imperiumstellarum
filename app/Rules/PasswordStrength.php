<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ZxcvbnPhp\Zxcvbn;

class PasswordStrength implements Rule
{

    /**
     * minimum score
     * @var string
     * 0 means the password is extremely guessable (within 10^3 guesses), dictionary words like 'password' or 'mother' score a 0
     * 1 is still very guessable (guesses < 10^6), an extra character on a dictionary word can score a 1
     * 2 is somewhat guessable (guesses < 10^8), provides some protection from unthrottled online attacks
     * 3 is safely unguessable (guesses < 10^10), offers moderate protection from offline slow-hash scenario
     * 4 is very unguessable (guesses >= 10^10) and provides strong protection from offline slow-hash scenario
     */
    public $minScore = 3;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $zxcvbnInstance = new Zxcvbn();
        $check = $zxcvbnInstance->passwordStrength($value);
        $score = $check['score'];
        return $score >= $this->minScore;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.password.strength');
    }
}
