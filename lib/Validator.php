<?php

namespace Notary;

class Validator
{
    const RuleRequired = '_required';
    const RuleValidEmail = '_valid_email';

    /**
     * Array of rules
     *
     * @var Rule[]
     */
    protected $rulesDomain = [];

    /**
     * Mapping of field to array of rule IDs to validate against
     * @var string[]
     */
    protected $fieldToRuleIdsForValidation = [];

    /**
     * Mapping of field to data against which rules are checked
     * @var array
     */
    protected $fieldToData = [];
       
    function __construct()
    {
        $this->addRule(Validator::RuleRequired, "Required",
                        function($_data) {
                            return (!empty($_data) && $_data !== null && strlen($_data) > 0);
                        });          
        
        $this->addRule(Validator::RuleValidEmail, "Invalid email",
                        function($_data) {
                            return filter_var($_data, FILTER_VALIDATE_EMAIL);
                        });      
    }

    /**
     * Get a rule which has been added to the validator
     *
     * @param string $ruleId
     * @return Rule|null
     */
    public function getRule($ruleId)
    {
        foreach($this->rulesDomain as $r) {
            if($r->getId() === $ruleId) {
                return $r;
            }
        }

        return null;
    }

    /**
     * Add a new rule
     *
     * @param string $_id
     * @param string $_ruleCheckFailureMessage
     * @param callable $_ruleCheckFunction
     * @return boolean
     * @throws \LogicException
     */
    public function addRule($_id, $_ruleCheckFailureMessage, $_ruleCheckFunction)
    {
        if($this->getRule($_id) !== null) {
            throw new \LogicException("Rule already added");
        }
               
        $this->rulesDomain[] = new Rule($_id, $_ruleCheckFailureMessage, $_ruleCheckFunction);
        return true;
    }
    
    /**
     * Add a field to be validated
     *
     * @param string $_field name of field
     * @param mixed $_data data within field
     * @param string[] $_ruleIds array of rule IDs to validate against
     */
    public function addField($_field, $_data, array $_ruleIds)
    {       
        $this->fieldToRuleIdsForValidation[$_field] = $_ruleIds;
        $this->fieldToData[$_field] = $_data;
    }


    /**
     *
     * Validate fields against rules
     *
     * @return ValidationError[]
     * @throws \LogicException
     */
    public function validate()
    {
        $validationErrors = array();
        
        foreach($this->fieldToRuleIdsForValidation as $field => $ruleIds) {            
           
            foreach($ruleIds as $rid) {
                                
                $rule = $this->getRule($rid);
                if($rule === null) {
                    throw new \LogicException("Trying to validate against a rule that doesn't exist (" . $rid . ")");
                }

                $ruleCheckFunc = $rule->getRuleCheckFunction();
                $isOk = $ruleCheckFunc($this->fieldToData[$field]);
                if(!$isOk) {
                    $validationErrors[] = new ValidationError($field, $rule);
                }
            }
            
        }
        
        return $validationErrors;        
    }
}