<?php

namespace Denarx\LaravelCustomValidator;

use Illuminate\Validation\Factory as DefaultFactory;
use App\Validator\Validator as AppValidator;
use Illuminate\Validation\Validator as DefaultValidator;

class Factory extends DefaultFactory
{
    /**
     * Resolve a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return DefaultValidator
     */
    protected function resolve(array $data, array $rules, array $messages, array $customAttributes)
    {
        if (is_null($this->resolver)) {
            if (file_exists(base_path('app/Validator/Validator.php')) && class_exists(AppValidator::class)) {
                return new AppValidator($this->translator, $data, $rules, $messages, $customAttributes);
            }
            return new Validator($this->translator, $data, $rules, $messages, $customAttributes);
        }

        return call_user_func($this->resolver, $this->translator, $data, $rules, $messages, $customAttributes);
    }
}
