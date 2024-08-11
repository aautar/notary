# notary

PHP library to compose and check against validation rules

## Overview

Notary is driven by 2 design goals:

 1. A validation rule is just a function
 2. Using a validation rule means just referencing said function 

No magic, no regex, no crazy syntax.

Just write a function, maybe encapsulate it in a class if you like.

## Examples

### Defining rules with closures

```php
$validator = new Validator();

$validator->addRule(
    "RULE_SERVICE_CAN_CREATE_USER",     
    "Can't create user, user already exists",
    function($_email) use ($userService) { 
        return $userService->canCreateNewUser($_email);
    }
);

// Add field, field value, and all rules to validate against
$validator->addField(
    'email', 
    $_POST['email'], 
    ["RULE_SERVICE_CAN_CREATE_USER"]
);

// Validate and get back any errors
$errors = $validator->validate();
if(empty($errors)) {
    // We're good!
}
```

### Defining rules with `Rule` classes

The method `Validator::addRule()` constructs an instance of the `Rule` class. In some cases it may be preferable create and manage instances of the `Rule` class yourself and/or inherit from `Rule`, in such cases use `Validator::addNewRule()` to attach the rule to the validator.

```php
$validator = new Validator();

$userDoesNotExistRule = new Rule(
    "RULE_SERVICE_CAN_CREATE_USER", 
    "Can't create user, user already exists",
    function($_email) use ($userService) {  
        return $userService->canCreateNewUser($_email);
    }
);

$validator->addNewRule($userDoesNotExistRule);

// Add field, field value, and all rules to validate against
$validator->addField(
    'email', 
    $_POST['email'], 
    [$userDoesNotExistRule->getId()]
);

// Validate and get back any errors
$errors = $validator->validate();
if(empty($errors)) {
    // We're good!
}
```

### Pre-defined / common rules
A few rules are bundled with the library given how common they are for applications.


- `Validator::RULE_REQUIRED`: Rule to check that field value should be non-empty (see [what PHP considers "empty"](https://www.php.net/manual/en/function.empty.php))
- `Validator::VALID_EMAIL`: Rule to check that field value is a string with the syntax of an email address (see [FILTER_VALIDATE_EMAIL](https://www.php.net/manual/en/filter.constants.php#constant.filter-validate-email))


### Multiple rules
Multiple rules can be attached to a field. Rules are evaluated in the order specified in the `addField()` call.

```php
$validator->addField(
    'email',
    $_POST['email'],
    [Validator::RULE_REQUIRED, Validator::VALID_EMAIL, $userDoesNotExistRule->getId()]
);
```

### Surfacing validation errors
`Validator::validate()` returns an array of `ValidationError` objects.

- If validation is successful, this array is empty.
- For every validation `Rule` that fails, there is a corresponding `ValidationError` object

The `ValidationError` object implements `jsonSerialize()` and can be surfaced as a JSON string easily via `json_encode()`. The outputted JSON has the following format:

```json 
{
  "field": "name-of-field",
  "message": "failure-message",
  "ruleId": "rule-id",
  "ruleFailedDueToShortCircuit": false 
}
```

### Short-circuit rule evaluation
By default, notary will evaluate *all rules* for a field (given via `Validator::addField()` call).

There are cases where this can be undesirable and/or unnecessary:

- A rule check may be expensive; e.g. requiring a network call to another service, and there is no reason to evaluate the rule if a prior rule has failed validation
- A rule check may be unnecessary; e.g. a `Validator::RULE_REQUIRED` check fails, there is no reason to evaluate rules where it's assumed the field value is present

In these cases, rule evaluation can be skipped, if/when there is a prior rule check failure, by setting the `$_shortCircuitOnRuleValidationFailure` to `true` when calling `Validator::addField()`, e.g.:

```php
$validator->addField(
    'email',
    $_POST['email'],
    [Validator::RULE_REQUIRED, Validator::VALID_EMAIL, $userDoesNotExistRule->getId()]
);
```

In the example above, if `$_POST['email']` is an empty string, `Validator::RULE_REQUIRED` fails and evaluation of the subsequent rules is skipped (with `ValidationError` instances returned indicating that validation of these rules have failed).
