<?php

namespace Notary;

class Validator
{
    const RULE_REQUIRED = CommonRules\Required::RULE_ID;
    const RULE_VALID_EMAIL = CommonRules\ValidEmail::RULE_ID;

    /**
     * Array of rules
     *
     * @var Rule[]
     */
    protected $rulesDomain = [];

    /**
     * Mapping of field to array of rule IDs to validate against
     * @var array<string, string[]>
     */
    protected $fieldToRuleIdsForValidation = [];

    /**
     * Mapping of field to data against which rules are checked
     * @var array
     */
    protected $fieldToData = [];

    /**
     * Mapping of field to whether or not validation can be short circuited
     * @var array<string, bool>
     */
    protected $fieldToAllowShortCircuitOnRuleValidationFailure = [];
       
    function __construct()
    {
        $this->addNewRule(new CommonRules\Required());
        $this->addNewRule(new CommonRules\ValidEmail());
    }

    /**
     * Get a rule which has been added to the validator
     *
     * @todo probably makes sense to refactor such that exception is thrown if a rule isn't found, instead of returning null
     *
     * @param string $ruleId
     * @return Rule|null
     */
    public function getRule(string $ruleId): ?Rule
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
     * @return bool
     * @throws \LogicException
     */
    public function addRule(string $_id, string $_ruleCheckFailureMessage, callable $_ruleCheckFunction): bool
    {
        return $this->addNewRule(new Rule($_id, $_ruleCheckFailureMessage, $_ruleCheckFunction));
    }

    /**
     * Add a new rule
     *
     * @param Rule $_newRule
     * @return boolean
     * @throws \LogicException
     */
    public function addNewRule(Rule $_newRule): bool
    {
        if($this->getRule($_newRule->getId()) !== null) {
            throw new \LogicException("Rule already added");
        }

        $this->rulesDomain[] = $_newRule;
        return true;
    }
    
    /**
     * Add a field to be validated
     *
     * @param string $_field name of field
     * @param mixed $_data data within field
     * @param string[] $_ruleIds array of rule IDs to validate against
     * @param bool $_shortCircuitOnRuleValidationFailure short circuit on a rule validation failure (don't validate subsequent rules, assume they've failed)
     */
    public function addField(string $_field, $_data, array $_ruleIds, bool $_shortCircuitOnRuleValidationFailure = false)
    {       
        $this->fieldToRuleIdsForValidation[$_field] = $_ruleIds;
        $this->fieldToData[$_field] = $_data;
        $this->fieldToAllowShortCircuitOnRuleValidationFailure[$_field] = $_shortCircuitOnRuleValidationFailure;
    }

    /**
     *
     * Validate fields against rules
     *
     * @return ValidationError[]
     * @throws \LogicException
     */
    public function validate(): array
    {
        $validationErrors = array();
        
        foreach($this->fieldToRuleIdsForValidation as $field => $ruleIds) {

            $canShortCircuit = $this->fieldToAllowShortCircuitOnRuleValidationFailure[$field];
            $detectedRuleValidationFailure = false;
           
            foreach($ruleIds as $rid) {
                $rule = $this->getRule($rid);
                if($rule === null) {
                    throw new \LogicException("Trying to validate against a rule that doesn't exist ({$rid})");
                }

                // If prior rule check failure for field, short circuit if allowed
                if($detectedRuleValidationFailure && $canShortCircuit) {
                    $validationErrors[] = new ValidationError($field, $rule, true);
                    continue;
                }

                $ruleCheckFunc = $rule->getRuleCheckFunction();
                $isOk = $ruleCheckFunc($this->fieldToData[$field]);
                if(!$isOk) {
                    $validationErrors[] = new ValidationError($field, $rule, false);
                    $detectedRuleValidationFailure = true;
                }
            }
        }
        
        return $validationErrors;        
    }
}
