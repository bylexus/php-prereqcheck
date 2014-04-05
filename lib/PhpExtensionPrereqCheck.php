<?php
require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

class PhpExtensionPrereqCheck extends PrereqCheck {
    private $_name = 'PHP Extension: ';

    public function check() {
        $arg_list = func_get_args();
        $extension = $arg_list[0];
        $this->name = $this->_name . $extension;

        $res = new CheckResult(true,$this);

        if (extension_loaded($extension) !== true) {
            $res->setFailed("Extension '{$extension}' not loaded.");
        }
        return $res;
    }
}