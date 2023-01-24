<?php

namespace Denarx\LaravelCustomValidator;

use Illuminate\Validation\Validator as DefaultValidator;
use Symfony\Component\HttpFoundation\File\File;
use Countable;

class Validator extends DefaultValidator
{
    /**
     * Validate that an attribute is an integer.
     * Customized for reject boolean true and empty strings by is_numeric
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateInteger($attribute, $value): bool
    {
        return is_numeric($value) && filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate that a required attribute exists.
     * Customized for validate not required empty string min length (empty string considered present)
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateRequired($attribute, $value): bool
    {
        if (is_null($value)) {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        } elseif ($value instanceof File) {
            return (string) $value->getPath() !== '';
        }

        return true;
    }

    /**
     * Determine if the field is present, or the rule implies required.
     * Customized for validate not required empty string min length (empty string not skipped)
     *
     * @param  object|string  $rule
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected function presentOrRuleIsImplicit($rule, $attribute, $value): bool
    {
        return $this->validatePresent($attribute, $value) ||
            $this->isImplicit($rule);
    }
}
