<?php

namespace Notary\Tests;

use Notary\Validator;
use Notary\Rule;
use Notary\ValidationError;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{    
    protected $formValidator = null;
    
    public function setUp()
    {       
        $this->formValidator = new Validator();
    }

    public function testAddRuleThrowsLogicException()
    {
        $this->expectException(\LogicException::class);
        $this->formValidator->addRule(Validator::RULE_REQUIRED, "required!", function() { });
    }
   
    public function testAddField() 
    {
        $this->formValidator->addField('a', 'data', []);
    }
    
    public function testGetRuleReturnsNull()
    {
        $result = $this->formValidator->getRule(888);
        $this->assertEquals($result, null);
    }
    
    public function testGetRuleReturnsRuleObject()
    {
        $this->formValidator->addRule("r123", "R is invalid", function() { });

        $r = $this->formValidator->getRule("r123");
        $this->assertEquals(true, $r instanceof Rule);
    }


    public function testValidateNonExistentRuleThrowsLogicException()
    {
        $this->formValidator->addField("email", "@123", ["has-tld"]);

        $this->expectException(\LogicException::class);
        $this->formValidator->validate();
    }

    public function testValidateNoFieldsNoRules()
    {
        $result = $this->formValidator->validate();
        $this->assertEquals($result, []);
    }

    public function testValidateSuccessfulRuleCheck()
    {
        $this->formValidator->addField("email", "test@test.com", [Validator::RULE_REQUIRED]);
        $result = $this->formValidator->validate();
        $this->assertEquals($result, []);
    }

    public function testValidateFailedRuleReturnsValidationError()
    {
        $this->formValidator->addField("email", "testtest.com", [Validator::RULE_VALID_EMAIL]);
        $result = $this->formValidator->validate();
        $this->assertEquals(true, $result[0] instanceof ValidationError);
    }

    public function testValidateFailedRulesReturnsMultipleValidationErrors()
    {
        $this->formValidator->addField("email", "", [Validator::RULE_VALID_EMAIL, Validator::RULE_REQUIRED]);
        $validationErrors = $this->formValidator->validate();
        $this->assertEquals(2, count($validationErrors));
    }
}