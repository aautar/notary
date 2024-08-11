<?php

namespace Notary;

class Rule
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $ruleCheckFailureMessage;

    /**
     * @var callable
     */
    protected $ruleCheckFunction;

    /**
     * @param string $_id Identifier for this rule
     * @param string $_ruleCheckFailureMessage Message to surface when validation rule fails
     * @param callable $_ruleCheckFunction Function to evaluate validation rule
     */
    public function __construct(string $_id, string $_ruleCheckFailureMessage, callable $_ruleCheckFunction)
    {
        $this->id = $_id;
        $this->ruleCheckFailureMessage = $_ruleCheckFailureMessage;
        $this->ruleCheckFunction = $_ruleCheckFunction;
    }

    function getId(): string
    {
        return $this->id;
    }

    function getRuleCheckFailureMessage(): string
    {
        return $this->ruleCheckFailureMessage;
    }

    function getRuleCheckFunction(): callable
    {
        return $this->ruleCheckFunction;
    }
}
