<?php

namespace Jflahaut\Base64rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CheckBase64FormatsRule implements Rule
{
    private $allowedFormats;
    private $errors;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $formats)
    {
        $this->allowedFormats = $formats;
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
        $validator = Validator::make(
            ['value' => $value],
            ['value' => new CheckBase64Rule()]
        );

        if(!$validator->passes()) {
            $errors = $validator->errors();
            $this->errors = array_merge($this->errors, $errors->all());

            return false;
        }

        $data = explode(';', $value)[0];

        foreach ($this->allowedFormats as $key=>$format) {
            if(preg_match('/\/\*/', $format)) {
                if(strpos(explode('/', $data)[0], explode('/', $format)[0]) !== false) {
                    return true;
                }
            } else {
                if (in_array(explode('/', $data)[1], $this->allowedFormats)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must have an encoded base64 value and be : ' . implode(' | ', $this->allowedFormats) . '. ' . implode(' ', $this->errors);
    }
}
