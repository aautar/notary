<?php

namespace Notary\CommonRules;

use Notary\Rule;

class ValidEmail extends Rule
{
    const RULE_ID = self::class;

    private function check($_data) {
        if(filter_var($_data, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    public function __construct() {
        parent::__construct(
                self::RULE_ID,
                "Invalid email",
                function($_data) {
                    return $this->check($_data);
                }
            );
    }
}
