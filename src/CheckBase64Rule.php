<?php

namespace Jflahaut\Base64rules;

use Illuminate\Contracts\Validation\Rule;

class CheckBase64Rule implements Rule
{
    private $errors;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!is_string($value)) {
            $this->errors[] = 'The :attribute is not a string';
            return false;
        }

        return preg_match('/^data:[a-z]+\/[a-z]{2,};base64,[0-9a-zA-Z\/+=]+/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must have an encoded base64 value. ' . implode(' ', $this->errors);
    }
}
