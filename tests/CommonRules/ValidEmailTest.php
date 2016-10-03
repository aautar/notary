<?php

namespace Notary\Tests;

use Notary\CommonRules\ValidEmail;

class ValidEmailTest extends \PHPUnit_Framework_TestCase
{
    public function testRuleCheckReturnsTrueForValidEmail()
    {
        $validEmailRule = new ValidEmail();
        $ruleCheckFunc = $validEmailRule->getRuleCheckFunction();

        $checkResult = $ruleCheckFunc("test@test.com");

        $this->assertEquals(true, $checkResult);
    }

    public function testRuleCheckReturnsFalseForInvalidEmail()
    {
        $validEmailRule = new ValidEmail();
        $ruleCheckFunc = $validEmailRule->getRuleCheckFunction();

        $checkResult = $ruleCheckFunc("test.com");

        $this->assertEquals(false, $checkResult);
    }
}