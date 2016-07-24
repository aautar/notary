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
     * @var string
     */
    protected $errorMessage;

    /**
     * @param string $_fieldName
     * @param Rule $_ruleFailed
     */
    public function __construct($_fieldName, Rule $_ruleFailed)
    {
        $this->fieldName = $_fieldName;
        $this->ruleIdFailed = $_ruleFailed->getId();
        $this->errorMessage = $_ruleFailed->getRuleCheckFailureMessage();
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

    /**
     * JSON representation
     */
    public function jsonSerialize()
    {
        return [
            'field' => $this->getFieldName(),
            'message' => $this->getErrorMessage(),
            'ruleId' => $this->getRuleIdFailed()
        ];
    }
}