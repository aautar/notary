<?php

namespace Notary\CommonRules;

use Notary\Rule;

class Required extends Rule
{
    const RULE_ID = "notary.common_rule.required";

    private function check($_data) {
        return (!empty($_data));
    }

    public function __construct() {
        parent::__construct(
                self::RULE_ID,
                "Required",
                function($_data) {
                    return $this->check($_data);
                }
            );
    }
}
