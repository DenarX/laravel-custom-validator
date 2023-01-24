# README #
Default Illuminate validator has some bugs and features. This package make it more strict and additionally allows you easily to extend it, without creating custom Factory and ServiceProvider, what make possible to change behavior of build-in rules 

### Installation ###

```
composer require show4me/laravel-custom-validator
```

for Lumen: add to your bootstrap/app.php

```php
$app->register(Denarx\LaravelCustomValidator\ValidationServiceProvider::class);
```
### What fixed by default

* integer - accept bool true as 1, now throw an error
* not required integer - accept empty string and null, now throw an error
* not required string - don't check min length, just skipped, now throw an error

### Overwrite/extend in your application ###

You can simply extend for adding/changing rules by creating file /app/Validator/Validator.php

```php
<?php

namespace App\Validator;

use Denarx\LaravelCustomValidator\Validator as CustomValidator;

class Validator extends CustomValidator
{
    public function validateNewRule($attribute, $value): bool
    {
        return $value==='test';
    }

    public function validateInteger($attribute, $value): bool
    {
        return !is_string($value) && is_numeric($value) && filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}

```

In this example the default rule "integer" overwrited for more strict behavior, numeric strings (like "1") will be rejected.
And we added new rule with name "new_rule" that accepts only string 'test'. 
On reject, we'll get an error message: "Error while validation"
For customise it we need to create file /resources/lang/en/validation.php

```php
<?php

$defaultTranslation = include(base_path('/vendor/laravel/lumen-framework/resources/lang/en/validation.php'));
$customTranslation = [
    'new_rule' => 'The :attribute must be equal "test".',
];

return array_merge($defaultTranslation, $customTranslation);

```
And now we have message : "The title must be equal \"test\"."

You can use just an extent without changing default rules behavior by creating the same file /app/Validator/Validator.php but with extending DefaultValidator instead CustomValidator. In that case validator has default Illuminate behavior, but you can easily extend it still 


```php
<?php

namespace App\Validator;

use Illuminate\Validation\Validator as DefaultValidator;

class Validator extends DefaultValidator
{
    public function validateNewRule($attribute, $value): bool
    {
        return $value==='test';
    }
}

```