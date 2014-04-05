<?php
/**
 * PHP Prerequisite Checker
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */

require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

class PhpExtensionPrereqCheck extends PrereqCheck {
    private $_name = 'PHP Extension: ';

    public function check() {
        $arg_list = func_get_args();
        $extension = $arg_list[0];
        $this->name = $this->_name . $extension;

        if (extension_loaded($extension) !== true) {
            $this->setFailed("Extension '{$extension}' not loaded.");
        }
    }
}