<?php
require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

class PhpVersionPrereqCheck extends PrereqCheck {
    public $_name = 'PHP Version Check';

    public function check() {
        $arg_list = func_get_args();
        $operator = $arg_list[0];
        $requiredVersion = $arg_list[1];
        $actualVersion = phpversion();
        $this->name = $this->_name . "({$operator} {$requiredVersion})";

        $res = new CheckResult(true,$this);

        if (version_compare ( $actualVersion, $requiredVersion, $operator) !== true) {
            $res->setFailed("Actual PHP Version ({$actualVersion}) does not meet the requirement {$operator} {$requiredVersion}");
        }
        return $res;
    }
}
