<?php

namespace Notary;

class Rule
{
    protected $id;
    protected $ruleCheckFailureMessage;
    protected $ruleCheckFunction;

    public function __construct($_id, $_ruleCheckFailureMessage, $_ruleCheckFunction)
    {
        $this->id = $_id;
        $this->ruleCheckFailureMessage = $_ruleCheckFailureMessage;
        $this->ruleCheckFunction = $_ruleCheckFunction;
    }

    function getId()
    {
        return $this->id;
    }

    function getRuleCheckFailureMessage()
    {
        return $this->ruleCheckFailureMessage;
    }

    function getRuleCheckFunction()
    {
        return $this->ruleCheckFunction;
    }
}