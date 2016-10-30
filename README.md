#notary

[![Build Status](https://travis-ci.org/aautar/notary.svg?branch=master)](https://travis-ci.org/aautar/notary)
[![codecov](https://codecov.io/gh/aautar/notary/branch/master/graph/badge.svg)](https://codecov.io/gh/aautar/notary)

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
    "RULE_USER_DOES_NOT_EXIST",     
    "Can't create user, user already exists",
    function($_email) { 
        $ua = new UserAccount(Database::db());
        return $ua->canCreateNewUser($_email);
    }
);

// Add field, field value, and all rules to validate against
$validator->addField(
    'email', 
    $_POST['email'], 
    [RULE_NOT_EXISTING_USER]
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
    "RULE_USER_DOES_NOT_EXIST", 
    "Can't create user, user already exists",
    function($_email) { 
        $ua = new UserAccount(Database::db());
        return $ua->canCreateNewUser($_email);
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
