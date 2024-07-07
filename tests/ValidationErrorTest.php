<?php

namespace Notary\Tests;

use Notary\ValidationError;
use Notary\Rule;

class ValidatorErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testJsonSerializeReturnsArray()
    {
        $error = new ValidationError("name", new Rule("r123", "something bad happened", function() { }));
        $jsonSerializeOutput = $error->jsonSerialize();

        $this->assertEquals(true, is_array($jsonSerializeOutput));
    }

    public function testCanJsonEncodeValidatorErrorObject()
    {
        $error = new ValidationError("name", new Rule("r123", "something bad happened", function() { }));
        $json = json_encode($error);

        $this->assertEquals('{"field":"name","message":"something bad happened","ruleId":"r123"}', $json);
    }

}