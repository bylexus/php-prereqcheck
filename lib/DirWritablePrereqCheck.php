<?php
/**
 * PHP Prerequisite Checker
 *
 * (c) 2014 Alexander Schenkel, info@alexi.ch
 */

require_once(dirname(__FILE__).'/PrereqCheck.php');
require_once(dirname(__FILE__).'/CheckResult.php');

class DirWritablePrereqCheck extends PrereqCheck {
    private $_name = 'Dir writable: ';

    public function check() {
        $arg_list = func_get_args();
        $dir = $arg_list[0];
        $this->name = $this->_name . $dir;

        if (!is_dir($dir) || !is_writable($dir)) {
            $this->setFailed("Directory '{$dir}' not writable.");
        }
    }
}