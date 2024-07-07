<?php

namespace Notary\Tests;

use Notary\CommonRules\Required;

class RequiredTest extends \PHPUnit\Framework\TestCase
{
    public function testRuleCheckReturnsFalseForNullData()
    {
        $isRequiredRule = new Required();
        $ruleCheckFunc = $isRequiredRule->getRuleCheckFunction();

        $checkResult = $ruleCheckFunc(null);

        $this->assertEquals(false, $checkResult);
    }

    public function testRuleCheckReturnsFalseForEmptyStringData()
    {
        $isRequiredRule = new Required();
        $ruleCheckFunc = $isRequiredRule->getRuleCheckFunction();

        $checkResult = $ruleCheckFunc('');

        $this->assertEquals(false, $checkResult);
    }

    public function testRuleCheckReturnsTrueForNonEmptyStringData()
    {
        $isRequiredRule = new Required();
        $ruleCheckFunc = $isRequiredRule->getRuleCheckFunction();

        $checkResult = $ruleCheckFunc('test123');

        $this->assertEquals(true, $checkResult);
    }
}
