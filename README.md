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

## Usage

```php
/*
 * Identifier for validator rule
 */
const RULE_NOT_EXISTING_USER = "__not_existing_user";

// Create Validator
$validator = new Validator();

// Add rule: user must not already be existing in system
$validator->addRule(
    RULE_NOT_EXISTING_USER,
    "User already exists", 
    function($_email) {
        $ua = new UserAccount(Database::db());
        $user = $ua->getAccount($_email);
        if($user == null) {
            return true;
        }

        return false;            
    }
);

// Add field, field value, and all rules to validate against
$validator->addField('email', $_POST['email'], 
        [Validator::RULE_REQUIRED,
         Validator::RULE_VALID_EMAIL,
         RULE_NOT_EXISTING_USER]);

// Validate and get back any errors
$errors = $validator->validate();

if(empty($errors)) {
    // We're good!
}
```
