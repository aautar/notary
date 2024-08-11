<?php

namespace Notary;

class ValidationError implements \JsonSerializable
{
    /**
     * @var string 
     */
    protected $fieldName;

    /**
     * @var string
     */
    protected $ruleIdFailed;

    /**
     * @var bool
     */
    protected $ruleFailedDueToShortCircuit;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @param string $_fieldName
     * @param Rule $_ruleFailed
     * @param bool $_ruleFailedDueToShortCircuit
     */
    public function __construct(string $_fieldName, Rule $_ruleFailed, bool $_ruleFailedDueToShortCircuit=false)
    {
        $this->fieldName = $_fieldName;
        $this->ruleIdFailed = $_ruleFailed->getId();
        $this->errorMessage = $_ruleFailed->getRuleCheckFailureMessage();
        $this->ruleFailedDueToShortCircuit = $_ruleFailedDueToShortCircuit;
    }

    /**
     * @return string
     */
    function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    function getRuleIdFailed()
    {
        return $this->ruleIdFailed;
    }

    /**
     * @return string
     */
    function getErrorMessage()
    {
        return $this->errorMessage;
    }

    function wasRuleFailureDueToShortCircuit(): bool
    {
        return $this->ruleFailedDueToShortCircuit;
    }

    /**
     * JSON representation
     */
    public function jsonSerialize()
    {
        return [
            'field' => $this->getFieldName(),
            'message' => $this->getErrorMessage(),
            'ruleId' => $this->getRuleIdFailed(),
            'ruleFailedDueToShortCircuit'=> $this->wasRuleFailureDueToShortCircuit(),
        ];
    }
}
