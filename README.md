#notary

PHP library to compose and check against validation rules

## Usage

```php
/*
 * Identifier for validator rule
 */
const VALIDATOR_RULE_NOT_EXISTING_USER = "__not_existing_user";

// Create Validator
$validator = new Validator();

// Add rule: user must not already be existing in system
$validator->addRule(VALIDATOR_RULE_NOT_EXISTING_USER, 
"User already exists", 

function($_email) {

    $ua = new UserAccount(Database::db());
    $user = $ua->getAccount($_email);
    if($user == null) {
        return true;
    }

    return false;            
});

// Add field, field value, and all rules to validate against
$validator->addField('email', $_POST['email'], 
        [Validator::RuleRequired, 
         Validator::RuleValidEmail,
         VALIDATOR_RULE_NOT_EXISTING_USER]);

// Validate and get back any errors
$errors = $validator->validate();

if(empty($errors)) {
    // We're good!
}
```